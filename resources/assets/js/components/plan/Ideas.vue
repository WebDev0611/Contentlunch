<template>
    <div class="panel-main">
        <div class="panel-header">
            <ul class="panel-tabs text-center">
                <li>
                    <a href="/plan">Topic Generator</a>
                </li>
                <li>
                    <a href="/plan/trends">Content Trends</a>
                </li>
                <li>
                    <a href="/plan/prescription">Content Prescription</a>
                </li>
                <li class="active">
                    <a href="#active-ideas" data-toggle='tab'>Active Ideas</a>
                </li>
                <li>
                    <a href="#parked-ideas" data-toggle='tab'>Parked Ideas</a>
                </li>
            </ul>
        </div>

        <freemium-alert
            restriction='create only ten ideas per month'>
        </freemium-alert>

        <div class="tab-content">
            <div class="tab-pane active" id="active-ideas">
                <input type="text" class="plan-panel-search" placeholder="Quick Search">
                <ideas-list status='active' @socialize='showModal = true'>
                </ideas-list>
            </div>
            <div class="tab-pane" id="parked-ideas">
                <input type="text" class="plan-panel-search" placeholder="Quick Search">
                <ideas-list status='parked'></ideas-list>
            </div>
        </div>

        <ideas-collaborators></ideas-collaborators>
    </div>
</template>

<script>
    import IdeasList from './IdeasList.vue';
    import IdeasCollaborators from './IdeasCollaborators.vue';
    import bus from '../bus.js';

    export default {
        name: 'ideas',

        components: {
            IdeasList,
            IdeasCollaborators,
        },

        created() {
            this.$store.dispatch('fetchIdeas');
        },
    }
</script>