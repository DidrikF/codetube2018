<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Upload</div>

                    <div class="panel-body">
                        <input type="file" name="video" id="video" @change="fileInputChange" v-if="!uploading">

                        <div class="alert alert-danger" v-if="failed">Something went wrong. Please try again.</div>

                        <!-- VIDEO UPLOAD FORM -->
                        <div id="video-form" v-if="uploading && !failed">

                            <!-- Is displayed while uploading the video -->
                            <div class="alert alert-info" v-if="!uploadingComplete">
                                Your video will be available at <a :href="$root.url + '/videos/' + uid" target="_blank">{{ $root.url }}/videos/{{ uid }}</a>.
                            </div>

                            <!-- Is displayed when the upload is completed successfully -->
                            <div class="alert alert-success" v-if="uploadingComplete">
                                Upload complete. Video is now processing. <a href="/videos">Go to your videos</a>.
                            </div>


                            <div class="progress" v-if="!uploadingComplete">
                                <div class="progress-bar" v-bind:style="{ width: fileProgress + '%' }"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" v-model="title">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" v-model="description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="visibility">Visibility</label>
                                <select class="form-control" v-model="visibility">
                                    <option value="private">Private</option>
                                    <option value="unlisted">Unlisted</option>
                                    <option value="public">Public</option>
                                </select>
                            </div>
                            
                            <span class="help-block pull-right"> {{ saveStatus }} </span>
                            <button class="btn btn-default" type="submit" @click.prevent="update">Save changes</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../axiosInstance';

    export default {
        //an object to hold data
        data() {
            return {
                uid: null,
                uploading: false,
                uploadingComplete: false,
                failed: false,
                title: 'Untitled',
                description: null,
                visibility: 'private',
                saveStatus: null,
                fileProgress: 0

            }
        },

        //functions
        methods: {
            fileInputChange() {
                this.uploading = true;
                this.failed = false; 

                this.file = document.getElementById('video').files[0]; //get the file data

                //save to database
                this.store().then(() => {
                    var form = new FormData();
                    form.append('video', this.file);
                    //uid passed from the response in the store() method
                    form.append('uid', this.uid);

                    //uploading video, and calling method to update progress bar (at this point we have gotten the uid from the server)
                    axiosInstance.post('/upload', form, {
                        
                        onUploadProgress: function (progressEvent) {
                            // Do whatever you want with the native progress event
                            console.log(progressEvent);
                        },
                        /*
                        progress: (e) => {
                            if(e.lengthComputable) { //What does this do?
                                //console.log(e.loaded + ' ' + e.total);
                                this.updateProgress(e);
                            }
                        }*/
                    }).then(() => {
                        this.uploadingComplete = true;
                    }).catch(() => {
                        //If uploading the video failed: 
                        this.failed = true;
                    });
                }, () => {
                    //If store() method fail:
                    this.failed = true;
                });
                //store the metadata
                

            },

            store() {
                //AJAX request
                return axiosInstance.post('/videos', { //vue resource package (need to require it in app.js)
                    //Data we want to send through:
                    title: this.title,
                    description: this.description,
                    visibility: this.visibility,
                    extension: this.file.name.split('.').pop()

                }).then((response) => {

                    console.log(response.json());
                    this.uid = response.json().data.uid; //this id we can use subsequently to make changes to the uploaded video

                }); 
            },

            update() {
                this.saveStatus = 'Saving changes.';

                return axiosInstance.put('/videos/' + this.uid, {
                    title: this.title,
                    description: this.description,
                    visibility: this.visibility
                }).then((response) =>{
                    this.saveStatus = 'Changes saved.';

                    //After 3 seconds remove the saveStatus message
                    setTimeout(() => {
                        this.saveStatus = null
                    }, 3000)

                }, () => {
                    this.saveStatus = 'Failed to save changes.'; 
                });
            },

            updateProgress (e) {
                e.percent = (e.loaded / e.total) * 100;
                this.fileProgress = e.percent; 
            }

        },

        ready() {
            window.onbeforeunload = () => {
                if (this.uploading && !this.uploadingComplete && !this.failed) {
                    return 'Are you sure you want to navigate away?';

                }
            }
        }
    }
</script>



