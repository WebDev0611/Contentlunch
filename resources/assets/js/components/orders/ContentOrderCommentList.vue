<template>
    <div>

        <loading v-if='!loaded'></loading>

        <div class="alert alert-info" role="alert" v-if="!comments.length && loaded">
            No comments for this order yet.
        </div>

        <h3 v-if="comments.length" class="order-title">{{comments[0].order_title}}</h3>

        <content-order-comment
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
            this.fetchComments().then(response => {
                this.comments = response;
                this.loaded = true;
            });
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
                    $.post('/api/content/orders/' + this.orderId + '/comments', {comment: this.message})
                        .done(response => {
                            swal("Done!", response.message, "success")
                            this.info = ''
                            this.message = ''
                        })
                        .fail((xhr, status, error) => {
                            this.info = xhr.responseJSON.message
                        }).always(() => {
                        this.sending = false;
                    });
                }
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