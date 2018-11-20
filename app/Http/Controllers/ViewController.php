<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeploySession;

class ViewController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('provision');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function build()
    {
        return view('build');
    }

    /**
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSession($token)
    {
        $session = DeploySession::where('token', $token)->firstOrFail();
        $ws = config('santoku.conf.ws.https', false) ? 'https://' : 'http://';
        $ws = $ws . config('santoku.conf.ws.address', '192.168.10.10') . ':' . config('santoku.conf.ws.port', '3000');
        return view('session', ['token' => $token, 'ws' => $ws]);
    }
}
