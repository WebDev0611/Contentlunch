<template>
    <div>
        <content-orders-filter :orders-count="orders.length">
        </content-orders-filter>

        <div class="create-panel-container order-container hide-over-10"
             :class="orders.length ? 'no-padding' : ''">

            <loading v-if='!loaded'></loading>

            <content-order-item v-if="orders.length"
                                v-for="(order, key) in orders"
                                :order="order"
                                :key="order.id"
                                :index="key"
                                :items-to-show="itemsToShow"
                                :filter="filter"
            ></content-order-item>

            <div v-if="!orders.length" class="alert alert-info alert-forms" role="alert">
                <p>No orders at this moment.</p>
            </div>

            <div class="alert alert-info alert-forms no-orders-message" role="alert"><p>
                There are no orders for the current filter setting.</p></div>

            <div class="create-panel-table" :class="orders.length <= showLimit ? 'hide' : ''">
                <div class="create-panel-table-cell text-center">
                    <a @click="showMore" href="#">{{ showMorePanelText }}</a>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import Loading from '../Loading.vue';

    export default {
        name: 'content-orders-list',

        data() {
            return {
                orders: [],
                showLimit: 3,
                itemsToShow: 3,
                itemsPassingFilter: 3,
                filter: 'all',
                loaded: false
            }
        },

        created() {
            this.loadOrders().then(response => {
                this.orders = response;
                this.loaded = true;
            });

            this.$on('changeFilter', filter => {
                this.filter = filter
                this.itemsPassingFilter = this.orders.filter(order =>
                    this.filter === 'all' || order.status === this.filter
                ).length;
            });
        },

        computed: {
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
            }
        }
    }
</script>