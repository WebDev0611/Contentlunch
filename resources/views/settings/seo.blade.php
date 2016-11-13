@extends('layouts.master')

@section('content')

<div class="workspace">
    <div class="panel clearfix">
        @include('settings.partials.profile_sidebar')
        <div class="panel-main left-separator">
            <div class="panel-header">
                <!-- navigation -->
                @include('settings.partials.navigation')
            </div>
            <div class="panel-container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <p>
                            If your site uses a SEO plugin, please select it from the list.
                            This will let Content Launch send HTML, titles and meta descriptions to your CMS.
                        </p>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="input-form-group">
                                    <label for="#">SELECT PLUGIN</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Inbound Writer Integration</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="button button-large button-extend">Submit</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="input-form-group">
                                    <label for="#">SELECT PLUGIN</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Yoast</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="button button-large button-extend">Submit</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="input-form-group">
                                    <label for="#">SELECT PLUGIN</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Metatag (Drupal)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="button button-large button-extend">Submit</button>
                            </div>
                        </div>

                        <div class="input-form-group">
                            <button class="button button-extend">Save Changes</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <i class="icon-notification"></i>
                        <p>
                            Content Launch supports the following plugins:
                            <strong>
                                Inbound Writer Integration, Yoast,
                                All in One SEO, Genesis Theme SEO, SEO Ultimate, Sales Power, Sales Machine, NSM Better
                                Meta, LG Better Meta, Metatag (Drupal)
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

<script type="text/javascript">
    $(function(){
        //tasks
        $('#add-task-button').click(function() {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            $('#addTaskModal').modal('hide');
        }
    });
</script>