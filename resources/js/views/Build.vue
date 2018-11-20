<template>
    <div style="margin-top: 15px;">
        <b-card no-body>
            <b-tabs pills lazy card>

                <b-tab active>
                    <template slot="title">
                        <font-awesome-icon :icon="['fas', 'cubes']"/>
                        <strong>VM Templates</strong>
                    </template>

                    <div v-if="!loaded_template" class="center-div">
                        <h3 class="text-info">
                            Loading
                            <font-awesome-icon :icon="['fas', 'circle-notch']" spin/>
                        </h3>
                    </div>
                    <div v-else>
                        <b-table striped hover :items="vmTemplates" :fields="vmTemplateFields">
                            <template slot="actions" slot-scope="row">
                                <b-button size="sm" variant="danger"
                                          @click.stop="delTemplate(row.item, row.index, $event.target)" class="mr-1">
                                    <font-awesome-icon :icon="['fas', 'trash']"/>
                                </b-button>
                            </template>
                            <template slot="operating_system.name" slot-scope="data">
                                <i :class="data.item.operating_system.logo"></i> {{data.item.operating_system.name}}
                            </template>
                        </b-table>
                        <b-card-footer>
                            <b-button v-b-modal.newTemplateModal size="sm" variant="success">
                                <font-awesome-icon :icon="['far', 'plus-square']"/>
                                Add Template
                            </b-button>
                        </b-card-footer>

                        <b-modal
                                id="newTemplateModal"
                                ref="newTemplateModal"
                                title="Add VM Template"
                                @cancel="onResetNewTemplate"
                                @ok="onSubmitNewTemplate"
                                cancel-variant="outline-danger"
                                ok-variant="outline-primary"
                                ok-title="Save">
                            <b-form @submit="onSubmitNewTemplate" @reset="onResetNewTemplate">

                                <b-form-group label="Template Label">
                                    <b-form-input id="templateName"
                                                  type="text"
                                                  v-model="newTemplate.name"
                                                  required
                                                  placeholder="A label for this template">
                                    </b-form-input>
                                </b-form-group>
                                <b-form-group label="vCenter Template Name">
                                    <b-form-input id="vmTemplateName"
                                                  type="text"
                                                  v-model="newTemplate.vm_template_name"
                                                  required
                                                  placeholder="The template name in vCenter">
                                    </b-form-input>
                                </b-form-group>
                                <b-form-group label="Template Image URL">
                                    <b-form-input id="vmTemplateName"
                                                  type="text"
                                                  v-model="newTemplate.image_url"
                                                  placeholder="An for this template (optional)">
                                    </b-form-input>
                                </b-form-group>

                                <b-form-group label="Template OS">
                                    <b-form-select v-if="loaded_os" v-model="newTemplate.operating_system_id"
                                                   :options="osOptions" required>
                                        <template slot="first">
                                            <option :value="null" disabled>-- Select an Operating System --</option>
                                        </template>
                                    </b-form-select>
                                </b-form-group>

                            </b-form>
                        </b-modal>
                    </div>
                </b-tab>

                <b-tab>
                    <template slot="title">
                        <font-awesome-icon :icon="['fas', 'network-wired']"/>
                        <strong>Networks</strong>
                    </template>

                    <div v-if="!loaded_net" class="center-div">
                        <h3 class="text-info">
                            Loading
                            <font-awesome-icon :icon="['fas', 'circle-notch']" spin/>
                        </h3>
                    </div>
                    <div v-else>
                        <b-table striped hover :items="networks" :fields="networkFields">
                            <template slot="actions" slot-scope="row">
                                <b-button size="sm" variant="danger"
                                          @click.stop="delNetwork(row.item, row.index, $event.target)" class="mr-1">
                                    <font-awesome-icon :icon="['fas', 'trash']"/>
                                </b-button>
                            </template>
                        </b-table>
                        <b-card-footer>
                            <b-button v-b-modal.newNetworkModal size="sm" variant="success">
                                <font-awesome-icon :icon="['far', 'plus-square']"/>
                                Add Network
                            </b-button>
                        </b-card-footer>

                        <b-modal
                                id="newNetworkModal"
                                ref="newNetworkModal"
                                title="Add Network"
                                @cancel="onResetNewNetwork"
                                @ok="onSubmitNewNetwork"
                                cancel-variant="outline-danger"
                                ok-variant="outline-primary"
                                ok-title="Save">
                            <b-form @submit="onSubmitNewNetwork" @reset="onResetNewNetwork">
                                <b-form-group label="Network Label">
                                    <b-form-input id="networkName"
                                                  type="text"
                                                  v-model="newNetwork.name"
                                                  required
                                                  placeholder="A label for this network">
                                    </b-form-input>
                                </b-form-group>
                                <b-form-group label="Subnet Mask">
                                    <b-form-input id="networkName"
                                                  type="number"
                                                  min="0"
                                                  step="1"
                                                  v-model="newNetwork.subnet_mask"
                                                  required
                                                  placeholder="The subnet mask of the network">
                                    </b-form-input>
                                </b-form-group>
                                <b-form-group label="DHCP">
                                    <toggle-button
                                            id="networkDHCP"
                                            v-model="newNetwork.dhcp"/>
                                </b-form-group>
                            </b-form>
                        </b-modal>

                    </div>
                </b-tab>

                <b-tab>
                    <template slot="title">
                        <font-awesome-icon :icon="['fas', 'cogs']"/>
                        <strong>Specs</strong>
                    </template>

                    <div v-if="!loaded_spec" class="center-div">
                        <h3 class="text-info">
                            Loading
                            <font-awesome-icon :icon="['fas', 'circle-notch']" spin/>
                        </h3>
                    </div>
                    <div v-else>

                        <b-table striped hover :items="customizationSpecs" :fields="specFields">
                            <template slot="actions" slot-scope="row">
                                <b-button size="sm" variant="danger"
                                          @click.stop="delSpec(row.item, row.index, $event.target)" class="mr-1">
                                    <font-awesome-icon :icon="['fas', 'trash']"/>
                                </b-button>
                            </template>
                        </b-table>

                        <b-card-footer>
                            <b-button v-b-modal.newCustomizationSpecModal size="sm" variant="success">
                                <font-awesome-icon :icon="['far', 'plus-square']"/>
                                Add Spec
                            </b-button>
                        </b-card-footer>

                        <b-modal
                                id="newCustomizationSpecModal"
                                ref="newCustomizationSpecModal"
                                title="Add Customization Spec"
                                @cancel="onResetCustomizationSpec"
                                @ok="onSubmitCustomizationSpec"
                                cancel-variant="outline-danger"
                                ok-variant="outline-primary"
                                ok-title="Save">

                            <b-form-group label="Spec Label">
                                <b-form-input id="specName"
                                              type="text"
                                              v-model="newSpec.name"
                                              required
                                              placeholder="Label this customization spec">
                                </b-form-input>
                            </b-form-group>

                            <b-form-group label="VM Name Prefix">
                                <b-form-input id="specVmNamePrefix"
                                              type="text"
                                              v-model="newSpec.vm_name_prefix"
                                              placeholder="Prefix the VM in vCenter with the following (optional)">
                                </b-form-input>
                            </b-form-group>

                            <b-form-group label="Node Name Postfix">
                                <b-form-input id="specNodeNamePostfix"
                                              type="text"
                                              v-model="newSpec.node_name_postfix"
                                              placeholder="Append the following the the node name in Chef (optional)">
                                </b-form-input>
                            </b-form-group>

                            <b-form-group label="Knife vSphere Arguments">
                                <b-form-input id="specProvisionCommand"
                                              type="text"
                                              v-model="newSpec.provision_command"
                                              required
                                              placeholder="Arguments to append to the knife vSphere command">
                                </b-form-input>
                            </b-form-group>

                            <b-form-group label="Template">
                                <b-form-select v-if="loaded_template" v-model="newSpec.vm_template_id"
                                               :options="templateOptions" required>
                                    <template slot="first">
                                        <option :value="null" disabled>-- Select a template --</option>
                                    </template>
                                </b-form-select>
                            </b-form-group>

                            <b-form-group label="Network">
                                <b-form-select v-if="loaded_net" v-model="newSpec.network_id"
                                               :options="netOptions" required>
                                    <template slot="first">
                                        <option :value="null" disabled>-- Select a network --</option>
                                    </template>
                                </b-form-select>
                            </b-form-group>

                        </b-modal>
                    </div>
                </b-tab>

            </b-tabs>
        </b-card>
    </div>
