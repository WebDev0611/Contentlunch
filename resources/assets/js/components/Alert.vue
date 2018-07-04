<template>
    <div class="alert alert-forms" :class='alertType' role="alert">
        <p><slot></slot></p>
    </div>
</template>

<script>
    export default {
        name: 'alert',

        props: ['type'],

        computed: {
            alertType() {
                return [ 'alert-' + this.type ];
            }
        }
    }
</script>