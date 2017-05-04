<template>
    <div class="dashboard-notification-box">
        <span class="dashboard-notification-box-count">
                <i class="icon-checklist"></i>
                <span id="content-orders">{{ contentOrdersToReviewCount }}</span>
            </span>
        <span>Content Orders to Review</span>
    </div>
</template>

<script>
    export default {
        name: 'content-orders-counter',

        data() {
            return {
                contentOrdersToReviewCount: 0,
            };
        },

        created() {
            this.refresh();
        },

        methods: {
            refresh() {
                $.get('/api/contents/orders-count', {'pending-approval': true})
                    .then(response => this.contentOrdersToReviewCount = response.data.count)
                    .fail(
                        err => {
                            console.log(err.responseJSON);
                            this.contentOrdersToReviewCount = 0;
                        }
                    )
                ;
            }
        }
    }
</script>
