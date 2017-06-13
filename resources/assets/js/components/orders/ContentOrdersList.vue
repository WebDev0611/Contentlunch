<template>
    <div>
        <content-orders-filter :orders-count="orders.length">
        </content-orders-filter>

        <div class="create-panel-container order-container"
             :class="orders.length ? 'no-padding' : ''">

            <loading v-if='!loaded'></loading>

            <content-order-item v-if="orders.length"
                                v-for="order in orders"
                                :order="order"
                                :key="order.id"
                                :should-show="shouldShow(order)"
            ></content-order-item>

            <div v-if="loaded && !orders.length" class="alert alert-info alert-forms" role="alert">
                <p>No orders at this moment.</p>
            </div>

            <div v-if="loaded && orders.length && itemsPassingFilter === 0"
                 class="alert alert-info alert-forms"
                 role="alert">
                <p> There are no orders for the current filter setting.</p>
            </div>

        </div>

        <!--<div class="create-panel-table" :class="showMorePanelClass">
            <div class="create-panel-table-cell text-center">
                <a @click="showMore" href="#">{{ showMorePanelText }}</a>
            </div>
        </div>-->

    </div>
</template>

<script>
    import Loading from '../Loading.vue';

    export default {
        name: 'content-orders-list',

        data() {
            return {
                orders: [],
                showLimit: 10,
                itemsToShow: 10,
                itemsPassingFilter: 0,
                filter: 'all',
                loaded: false
            }
        },

        created() {
            this.loadOrders().then(response => {
                this.orders = response;
                this.loaded = true;
                this.itemsPassingFilter = this.orders.length
            });

            this.$on('changeFilter', filter => {
                this.filter = filter
                this.itemsPassingFilter = this.orders.filter(order =>
                    this.filter === 'all' || order.status === this.filter
                ).length;
            });
        },

        computed: {
            showMorePanelClass() {
                return (this.orders.length <= this.showLimit || this.itemsPassingFilter <= this.showLimit) ? 'hide' : ''
            },

            showMorePanelText() {
                return this.itemsToShow > this.showLimit ? 'Show Less' : (this.orders.length - this.showLimit) + " More - Show All"
            }
        },

        methods: {
            loadOrders() {
                return $.get('/api/content/orders/');
            },

            showMore() {
                this.itemsToShow = (this.itemsToShow > this.showLimit) ? this.showLimit : this.orders.length;
            },

            shouldShow(order) {
                return this.filter === 'all' || order.status === this.filter
            }
        }
    }
</script>