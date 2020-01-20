<template>
    <div class="container-fluid pt-5 pb-5">
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "App",

        props: [
            'user',
        ],

        created() {
            window.axios.interceptors.request.use(
                (config) => {
                    if (config.method === 'get') {
                        config.url = config.url + '?api_token=' + this.user.api_token;
                    } else {
                        config.data = {
                            ...config.data,
                            api_token: this.user.api_token
                        };
                    }

                    return config;
                }

            )
        }
    }
</script>

<style>

</style>