<template>
    <div class="wrapper">
        <h2>Upload image: </h2>
        <vue-dropzone id="upload" :options="config" @vdropzone-complete="afterComplete"></vue-dropzone>
        <br>
        <div v-if="seen">
            <form method="post" action="/photo/watermark">
                <input type="hidden" name="filename" :value="photo_name">
                <div class="field">
                    <div class="control">
                        <label>Do you want watermark ? (image or text)</label>
                    </div>
                </div>
                <div class="control">
                    <label class="radio">
                        <input type="radio" v-model="watermark" name="watermark" value="0">
                        Photo
                    </label>
                    <label class="radio">
                        <input type="radio" v-model="watermark" name="watermark" value="1">
                        Text
                    </label>
                </div>

                <div v-if="watermark == 0" class="field">
                    <div class="control">
                        <div class="file">
                            <label class="file-label">
                                <input class="file-input" type="file" name="resume">
                                <span class="file-cta">
                                  <span class="file-icon">
                                    <i class="fas fa-upload"></i>
                                  </span>
                                  <span class="file-label">
                                    Choose a file…
                                  </span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div v-else class="field">
                    <div class="control">
                        <input class="input column is-8" type="text" placeholder="Enter text watermark or send second file">
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <label class="checkbox">
                            <input name="crop" value="false" v-model="smart_crop" type="checkbox">
                            Crop photo ? (smart crop)
                        </label>
                    </div>
                </div>
                <div v-if="smart_crop" class="field">
                    <div class="control field-body">
                        <input type="text" class="input column is-2" placeholder="width"> <span> x </span>
                        <input type="text" class="input column is-2" placeholder="height">
                    </div>
                </div>

                <button class="button is-link" type="submit">Add watermark</button>
            </form>
        </div>
    </div>
</template>

<script>

    import vueDropzone from "vue2-dropzone";
    export default {

        name: "FileUploadComponent",
        data: () => ({
            config: {
                url: "/photo/upload",
                maxFilesize: 5,
                maxFiles: 2,
            },
            seen: true,
            watermark: "0",
            smart_crop: false,
            photo_name: ''
        }),
        components: {
            vueDropzone
        },
        methods: {
            afterComplete(file) {
                let resp = file.xhr.response;
                let result = JSON.parse(resp)
                if(result.success == true) {
                    this.seen = true;
                    this.photo_name = result.photo;
                }

            }
        }
    }
</script>

<style scoped>
    @import "~vue2-dropzone/dist/vue2Dropzone.css";
</style>