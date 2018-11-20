<?php

namespace App\Http\Controllers;

use App\DeploySession;
use Illuminate\Http\Request;
use App\OperatingSystem;
use App\Network;
use App\VmTemplate;
use App\CustomizationSpec;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

class ApiController extends Controller
{
    /*
     *
     * Operating System Model
     *
     */

    /**
     * @return false|string
     */
    public function getOperatingSystems()
    {
        return json_encode(OperatingSystem::all());
    }

    /**
     * @param $id
     * @return false|string
     */
    public function getOperatingSystem($id)
    {
        return json_encode(OperatingSystem::findOrFail($id));
    }

    /*
     *
     * VM Template Model
     *
     */

    /**
     * @return false|string
     */
    public function getTemplates()
    {
        return json_encode(VmTemplate::with('operatingSystem')->get());
    }

    /**
     * @param $id
     * @return false|string
     */
    public function getTemplate($id)
    {
        return json_encode(VmTemplate::findOrFail($id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function storeTemplate(Request $request)
    {
        // Get the request data
        $data = $request->all();
        // Validate the POST data
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'vm_template_name' => 'required|string',
            'operating_system_id' => 'required|integer|exists:operating_systems,id',
            'image_url' => 'nullable|string'
        ]);
        if ($validator->fails()) return response()->make($validator->errors(), 400);
        VmTemplate::create($data);
        // Return a response
        return response('', 204);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delTemplate($id)
    {
        VmTemplate::findOrFail($id)->delete();
        return Response::make('', 204);
    }

    /*
     *
     * Network Model
     *
     */

    /**
     * @return false|string
     */
    public function getNetworks()
    {
        return json_encode(Network::all());
    }

    /**
     * @param $id
     * @return false|string
     */
    public function getNetwork($id)
    {
        return json_encode(Network::findOrFail($id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function storeNetwork(Request $request)
    {
        // Get the request data
        $data = $request->all();
        // Validate the POST data
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'subnet_mask' => 'required|integer',
            'dhcp' => 'boolean'
        ]);
        if ($validator->fails()) return response()->make($validator->errors(), 400);
        Network::create($data);
        // Return a response
        return response('', 204);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delNetwork($id)
    {
        Network::findOrFail($id)->delete();
        return Response::make('', 204);
    }

    /*
     *
     * Customization Spec Model
     *
     */

    /**
     * @return false|string
     */
    public function getSpecs()
    {
        return json_encode(
            CustomizationSpec::with('network')
                ->with('vmTemplate')
                ->with('vmTemplate.operatingSystem')
                ->get()
        );
    }

    /**
     * @param $id
     * @return false|string
     */
    public function getSpec($id)
    {
        return json_encode(CustomizationSpec::findOrFail($id));
    }

    public function getSpecFromToken($token)
    {
        $session = DeploySession::where('token', $token)
            ->firstOrFail();

        return json_encode(CustomizationSpec::with('network')
            ->with('vmTemplate')
            ->with('vmTemplate.operatingSystem')
            ->findOrFail($session->customization_spec_id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function storeSpec(Request $request)
    {
        // Get the request data
        $data = $request->all();
        // Validate the POST data
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'vm_template_id' => 'required|integer|exists:vm_templates,id',
            'network_id' => 'required|integer|exists:networks,id',
            'vm_name_prefix' => 'string',
            'node_name_postfix' => 'string',
            'provision_command' => 'required|string'
        ]);
        if ($validator->fails()) return response()->make($validator->errors(), 400);
        CustomizationSpec::create($data);
        // Return a response
        return response('', 204);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delSpec($id)
    {
        CustomizationSpec::findOrFail($id)->delete();
        return Response::make('', 204);
    }

    /**
     * @param Request $request
     * @return false|\Illuminate\Http\Response|string
     */
    public function requestSession(Request $request)
    {
        // Get the request data
        $data = $request->all();
        // Validate the POST data
        $validator = Validator::make($data, [
            'id' => 'required|integer|exists:customization_specs,id'
        ]);
        if ($validator->fails()) return response()->make($validator->errors(), 400);

        $session = DeploySession::create([
            'customization_spec_id' => $data['id'],
            'token' => generateDeploySessionToken()
        ]);

        return json_encode($session);
    }

    /**
     * @param Request $request
     * @return false|\Illuminate\Http\Response|string
     */
    public function runSession(Request $request)
    {
        // Get the request data
        $data = $request->all();
        // Validate the POST data
        $validator = Validator::make($data, [
            'token' => 'required|string|exists:deploy_sessions,token',
            'hostname' => 'string|required',
            'ip' => 'ip|nullable'
        ]);
        if ($validator->fails()) return response()->make($validator->errors(), 400);

        $session = DeploySession::where('token', $data['token'])
            ->firstOrFail();
        $spec = CustomizationSpec::with('network')
            ->with('vmTemplate')
            ->with('vmTemplate.operatingSystem')
            ->findOrFail($session->customization_spec_id);

        $knife = config('santoku.bins.knife');
        $knifeRB = config('santoku.conf.kniferb');
        $hostname = $data['hostname'];
        $prefix = empty($spec->vm_name_prefix) ? '' : $spec->vm_name_prefix;
        $postfix = empty($spec->node_name_postfix) ? '' : $spec->node_name_postfix;
        $vmName = $prefix . $hostname;
        $template = $spec->vmTemplate->vm_template_name;
        $nodeName = $hostname . $postfix;
        $userArgs = $spec->provision_command;
        $knifeArgs = [
            'vsphere',
            'vm',
            'clone',
            $vmName,
            '--config',
            $knifeRB,
            '--template',
            $template,
            '--chostname',
            $hostname,
            '--node-name',
            $nodeName,
            '--yes'
        ];
        if (!$spec->network->dhcp) {
            $ipMask = '"' . $data['ip'] . '/' . strval($spec->network->subnet_mask) . '"';
            $knifeArgs =  array_merge($knifeArgs, ['--cips', $ipMask]);
        }
        $knifeArgs = array_merge($knifeArgs, array_map('trim', explode(',', $userArgs)));
        $fullCommand = $knife . ' ' . implode(' ', $knifeArgs);
        $provisionData = [
            'token' => $data['token'],
            'knife' => $knife,
            'knife_args' => $knifeArgs,
            'knife_rb' => $knifeRB,
            'knife_fill_cmd' => $fullCommand,
            'ip' => array_key_exists('ip', $data) ? $data['ip'] : null,
            'hostname' => $hostname,
            'vm_prefix' => $prefix,
            'node_postfix' => $postfix,
            'vm_name' => $vmName,
            'vm_template' => $template,
            'chef_node_name' => $nodeName,
            'custom_bootstrap_args' => $userArgs,
            'customization_sped' => $spec,
            'session' => $session
        ];

        Redis::publish('sessions', json_encode(
            [
                'event' => 'provision',
                'token' => $data['token'],
                'data' => $provisionData
            ]
        ));

        return json_encode($provisionData);
    }
}
