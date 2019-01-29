<template>
    <div>
        <vodal :show="is_modal_visible" animation="zoom" @hide="closeModal">
            <div>
                <div class="form-group col-md-12">
                    <div class=" col-md-3">
                        <img :src="auth_user.avatar" alt="" class="profile-photo-md "/>
                    </div>

                    <div class="col-md-9">
                        <textarea name="texts" cols="30" rows="3" class="form-control" v-model="share_text"
                                  placeholder="Say Something About this ... "></textarea>
                    </div>
                </div>
                <div class="col-md-12" style="padding: 8% 10%;">
                    <div class="post-container">
                        <img :src="shared_post.profile_pic" alt="user" class="profile-photo-md pull-left"/>
                        <div class="post-detail">
                            <div class="user-info">
                                <h5><a :href="shared_post.user_link" class="profile-link">{{shared_post.username}}</a>
                                    <!--<span class="following">following</span>-->
                                </h5>
                                <p class="text-muted">Published {{shared_post.diff_created_at}} </p>
                            </div>
                            <div class="line-divider"></div>
                            <div class="post-text">
                                <p>
                                    {{shared_post.body}}
                                    <i class="em em-anguished"></i>
                                    <i class="em em-anguished"></i>
                                    <i class="em em-anguished"></i>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-12">
                    <button class="btn btn-primary" @click="postShare(shared_post)">Share</button>
                </div>
            </div>
        </vodal>
        <div v-if="!is_my_profile" class="create-post">
            <div class="row">
                <div class="form-group col-md-12">
                    <img :src="auth_user.avatar" alt="" class="profile-photo-md"/>
                    <textarea name="texts" id="exampleTextarea" cols="30" rows="1" class="form-control"
                              v-on:keyup.enter="publishPost" v-model="post_text"
                              placeholder="Write what you wish"></textarea>
                </div>
                <div class="tools col-md-12 ">
                    <ul style="margin-left: 70px;" class="publishing-tools list-inline">
                        <li><a href="#"><i class="ion-compose"></i></a></li>
                        <li><a href="javascript:;" @click="showImageUploader"><i
                                :style="(image_uploading_selected) ? 'color: #27aae1;':''" class="ion-images"></i></a>
                        </li>
                        <li><a href="javascript:;" @click="showVideoUploader"><i
                                :style="(video_uploading_selected) ? 'color: #27aae1;':''" class="ion-ios-videocam"></i></a>
                        </li>
                    </ul>
                    <button class="btn btn-primary pull-right" @click="publishPost">
                        <i v-if="publish_loading" class="upload-spinn fa fa-cog fa-spin fa-1x fa-fw"></i>
                        Publish
                    </button>
                </div>
                <div v-if="image_uploading_selected" style="display: inline-block;padding-left: 70px;">
                    <vue-upload-multiple-image @upload-success="uploadImageSuccess" @before-remove="beforeRemove"
                                               @edit-image="editImage" @data-change="dataChange"
                                               :data-images="images"></vue-upload-multiple-image>
                </div>
                <div v-if="video_uploading_selected" style="display: inline-block;padding-left: 70px;">
                    <vue-dropzone style="height: auto" ref="dropzone" :thumbnailHeight="120" :thumbnailWidth="120"
                                  @vdropzone-success="afterFileUploadSuccess"
                                  @vdropzone-sending="whileSending" :max-number-of-files='5'
                                  :paramName="'video'" :url="'/video/upload'"
                                  @vdropzone-complete="afterUploadAllFiles" @vdropzone-processing="onUploadProgress"
                                  :use-custom-dropzone-options=true id="drop1" :dropzone-options="this.dropOptions">
                    </vue-dropzone>
                    <!--:url="'/video/upload'" :use-custom-dropzone-options=true-->
                    <!--<vue-dropzone :ref="vueDropzone" id="drop1"  :options="dropOptions"></vue-dropzone>-->

                </div>


            </div>
        </div>


        <!--<post v-for="post in posts" :key="post.id" v-bind:data="{post: post, auth_user : auth_user}"></post>-->
        <div v-for="(post , index) in posts" :key="post.id" class="post-content">
            <div v-if="post.images.length > 0" class="row" style="padding: 30px 20px;">
                <div v-for="(image ,index) in post.images" :class="index === 0 ? 'col-md-12': 'col-sm-4'" style="margin-top: 15px;">
                    <a :href="image.link" target="_blank">
                        <img :src="((index === 0) ? '/image/' : '/image/300x200/')+image.file_name " alt="post-image" class="img-responsive post-image"/>
                    </a>
                </div>
            </div>
            <div v-if="post.videos.length > 0" class="row" style="padding: 30px 20px;">
                <div v-for="(video , index) in post.videos" :class="index === 0 ? 'col-md-12': 'col-sm-4'">
                    <div class="video-wrapper">
                        <video class="post-video" controls>
                            <source :src="'/video/'+video.file_name" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
            <div class="post-container">
                <img :src="post.profile_pic" alt="user" class="profile-photo-md pull-left"/>
                <div class="post-detail">
                    <div class="user-info">
                        <h5><a :href="post.user_link" class="profile-link">{{post.username}}</a>
                            <span class="following">following</span>
                        </h5>
                        <p class="text-muted">Published {{post.diff_created_at}} </p>
                    </div>
                    <div class="reaction">
                        <a class="btn text-primary" data-toggle="tooltip" @click="postLike(post,index)"
                           :title="getPeopleLikes(post.likes)">
                            <i class="icon ion-thumbsup"></i> {{post.likes.length}}
                        </a>
                        <a class="btn text-green" @click="showShareModal(post,index)">
                            <i class="fa fa-share"></i> {{post.shares_count}}
                        </a>
                        <a class="btn text-red" :href="post.share_link" >
                            <i class="fa fa-facebook"></i>
                        </a>
                    </div>
                    <div class="line-divider"></div>
                    <div class="post-text">
                        <p>
                            {{post.body}}
                            <i class="em em-anguished"></i>
                            <i class="em em-anguished"></i>
                            <i class="em em-anguished"></i>
                        </p>
                    </div>
                    <div v-if="post.has_share" class="col-md-12" style="border-radius: 25px;background-color: white;">
                        <div class="post-container">
                            <img :src="post.shared_post.profile_pic" alt="user" class="profile-photo-md pull-left"/>
                            <div class="post-detail">
                                <div class="user-info">
                                    <h5><a :href="post.shared_post.user_link" class="profile-link">{{post.shared_post.username}}</a>
                                    </h5>
                                    <p class="text-muted">Published {{post.shared_post.diff_created_at}} </p>
                                </div>
                                <div class="line-divider"></div>
                                <div class="post-text">
                                    <p>
                                        {{post.shared_post.body}}
                                        <i class="em em-anguished"></i>
                                        <i class="em em-anguished"></i>
                                        <i class="em em-anguished"></i>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="line-divider"></div>
                    <div v-for="comment in post.comments" :key="comment.id" class="post-comment">
                        <img :src="comment.profile_pic" alt="" class="profile-photo-sm"/>
                        <p>
                            <a :href="comment.user_link" class="profile-link">{{comment.username}} </a>
                            <i class="em em-laughing"></i>
                            {{comment.body}}
                        </p>
                        <!--<p>{{comment.created_at}}</p>-->
                    </div>
                    <div class="post-comment">
                        <img :src="auth_user.avatar" alt="" class="profile-photo-sm"/>
                        <input @keyup.enter="postComment(post)" v-model="post.comment_text" type="text"
                               class="form-control" placeholder="Post a comment">
                    </div>
                </div>
            </div>
        </div>

        <div v-if="is_posts_loading" class="row" style="position: absolute;left: 45%;top: 300px;">
            <div class="showbox">
                <div class="loader">
                    <svg class="circular" viewBox="25 25 50 50">
                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                stroke-miterlimit="10"/>
                    </svg>
                </div>
            </div>
        </div>
        <div v-if="is_no_posts">
            <h3 style="text-align: center; margin-top: 15%; font-size: 18px;">No Posts</h3>
        </div>

    </div>
