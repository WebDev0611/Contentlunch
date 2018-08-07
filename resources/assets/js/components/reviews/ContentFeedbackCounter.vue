<template>
    <div class="dashboard-notification-box">
        <span class="dashboard-notification-box-count">
            <i class="icon-navigation-review"></i>
            <span class="content-feedback-count">{{ contentFeedbackCounter }}</span>
        </span>
        <span>Client Content <br> Feedback</span>
        <span>
            <a href="/content" title="Go to client content feedback page">
                <button class="btn btn-default orders-link" type="button">Go
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </button>
            </a>
        </span>
    </div>
</template>

<script>
    export default {
        name: 'content-feedback-counter',

        data() {
            return {
                contentFeedbackCounter: 50,
            };
        },

        created() {
            this.refresh();
        },

        methods: {
            refresh() {
                // $.get('')
                //     .then(response => this.contentFeedbackCounter = response.count)
                //     .fail(
                //         err => {
                //             console.log(err.responseJSON);
                //             this.contentFeedbackCounter = 0;
                //         }
                //     )
                // ;
            }
        }
    }
</script>