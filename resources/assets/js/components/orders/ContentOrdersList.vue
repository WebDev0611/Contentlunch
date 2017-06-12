<template>
    <div>
        <loading v-if='!loaded'></loading>

        <content-order-item v-if="orders.length"
                            v-for="order in orders"
                            :order="order"
                            :key="order.id"
        ></content-order-item>

        <div v-if="!orders.length" class="alert alert-info alert-forms" role="alert">
            <p>No orders at this moment.</p>
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
                loaded: false
            }
        },

        created() {
            this.loadOrders().then(response => {
                this.orders = response;
                this.loaded = true;
            });
        },

        methods: {
            loadOrders() {
                return $.get('/api/content/orders/');
            }
        }
    }
</script>