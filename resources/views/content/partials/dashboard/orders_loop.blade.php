
<div class="create-panel-table border-left order-list-row"
     title="{{ $order->title }}"
     data-original-title="{{ $order->title }}"
     data-status="{{ $order->status }}"
     data-writer="{{ $order->writer ? $order->writer->name : "None" }}">
    <div class="create-panel-table-cell">
        <img src="/images/cl-avatar2.png" alt="" class="create-image"><br />
        {{--<small>{{$order->writer ? $order->writer->name : "None"}}</small>--}}
    </div>
    <div class="create-panel-table-cell title-cell">
        <h5 class="dashboard-tasks-title">
            {{ $order->title }}
        </h5>
        <ul class="dashboard-tasks-list">
            <li>STATUS: <strong>{{$order->status}}</strong></li>
            <li>WRITER: <strong>{{$order->writer ? $order->writer->name : "None"}}</strong></li>
        </ul>
    </div>
    <div class="create-panel-table-cell text-right">
        <i class="icon-arrange-mini"></i>
    </div>
    <div class="create-panel-table-cell text-right">
        <span class="dashboard-performing-text small">
            LAUNCHED: <strong>05/05/2016</strong>
        </span>
    </div>
    <div class="create-panel-table-cell text-right">
        <i class="create-panel-spaceship icon-spaceship-circle"></i>
    </div>
</div>