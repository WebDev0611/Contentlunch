<template>
    <div class="alert alert-info alert-forms freemium-notification alert-dismissable" v-show='accountPlan'>

        <h4>You are using the {{ accountPlan }} of ContentLaunch</h4>

        <p>
            You can {{ restrictionMessage }} during this period.
            Switch to a paid account to remove this and other limitations.
        </p>

        <a href="/subscription">
            <button class="btn btn-upgrade">Upgrade now</button>
        </a>
    </div>
</template>

<script>
    export default {
        name: 'freemium-alert',

        props: ['type'],

        computed: {
            restrictionMessage() {
                return this.restriction || 'try out all features of the app, but some functionality is limited';
            },

            accountPlan() {
                switch (this.$store.state.subscriptionType) {
                    case 'trial': return '2 week free trial';
                    case 'free': return 'free version';
                    default: return null;
                }
            }
        }
    }
</script>