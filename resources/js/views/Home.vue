<template>
    <div>
        <div v-if="customizationSpecs.length === 0 && loaded_spec" class="center-div" style="margin-top:10px;">
            <h2>Nothing here...</h2>
            <h4>Create build some networks and templates.</h4>
        </div>
        <div v-if="!loaded_spec" class="center-div" style="margin-top:20px;">
            <h3 class="text-info">
                Loading
                <font-awesome-icon :icon="['fas', 'circle-notch']" spin/>
            </h3>
        </div>
        <b-card-group deck v-else style="margin-top:20px;">
            <div v-for="spec in customizationSpecs">
                <b-card v-if="spec.vm_template.image_url !== null" :title="spec.name" :key="'spec-'+spec.id"
                        :img-src="spec.vm_template.image_url"
                        img-top
                        style="max-width: 20em;">
                    <p class="card-text">
                        <i class="fas fa-network-wired"></i> {{spec.network.name}}
                    </p>
                    <p class="card-text">
                        <i :class="spec.vm_template.operating_system.logo"></i>
                        {{spec.vm_template.operating_system.name}}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-cube"></i> {{spec.vm_template.vm_template_name}}
                    </p>
                    <b-button variant="primary" @click.stop="startDeploy(spec)">Deploy <font-awesome-icon :icon="['fas', 'cloud-upload-alt']"/></b-button>
                </b-card>

                <b-card v-else :title="spec.name" :key="'spec-'+spec.id" style="max-width: 20em;">
                    <p class="card-text">
                        <i class="fas fa-network-wired"></i> {{spec.network.name}}
                    </p>
                    <p class="card-text">
                        <i :class="spec.vm_template.operating_system.logo"></i>
                        {{spec.vm_template.operating_system.name}}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-cube"></i> {{spec.vm_template.vm_template_name}}
                    </p>
                    <b-button variant="primary" @click.stop="startDeploy(spec)">Deploy <font-awesome-icon :icon="['fas', 'cloud-upload-alt']"/></b-button>
                </b-card>
            </div>
        </b-card-group>

        <b-modal
                id="deployModal"
                ref="deployModal"
                title="Deployment Customization"
                @cancel="resetDeploy"
                @ok="requestSession"
                cancel-variant="outline-danger"
                ok-variant="outline-primary"
                ok-title="Deploy"
        >
            <b-form-group label="VM Hostname">
                <b-form-input id="specName"
                              type="text"
                              v-model="newDeploy.hostname"
                              required
                              placeholder="The hostname of the new vm">
                </b-form-input>
            </b-form-group>

            <b-form-group v-if="!target.network.dhcp" label="VM IP">
                <b-form-input id="specVmNamePrefix"
                              type="text"
                              required
                              v-model="newDeploy.ip"
                              placeholder="The IP that the new host should be given">
                </b-form-input>
            </b-form-group>

        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "home",
        data() {
            return {
                loaded_spec: false,
                customizationSpecs: [],
                newDeploy: {
                    ip: null,
                    hostname: '',
                    token: ''
                },
                target: {
                    network: {
                        auto_pick_static: false
                    }
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
                console.log('Mounted & Ready');
                this.getCustomizationSpecs();
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
            resetDeploy() {
                this.newDeploy.token = '';
                this.newDeploy.hostname = '';
                this.newDeploy.ip = null;
                this.token = '';
                this.target = {network: {auto_pick_static: false}};
                this.$refs.deployModal.hide();
            },
            startDeploy(spec) {
                this.target = spec;
                this.$refs.deployModal.show();
            },
            requestSession() {
                let form = {id: this.target.id};
                axios['post']('/api/session/request', form).then(response => {
                    this.newDeploy.token = response.data.token;
                    this.sendDeploy();
                });
            },
            sendDeploy() {
                let form = this.newDeploy;
                axios['post']('/api/session/run', form).then(response => {
                    console.log(response.data);
                    this.newDeploy.token = '';
                    this.newDeploy.hostname = '';
                    this.newDeploy.ip = null;
                    this.target = {network: {auto_pick_static: false}};
                    this.$refs.deployModal.hide();
                    window.open('/session/' + response.data.token, '_blank');
                });
            }
        }
    }
</script>
