<template>
    <div class="panel-container-options white">
        <div class="row">
            <div class="col-md-2">
                <label class="select-horizontal-label">{{ contentCount }} Content Items</label>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <label class="select-horizontal-label">Show:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="select select-small extend">
                                    <select name="content_stage" id="#">
                                        <option>All Types</option>
                                        <option value="published">Published</option>
                                        <option value="ready_published">Ready to be Published</option>
                                        <option value="written">Being Written</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <label class="select-horizontal-label">By:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="select select-small extend">
                                    <select name="#" id="#">
                                        <option value="#">Any one</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <label class="select-horizontal-label">Campaign:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="select select-small extend">
                                    <select name="#" id="#">
                                        <option value="#">All</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="create-link-dropdown">
                    <a href="#">FILTER CONTENT</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'content-filter',

        props: [ 'contentCount' ],
    }
</script>