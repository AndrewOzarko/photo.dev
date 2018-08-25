<template>
    <div class="wrapper">
        <h2>Upload image: </h2>
        <vue-dropzone id="upload" :options="config" v-on:vdropzone-error="failed" @vdropzone-complete="afterComplete"></vue-dropzone>
        <br>
        <div v-if="seen">
            <form enctype='multipart/form-data' id="uploadForm">
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
                    <div class="control" id="photofile">
                        <div class="file">
                            <label class="file-label">
                                <input class="file-input" id="file" type="file" name="w-image">
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
                        <input id="waterText" v-model="waterText" class="input column is-8" type="text" placeholder="Enter text watermark or send second file">
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
                        <input v-model="crop_width" type="text" class="input column is-2" placeholder="width"> <span> x </span>
                        <input v-model="crop_height" type="text" class="input column is-2" placeholder="height">
                    </div>
                    <div id="crop_width"></div>
                    <div id="crop_height"></div>
                </div>

                <button class="button is-link" @click="sendWaterMark">Edit photo</button>
                <button class="button is-link" @click="downloadPhoto">Download photo</button>
            </form>

            <div v-if="is_success == true">
                Photo successfull editing
            </div>
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
                maxFiles: 1,
            },
            seen: false,
            is_success: false,
            watermark: "0",
            smart_crop: false,
            photo_name: '',
            waterText: '',
            crop_width: 0,
            crop_height: 0,
        }),
        components: {
            vueDropzone
        },
        methods: {
            afterComplete(file) {
                let resp = file.xhr.response;
                let result = JSON.parse(resp);
                if(result.success == true) {
                    this.seen = true;
                    this.photo_name = result.photo;
                }

            },

            failed(file,message,xhr){
                let response = xhr.response;
                let parse = JSON.parse(response, (key, value)=>{
                    return value;
                });
                console.log(response);
                document.querySelector(".dz-error-message span").innerHTML = parse.errors['file'];
            },
            sendWaterMark(e) {
                e.preventDefault();
                let data = new FormData();
                console.log(this.photo_name);

                if(this.watermark == 0) {
                    data.append('photofile', document.getElementById('file').files[0]);
                } else if (this.watermark ==  1) {
                    data.append('waterText', this.waterText);
                }

                if(this.smart_crop == true) {
                    data.append('crop', this.smart_crop);
                    data.append('crop_width', this.crop_width);
                    data.append('crop_height', this.crop_height);
                }

                data.append('imagename', this.photo_name);
                data.append('watermarkType', this.watermark);
                let t = this;
                axios.post('/photo/watermark', data)
                    .then(function (response) {
                        let data = response.data;

                        if(data.success == true) {
                            t.is_success = true;
                            let errorMessages = document.querySelectorAll('.text-danger');

                            errorMessages.forEach(function(element) {
                                element.remove();
                            });
                        }
                    })
                    .catch(function (error) {
                        let errors = error.response.data.errors;
                        let firstItem = Object.keys(errors)[0];
                        let firstItemDOM = document.getElementById(firstItem);
                        let firstErrorMessage = errors[firstItem][0];

                        let errorMessages = document.querySelectorAll('.text-danger');

                        errorMessages.forEach(function(element) {
                            element.remove();
                        });

                        firstItemDOM.insertAdjacentHTML('afterend', '<div class="text-danger">' + firstErrorMessage + '</div>');

                        console.log(firstItem);
                    });
            },
            downloadPhoto(e) {
                e.preventDefault();
                window.open("/storage/photo/" + this.photo_name, '_blank');
            }

        }
    }
</script>

<style scoped>
    @import "~vue2-dropzone/dist/vue2Dropzone.css";
</style>