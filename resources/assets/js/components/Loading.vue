<template>
    <img
        src="/images/ring.gif"
        class='loading-relative'
        :class='loadingClass'
        style='max-height:30px'
        alt="" />
</template>

<script>
    export default {
        name: 'loading',

        props: [ 'size' ],

        computed: {
            loadingClass() {
                return {
                    'loading-small': this.size == 'small',
                };
            },
        }
    }
</script>