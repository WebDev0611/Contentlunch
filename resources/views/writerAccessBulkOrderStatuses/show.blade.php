@extends('layouts.master')

@section('content')
    <div class="workspace">

        <!-- Pannel Container -->
        <div class="panel clearfix">

            <!-- Main Pane -->

                <!-- Panel Header -->
                <div class="panel-header">
                    <div class="panel-options">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Your Order Progress</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="head-actions">
                                    {{--<button
                                            class="button button-outline-secondary button-small delimited"
                                            id="update-task">
                                        UPDATE
                                    </button>

                                    <div class="btn-group">
                                        <button
                                                class="button button-small"
                                                id="close-task">
                                            CLOSE TASK
                                        </button>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Panel Header -->

                <!-- Panel Container -->
                <div class="panel-container padded relative">


                    <div class="inner">

                        <div class="row">
                            <div class="col-sm-12 col-md-10 col-md-offset-1">
                                <div class="input-form-group text-center">
                                    <label for="start_date" style="margin-bottom: 20px;">Please stand by while we process your order.</label>


                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{$bulkOrderStatus->status_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$bulkOrderStatus->status_percentage}}%">
                                            <span>{{$bulkOrderStatus->status_percentage}}% Complete</span>
                                        </div>
                                    </div>

                                    <div class="loading-icon text-center" style="margin-left: 50%;">
                                        <img style="width: 50px; margin-left: -50%;" src="/images/loading.gif" alt="">
                                    </div>
                                </div>
                            </div>


                        </div>


                        </div>
                        <!-- Editor container -->


                    </div>

                </div>  <!-- End Panel Container -->


        </div>  <!-- End Panel Container -->
    </div>

@stop


@section('styles')

@stop

@section('scripts')
    <script type='text/javascript'>
        (function($){
            var $progressBar = $(".progress-bar");
            var intervalId = setInterval(function(){
                $.ajax({
                    url: "/writeraccess/bulk-orders/status/{{$bulkOrderStatus->id}}",
                    success: function(data){
                        $progressBar
                            .attr("aria-valuenow", data.status_percentage)
                            .css("width", data.status_percentage+"%")
                            .find("span").text(data.status_percentage+"%")
                        ;

                        if(data.status_percentage === 100){
                            clearInterval(intervalId);
                            setTimeout(function(){
                                window.location.href = "/content/orders?bulksuccess=true";
                            }, 2000);

                        }
                    }
                });



            }, 2000);

        })(jQuery);
    </script>
@stop

