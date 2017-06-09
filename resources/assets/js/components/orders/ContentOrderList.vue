<template>
    <div>
        <content-order-item v-for="order in orders"
                            :order="order"
                            :key="order.id"
        ></content-order-item>
    </div>
</template>


<script>
    export default {
        name: 'content-order-list',

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