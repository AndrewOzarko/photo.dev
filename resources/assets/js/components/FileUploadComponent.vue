<template>
    <vue-dropzone id="upload" :options="config"></vue-dropzone>
</template>

<script>
    import vueDropzone from "vue2-dropzone";
    export default {

        name: "FileUploadComponent",
        data: () => ({
            config: {
                url: "https://localhost:8000"
            }
        }),
        components: {
            vueDropzone
        }
    }
</script>

<style scoped>
    @import "~vue2-dropzone/dist/vue2Dropzone.css";
</style>