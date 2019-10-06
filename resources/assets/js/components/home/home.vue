<template>
    <div class="container" v-html="content">
        
    </div>
</template>

<script>
    export default {
        name: "home",
        data() {
            return {
                content:'Loading....'
            }
        },
        mounted(){
            this.load();
        },
        methods: {
            load:function () {
                axios.get('/home').then(response=> {
                    this.content=response.data;
                });
            }
        }
    }
</script>

<style scoped>

</style>
