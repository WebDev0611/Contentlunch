<template>
    <div>
        <div class="alert alert-info" role="alert" v-if="!comments.length && loaded">
            No comments for this order yet.
        </div>

        <h3 v-if="comments.length" class="order-title">{{comments[0].order_title}}</h3>

        <loading v-if='!loaded'></loading>

        <content-order-comment
                v-if="loaded"
                v-for="comment in comments"
                :key="comment.id"
                :comment="comment"
        ></content-order-comment>

        <div class="order-response" v-if="loaded">
            <div class="col-md-8 col-md-offset-2">
                <h4>Write a comment...</h4>

                <textarea class="form-control" rows="6" v-model="message"></textarea>
                <span v-text="info" v-if="info.length" class="text-danger"></span>

                <button type="button" class="btn btn-primary pull-right" @click="postComment" v-if="!sending">
                    Send
                </button>

                <loading class="pull-right" v-if="sending"></loading>

            </div>
        </div>

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
    h3.order-title {
        margin-bottom: 40px;
        padding-left: 15px;
        font-weight: 600;
    }

    .alert {
        margin: 0;
    }

    .order-response {
        border-top: 1px solid #dadfeb;
        padding: 20px;
    }

    .order-response .btn {
        margin-top: 10px;
    }
</style>