</template>
<style scoped>
    @import "vodal/common.css";
    @import "vodal/zoom.css";

    .dropzone {
        width: 495px;
    }
</style>
<script>
    var _this;
    export default {
        props: ['data'],
        name: "feed",
        http: {
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        data() {
            return {
                dropOptions: {
                    url: '/video/upload',
                    maxFilesize: 100, // MB
                    maxFiles: 8,
                    paramName: 'video',
                    thumbnailHeight: 120,
                    thumbnailWidth: 120,
                    acceptedFiles: ".avi,.wmv,.mp4,.mov,.mpg,.flv,.mpeg",
                    addRemoveLinks: true,
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),}
                },
                is_all_videos_uploaded: false,
                publish_loading: false,
                video_uploading_selected: false,
                image_uploading_selected: false,
                current_index: 0,
                token: '',
                is_modal_visible: false,
                is_no_posts: false,
                is_posts_loading: false,
                is_my_profile: true,
                auth_user: {},
                videos: [],
                images: [],
                posts: [],
                post_text: '',
                comment_text: '',
                share_text: '',
                shared_post: {},
                page: 1,
                posts_type: 'feeds',
                user_id: 0,
            }
        },
        created() {
            console.log();
            _this = this;
            _this.token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            _this.auth_user = _this.data;
            _this.posts_type = _this.data.posts_type;
            _this.user_id = _this.data.user_id;
            _this.is_my_profile = _this.data.is_my_profile;
            _this.fetchPosts();
        },
        mounted() {

        },
        methods: {
            showVideoUploader() {
                _this.image_uploading_selected = false;
                _this.video_uploading_selected = true;
            },
            showImageUploader() {
                _this.image_uploading_selected = true;
                _this.video_uploading_selected = false;
            },
            closeModal() {
                _this.is_modal_visible = false;
            },
            showShareModal(post, index) {
                _this.shared_post = post;
                _this.is_modal_visible = true;
                _this.current_index = index;
            },
            postShare(post, index) {
                _this.axios.post('/feeds/posts/' + post.id + '/share', {
                    body: _this.share_text,
                }).then(response => {
                    if (response.data.status) {
                        _this.posts[_this.current_index].shares_count = response.data.shares_count;
                        _this.posts.unshift(response.data.item);
                        _this.share_text = '';
                    }
                    _this.is_modal_visible = false;
                }).catch(e => {
                    this.errors.push(e);
                    _this.is_modal_visible = false;
                });
            },
            postLike(post, index) {
                _this.axios.post('/feeds/posts/' + post.id + '/like', {
                    id: post.id,
                }).then(response => {
                    if (response.data.status) {
                        _this.posts[index].likes = response.data.likes;
                    }
                }).catch(e => {
                    this.errors.push(e)
                });
            },
            getPeopleLikes(likes) {
                var text = '';
                likes.map(function (value, key) {
                    text += value.f_name + ' ' + value.l_name;
                });
            },
            publishPost() {
                _this.publish_loading = true;
                _this.axios.post('/feeds/posts', {
                    body: _this.post_text,
                    images: _this.images,
                    videos: _this.videos,
                }).then(response => {
                    if (response.data.status) {
                        _this.posts.unshift(response.data.item);
                        _this.post_text = '';
                        _this.images = [];
                        _this.image_uploading_selected = false;
                        _this.video_uploading_selected = false;
                        _this.publish_loading = false;
                    }
                }).catch(e => {
                    this.errors.push(e);
                    _this.publish_loading = false;
                });
            },
            postComment(post) {
                _this.axios.post('/feeds/comment', {
                    id: post.id,
                    body: post.comment_text,
                }).then(response => {
                    if (response.data.status) {
                        post.comments.push(response.data.item);
                        post.comment_text = '';
                    }
                }).catch(e => {
                    this.errors.push(e)
                });
            },
            fetchPosts() {
                _this.is_posts_loading = true;
                _this.axios.get('/feeds/posts/' + _this.posts_type + '/' + _this.user_id + '?page=' + _this.page).then((response) => {
                    _this.is_posts_loading = false;
                    response.data.data.map(function (post, key) {
                        var comments = [];
                        post.comments.map(function (comment, key) {
                            comments.push(comment);
                        });
                        post.comments = comments;
                        _this.posts.push(post);
                    });
                    _this.is_no_posts = _this.posts.length === 0;
                    _this.page = response.data.current_page++;
                }).catch((response) => {
                    _this.is_no_posts = _this.posts.length === 0;
                    _this.is_posts_loading = false;
                });
            },
            removeAllFiles() {
                _this.$refs.dropzone.removeAllFiles();
                _this.videos = [];
            },
            onUploadProgress() {
                _this.publish_loading = true;
                _this.is_all_videos_uploaded = false;
            },
            afterFileUploadSuccess(file, response) {
                _this.videos.push({
                    id: response.id,
                    display_name: file.name,
                    file_name: response.file_name,
                    size: file.size,
                    mime_type: ''
                });
            },
            afterUploadAllFiles(file) {
                if (this.$refs.dropzone.getUploadingFiles().length === 0 && this.$refs.dropzone.getQueuedFiles().length === 0) {
                    this.is_all_videos_uploaded = true;
                    _this.publish_loading = false;
                }
            },
            uploadImageSuccess(e, f, g) {
                _this.images = g;
            },
        }
    }
</script>
