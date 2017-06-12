@extends('layouts.master')

@section("styles")
    <style>
        .hide-over-10.order-container > :nth-child(n + 11) {
            display: none;
        }

        .hide-over-10 #showAllPanel{
            display: inline-table;
        }
        .title-cell{
            width: 400px;
        }

        .no-orders-message{
            margin: 20px 40px;
            display: none;
        }

        a.comments-link {
            color: #2481ff !important;
        }
    </style>
@stop

@section('content')
    <div class="workspace">

        <div class="panel clearfix">

            <div class="panel">
                @include('content.partials.dashboard.panel_tabs')

                @include('elements.freemium-alert')

                @include('content.partials.dashboard.order_filter')

                <div class="create-panel-container {{$countOrders !== 0 ? "no-padding" : ""}} order-container hide-over-10">

                    <content-orders-list></content-orders-list>

                    <div class="alert alert-info alert-forms no-orders-message" role="alert"><p>There are no orders for the current filter setting.</p></div>

                    <div class="create-panel-table{{$countOrders <= 10 ? " hide" : ""}}" id="showAllPanel">
                        <div class="create-panel-table-cell text-center">
                            <a href="#">{{$countOrders > 10 ? ($countOrders-10)." More - Show All" : ""}}</a>
                        </div>
                    </div>

                    <div class="create-panel-table" style="display: none;" id="showLessPanel">
                        <div class="create-panel-table-cell text-center">
                            <a href="#">Show Less</a>
                        </div>
                    </div>
                </div>
            </div>
            <aside class="panel-sidebar hide">
                <div class="panel-header">
                    <h4 class="panel-sidebar-title">Orders activity feed</h4>
                </div>
                <div class="panel-container">
                    {{--<div class="plan-activity-box-container">
                        <div class="plan-activity-box-img">
                            <img src="/images/avatar.jpg" alt="#">
                        </div>
                        <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                            <p class="plan-activity-text">
                                Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                                Etiam eget dolor...
                            </p>
                        </div>
                    </div>
                    <div class="plan-activity-box-container">
                        <div class="plan-activity-box-img">
                            <img src="/images/avatar.jpg" alt="#">
                        </div>
                        <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                            <p class="plan-activity-text">
                                Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                                Etiam eget dolor...
                            </p>
                        </div>
                    </div>
                    <div class="plan-activity-box-container">
                        <div class="plan-activity-box-icon">
                            <i class="icon-edit"></i>
                        </div>
                        <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                        </div>
                    </div>
                    <div class="plan-activity-box-container">
                        <div class="plan-activity-box-img">
                            <img src="/images/avatar.jpg" alt="#">
                        </div>
                        <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                            <p class="plan-activity-text">
                                Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                                Etiam eget dolor...
                            </p>
                            <div class="plan-activity-dropdown">
                                <button type="button" class="button button-action" data-toggle="dropdown">
                                    <i class="icon-add-circle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="#">Write It</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>--}}
                    <div class="alert alert-info alert-forms" role="alert"><p>No activity to show.</p></div>
                </div>
            </aside>
        </div>
    </div>

@stop

@section('scripts')
<script>
    (function($){
        var $showAllPanel = $("#showAllPanel"),
            $showLessPanel = $("#showLessPanel"),
            $orderContainer = $(".order-container"),
            $noOrdersMessage = $(".no-orders-message");

        $showAllPanel.on("click", function(e){
            e.preventDefault();
            $orderContainer.removeClass("hide-over-10");
            $showAllPanel.hide();
            $showLessPanel.show();
        });

        $showLessPanel.on("click", function(e){
            e.preventDefault();
            $orderContainer.addClass("hide-over-10");
            $showLessPanel.hide();
            $showAllPanel.show();
        });

        $("#statusFilter").on("change", function(e){
            var status = $(this).val(),
                $allOrderListRows = $(".order-list-row"),
                $matchingOrderListRows = $(".order-list-row[data-status='"+status+"']");

            if(status === "all"){
                $allOrderListRows.removeClass("hide");
                if($allOrderListRows.length > 10){
                    $showAllPanel.show();
                }
            }else{
                $showAllPanel.hide();
                $allOrderListRows.addClass("hide");
                $matchingOrderListRows.removeClass("hide");
                if($matchingOrderListRows.length === 0){
                    $noOrdersMessage.show();
                }else{
                    $noOrdersMessage.hide();
                }
            }
        });

    })(jQuery);
</script>
@stop