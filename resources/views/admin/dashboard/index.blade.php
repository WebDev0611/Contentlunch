@extends('admin.layouts.master')

@section('header')
    <div class="row border-bottom white-bg dashboard-header">
        <h2>Welcome, {{ Auth::user()->name }}</h2>
    </div>
@endsection

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>User Logins</h5>
        </div>
        <div class="ibox-content">
            <div>
                <canvas id="lineChart" height="100" width="500"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('admin.scripts')
<script>
    function getLastDays(daysCount) {
        return _.times(daysCount, function(day) {
            return moment().subtract(day, 'day').format('MMM Do');
        }).reverse();
    }

    var logins = {!! $logins !!};

    (function() {

        var ctx = document.getElementById("lineChart").getContext("2d");
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: getLastDays(14),
                datasets: [
                    {
                        label: "Logins",
                        backgroundColor: 'rgba(26,179,148,0.5)',
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: logins,
                        lineTension: 0.2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                legend: {
                    display: false,
                },
            },
        });

    })();
</script>
@endpush