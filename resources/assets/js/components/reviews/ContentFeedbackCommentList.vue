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
        <p class="review-feedback-head hidden">Feedback</p>
        <div class="review-feedback-inline">
            <div class="review-inline">
                <blockquote v-for="comment in comments">
                    <p>{{ comment }}</p>
                </blockquote>
            </div>        
        </div>
    </div>
</template>

<script>
    import contentFeedbackCommentList from './contentFeedbackCommentList.vue'

    export default {
        name: 'content-order-comment-list',

        components: {
            contentFeedbackCommentList,
        },

        data() {
            return {
                comments: [],
                message: '',
                info: ''
            }
        },

        created() {
            this.refresh()
        },

        methods: {
            fetchComments() {
                return [];
                // return ['This looks a bit vague. Maybe you can add some more hands-on experiences.'];
                // return $.get('');
            },

            postComment() {
                this.comments.push(this.message);
                // if (!this.message.length) {
                //     this.info = 'Comment can\'t be empty'
                // } else {
                //     // $.post('/api/content/orders/' + this.orderId + '/comments?fresh=true', {comment: this.message})
                //     //     .done(response => {
                //     //         this.refresh()
                //     //         //swal("Done!", response.message, "success")
                //     //     })
                //     //     .fail((xhr, status, error) => {
                //     //         if (xhr.responseJSON.error) {
                //     //             this.info = xhr.responseJSON.error
                //     //         } else if (xhr.responseJSON.fault) {
                //     //             this.info = xhr.responseJSON.fault
                //     //         }
                //     //     }).always(() => {
                //     //     this.sending = false;
                //     // });
                // }
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