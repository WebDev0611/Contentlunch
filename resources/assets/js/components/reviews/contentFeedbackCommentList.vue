<template>
    <div>
        <div>
            <div class="content-tasks-box-container">
                <!-- Reviewer Comments -->
                <div class="input-form-group">
                    <label for="#">Article review</label>
                    <textarea class="input input-area" name=" " rows="4" cols="20"
                              placeholder="Enter your content comments here..." v-model="message"></textarea>
                </div>
            </div>
            <button class="button button-primary btn-block button-small text-uppercase save-idea" @click="postComment">
                Submit Comments
            </button>

            <div class="distancer distancer-short"></div>
            <p class="review-feedback-head hidden">Feedback</p>
            <div class="review-feedback-inline">
                <div class="review-inline">
                    <content-feedback-comment
                            v-for="comment in comments"
                            :key="comment.id"
                            :comment="comment"
                    ></content-feedback-comment>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-approve" tabindex="-1" role="dialog" aria-labelledby="Review Content">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">REVIEW CONTENT</h4>
                    </div>
                    <div class="modal-body">
                        <div class="inner text-center">
                            <p class="medium color-green"><strong>You decided to approve the content.</strong></p>
                            <p>Please review your feedback and submit. It will be soon visible to AUTHOR.</p>
                        </div>

                        <div class="distancer distancer-short"></div>

                        <div class="inner wide">
                            <div class="input-form-group nobottommargin">
                                <label for="#">Your overall feedback</label>
                                <textarea class="input input-area input-area-review" name=" " rows="6" cols="20"
                                          placeholder="Enter your overall content comments here..."></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer centered widebuttons">
                        <button type="button" class="button button-outline-secondary" data-dismiss="modal">CANCEL
                        </button>
                        <button type="button" class="button button-green button-withlasticon" data-dismiss="modal"
                                data-toggle="modal" data-target="#modal-confirmation-approve">APPROVE<i
                                class="icon icon-check-light"></i></button>
                    </div>
                </div>
            </div>
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

        data() {
            return {
                comments: [],
                message: '',
                info: ''
            }
        },

        created() {
            this.refresh();
        },

        methods: {

            fetchComments() {
                let that = this;
                $.ajax({
                    url: '/api/contents/' + window.orderId + '/comments',
                    method: "GET",
                    data: {text: this.message},
                })
                    .done(comments => {
                        let i;
                        for (i = 0; i < comments.length; i++) {
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
                        url: '/api/contents/' + window.orderId + '/comments',
                        method: "POST",
                        data: {text: this.message},
                    })
                        .done(response => {
                            that.comments.unshift(response.comment.text + " ~ " + response.user.name);
                            console.log(that.comments);
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
                // this.fetchComments().then(response => {
                //     this.comments = response;
                // });
            }
        }
    }
</script>