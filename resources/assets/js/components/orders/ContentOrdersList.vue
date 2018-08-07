<template>
    <div>
        <content-orders-filter :orders-count="orders.length">
        </content-orders-filter>

        <div class="create-panel-container order-container" :class="{'no-padding': filteredOrders.length}">

            <loading v-if='!loaded'></loading>

            <content-order-item v-if="filteredOrders.length"
                                v-for="(order, key) in filteredOrders"
                                :order="order"
                                :key="order.id"
                                :should-show="key < itemsToShow"
            ></content-order-item>

            <div v-if="loaded && !orders.length" class="alert alert-info alert-forms" role="alert">
                <p>No orders at this moment.</p>
            </div>

            <div v-if="loaded && !filteredOrders.length"
                 class="alert alert-info alert-forms"
                 role="alert">
                <p> There are no orders for the current filter setting.</p>
            </div>

        </div>

        <div class="create-panel-table" :class="showMorePanelClass">
            <div class="create-panel-table-cell text-center">
                <a @click="showMore" href="#">{{ showMorePanelText }}</a>
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
                filteredOrders: [],
                showLimit: 10,
                itemsToShow: 10,
                loaded: false
            }
        },

        created() {
            this.loadOrders().then(response => {
                this.orders = this.filteredOrders = response;
                this.loaded = true;
            });

            this.$on('changeFilter', filter => {
                this.filteredOrders = this.orders.filter(order =>
                    filter === 'all' || order.status === filter
                );
            });
        },

        computed: {
            showMorePanelClass() {
                return (this.filteredOrders.length <= this.showLimit) ? 'hide' : ''
            },

            showMorePanelText() {
                return this.itemsToShow > this.showLimit ? 'Show Less'
                    : (this.filteredOrders.length - this.showLimit) + " More - Show All"
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