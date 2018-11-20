<template>
    <div style="margin-top: 20px; margin-bottom: 20px; margin-right: 10px; margin-left: 10px;" class="card">
        <div class="card-header" style="width: 100%; margin: 0px auto; padding-top: 10px; padding-bottom: 0px;">
            <div style="margin-left: 10px; margin-right: 10px; color: #1d68a7;" class="center-div">
                <h4>
                    <i :class="icon"></i> {{name}}
                    <font-awesome-icon v-if="!done" :icon="['fas', 'circle-notch']" spin/>
                    <font-awesome-icon v-else :icon="['fas', 'check-circle']"/>
                </h4>
            </div>
        </div>
        <div id="terminal"
             style="width: 100%; margin: 0px auto; padding-top: 0px;background-color: #343a40;"
             class="card-body fixed-panel" v-chat-scroll>
            <p v-for="line in consoleLog" :style="line.style">{{line.text}}</p>
        </div>
    </div>
</template>

<script>
    import VueSocketIO from 'vue-socket.io'

    Vue.use(new VueSocketIO({connection: $("#ws").val() + '/' + $("#session-token").val()}));

    export default {
        name: "home",
        sockets: {
            connect: function () {
                console.log('socket connected')
            },
            terminal: function (data) {
                this.consoleLog.push({text: data, style: 'margin-left: 10px; margin-right: 10px; color: #c7eed8;'});
            },
            terminalErr: function (data) {
                this.consoleLog.push({text: data, style: 'margin-left: 10px; margin-right: 10px; color: #f2a29f;'});
            },
            terminalDone: function (data) {
                this.done = true;
            }
        },
        data() {
            return {
                token: '',
                name: '',
                icon: '',
                loading: true,
                done: false,
                consoleLog: [],
                consoleErr: [],
                spec: {}
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
                this.token = $("#session-token").val();
                console.log('Mounted & Ready');
                console.log('Session token: ' + this.token);
                console.log('WS URL: ' + $("#ws").val());
                this.getCustomizationSpec();
            },
            getCustomizationSpec() {
                this.loading = true;
                axios.get('/api/spec/session/' + this.token)
                    .then(response => {
                        this.spec = response.data;
                        this.name = response.data.vm_template.operating_system.name;
                        this.icon = response.data.vm_template.operating_system.logo;
                        this.loading = false;
                        this.$emit('pulled-customization-spec');
                    });
            }
        }
    }
</script>
