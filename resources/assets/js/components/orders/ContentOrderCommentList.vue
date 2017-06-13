<template xmlns="http://www.w3.org/1999/html">
    <div>
        <div class="alert alert-info alert-forms margin-20" v-if="!comments.length && loaded">
            <p><strong>No comments have been posted for this order.</strong></p>
        </div>

        <loading v-if='!loaded'></loading>

        <div class="panel-footer" v-if="loaded">
                <textarea v-model="message" class="form-control input-sm"
                          placeholder="Type your message here..."></textarea>

            <span v-text="info" v-if="info.length" class="text-danger"></span>

            <span class="input-group-btn post-btn">
                            <button class="btn btn-primary btn-sm" @click="postComment"
                                    v-if="!sending">Post</button>
                </span>

            <loading class="pull-right" v-if="sending"></loading>
        </div>

        <content-order-comment
                v-if="loaded"
                v-for="comment in comments"
                :key="comment.id"
                :comment="comment"
        ></content-order-comment>

    </div>
</template>

<script>
    import ContentOrderComment from './ContentOrderComment.vue'

    export default {
        name: 'content-order-comment-list',

        props: ['orderId'],

        components: {
            ContentOrderComment,
        },

        data() {
            return {
                comments: [],
                message: '',
                info: '',
                loaded: false,
                sending: false
            }
        },

        created() {
            this.refresh()
        },

        methods: {
            fetchComments() {
                return $.get('/api/content/orders/' + this.orderId + '/comments');
            },

            postComment() {
                if (!this.message.length) {
                    this.info = 'Comment can\'t be empty'
                } else {
                    this.sending = true;
                    $.post('/api/content/orders/' + this.orderId + '/comments?fresh=true', {comment: this.message})
                        .done(response => {
                            this.refresh()
                            swal("Done!", response.message, "success")
                        })
                        .fail((xhr, status, error) => {
                            if (xhr.responseJSON.error) {
                                this.info = xhr.responseJSON.error
                            } else if (xhr.responseJSON.fault) {
                                this.info = xhr.responseJSON.fault
                            }
                        }).always(() => {
                        this.sending = false;
                    });
                }
            },

            refresh() {
                this.info = ''
                this.message = ''
                this.loaded = false;
                this.fetchComments().then(response => {
                    this.comments = response;
                    this.loaded = true;
                });
            }
        }
    }
</script>

<style scoped>
    .alert {
        margin: 0;
    }

    .post-btn {
        margin-left: 10px !important;
        display: inline-block;
    }

    textarea {
        width: 80%;
        vertical-align: middle;
        display: inline-block;
    }

    img.loading-relative {
        margin: auto;
    }
</style>