<template>
    <div class="dashboard-notification-box" :class="{'notify' : contentOrdersToReviewCount > 0}">
        <span class="dashboard-notification-box-count">
                <i class="icon-itemlist"></i>
                <span id="content-orders">{{ contentOrdersToReviewCount }}</span>
            </span>
        <span>Content Orders <br> to Review</span>
        <span>
            <a href="/content/orders" title="Go to content orders page">
                <button class="btn btn-default orders-link" type="button">Go
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </button>
            </a>
        </span>
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
                $.get('/api/content/orders-count', {'pending-approval': true})
                    .then(response => this.contentOrdersToReviewCount = response.count)
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

<style scoped>
    .orders-link span {
        color: #b0acb7;
    }
</style>