<template>
    <div>
        <div class="content-tasks-box-container">
            <!-- Reviewer Comments -->
            <div class="input-form-group">
                <label for="#">Article review</label>
                <textarea class="input input-area" name=" " rows="4" cols="20" placeholder="Enter your content comments here..." v-model="message"></textarea>
            </div>
        </div>

        <button class="button button-primary btn-block button-small text-uppercase save-idea" @click="postComment">Submit Comments</button>

        <div class="distancer distancer-short"></div>
        <div class="review-feedback">
            <p v-if="hasComments" class="review-feedback-head">Inline Feedback</p>
            <content-feedback-comment
                v-for="comment in comments"
                :key="comment.id"
                :comment="comment"
            ></content-feedback-comment>
        </div>
    </div>
</template>

<script>
    import ContentFeedbackCommentList from './contentFeedbackCommentList.vue'

    export default {
        name: 'content-feedback-comment-list',

        components: {
            ContentFeedbackCommentList,
        },

        props: ['orderid'],

        data() {
            return {
                comments: [],
                message: '',
                info: '',
                hasComments: false
            }
        },

        created() {
            this.refresh();
        },

        methods: {

            fetchComments() {
                let that = this;
                $.ajax({
                    url: '/api/contents/' + that.orderid + '/comments',
                    method: "GET",
                    data: {text: this.message},
                })
                    .done(comments => {
                        let i;
                        for (i = 0; i < comments.length; i++) {
                            if (!that.hasComments) that.hasComments = true;
                            that.comments.unshift(comments[i]);
                        }
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
                return [];
            },

            postComment() {
                let that = this;
                console.log("Testing comments");
                if (!this.message.length) {
                    this.info = 'Comment can\'t be empty'
                } else {
                    $.ajax({
                        url: '/api/contents/' + that.orderid + '/comments',
                        method: "POST",
                        data: {text: this.message},
                    })
                        .done(response => {
                            that.comments.unshift(response);
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
                if (this.comments.length > 0) {
                    $('.btn-rejectcontent').removeAttr('disabled');
                    $('.review-feedback-head').removeClass('hidden');
                }
                // clear message
                this.message = '';
            },

            refresh() {
                this.message = '';
                this.comments = this.fetchComments();
            }
        }
    }
</script>