</template>

<script>
    export default {
        name: "build",
        data() {
            return {
                loaded_os: false,
                loaded_net: false,
                loaded_spec: false,
                loaded_template: false,
                operatingSystems: [],
                networks: [],
                customizationSpecs: [],
                vmTemplates: [],
                osOptions: [],
                templateOptions: [],
                netOptions: [],
                vmTemplateFields: {
                    name: {label: 'Label'},
                    vm_template_name: {label: 'VM Template'},
                    OS: {key: 'operating_system.name', label: 'OS'},
                    actions: {}
                },
                networkFields: {
                    name: {label: 'Label'},
                    subnet_mask: {},
                    dhcp: {label: 'DHCP'},
                    actions: {}
                },
                specFields: {
                    name: {label: 'Label'},
                    vm_name_prefix: {label: 'VM Prefix'},
                    node_name_postfix: {label: 'Node Postfix'},
                    actions: {}
                },
                newTemplate: {
                    name: '',
                    vm_template_name: '',
                    operating_system_id: null,
                    image_url: null
                },
                newNetwork: {
                    name: '',
                    subnet_mask: 24,
                    dhcp: false
                },
                newSpec: {
                    name: '',
                    provision_command: '',
                    vm_name_prefix: null,
                    node_name_postfix: null,
                    vm_template_id: null,
                    network_id: null
                }
            }
        },
        ready() {
            this.prepareComponent();
        },
        mounted() {
            this.prepareComponent();
        },
        methods: {
            prepareComponent() {
                console.log('Mounted & Ready!');
                this.getOperatingSystems();
                this.getNetworks();
                this.getCustomizationSpecs();
                this.getVmTemplats();
            },
            onSubmitNewTemplate(evt) {
                evt.preventDefault();
                let form = this.newTemplate;
                axios['post']('/api/template', form).then(response => {
                    this.$refs.newTemplateModal.hide();
                    this.newTemplate.vm_template_name = '';
                    this.newTemplate.name = '';
                    this.newTemplate.operating_system_id = null;
                    this.getVmTemplats();
                });
            },
            onResetNewTemplate(evt) {
                evt.preventDefault();
                this.newTemplate.vm_template_name = '';
                this.newTemplate.name = '';
                this.newTemplate.operating_system_id = null;
                this.$refs.newTemplateModal.hide();
            },
            delTemplate(item, index, button) {
                axios.delete('/api/template/' + item.id).then(response => {
                    this.getVmTemplats();
                    this.getCustomizationSpecs();
                });
            },
            onSubmitNewNetwork(evt) {
                evt.preventDefault();
                let form = this.newNetwork;
                axios['post']('/api/network', form).then(response => {
                    this.$refs.newNetworkModal.hide();
                    this.newNetwork.name = '';
                    this.newNetwork.subnet_mask = 24;
                    this.newNetwork.dhcp = false;
                    this.getNetworks();
                });
            },
            onResetNewNetwork(evt) {
                evt.preventDefault();
                this.newNetwork.name = '';
                this.newNetwork.subnet_mask = 24;
                this.newNetwork.dhcp = false;
                this.$refs.newNetworkModal.hide();
            },
            delNetwork(item, index, button) {
                axios.delete('/api/network/' + item.id).then(response => {
                    this.getNetworks();
                    this.getCustomizationSpecs();
                });
            },
            onSubmitCustomizationSpec(evt) {
                evt.preventDefault();
                let form = this.newSpec;
                axios['post']('/api/spec', form).then(response => {
                    this.$refs.newCustomizationSpecModal.hide();
                    this.newSpec.name = '';
                    this.newSpec.network_id = null;
                    this.newSpec.vm_template_id = null;
                    this.newSpec.provision_command = '';
                    this.newSpec.vm_name_prefix = '';
                    this.newSpec.node_name_postfix = '';
                    this.getCustomizationSpecs();
                });
            },
            onResetCustomizationSpec(evt) {
                evt.preventDefault();
                this.newSpec.name = '';
                this.newSpec.network_id = null;
                this.newSpec.vm_template_id = null;
                this.newSpec.provision_command = '';
                this.newSpec.vm_name_prefix = '';
                this.newSpec.node_name_postfix = '';
                this.$refs.newCustomizationSpecModal.hide();
            },
            delSpec(item, index, button) {
                axios.delete('/api/spec/' + item.id).then(response => {
                    this.getCustomizationSpecs();
                });
            },
            getOperatingSystems() {
                this.loaded_os = false;
                axios.get('/api/os')
                    .then(response => {
                        this.operatingSystems = response.data;
                        this.$emit('pulled-operating-systems');
                        this.osOptions = [];
                        for (let os of this.operatingSystems) {
                            this.osOptions.push({
                                value: os.id,
                                text: os.name
                            })
                        }
                        this.loaded_os = true;
                    });
            },
            getNetworks() {
                this.loaded_net = false;
                axios.get('/api/network')
                    .then(response => {
                        this.networks = response.data;
                        this.$emit('pulled-networks');
                        this.netOptions = [];
                        for (let net of this.networks) {
                            this.netOptions.push({
                                value: net.id,
                                text: net.name
                            })
                        }
                        this.loaded_net = true;
                    });
            },
            getCustomizationSpecs() {
                this.loaded_spec = false;
                axios.get('/api/spec')
                    .then(response => {
                        this.customizationSpecs = response.data;
                        this.$emit('pulled-customization-specs');
                        this.loaded_spec = true;
                    });
            },
            getVmTemplats() {
                this.loaded_template = false;
                axios.get('/api/template')
                    .then(response => {
                        this.vmTemplates = response.data;
                        this.$emit('pulled-vm-templates');
                        this.templateOptions = [];
                        for (let template of this.vmTemplates) {
                            this.templateOptions.push({
                                value: template.id,
                                text: template.name
                            })
                        }
                        this.loaded_template = true;
                    });
            },
        }
    }
</script>

<style scoped>

</style>