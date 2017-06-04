<script>
    @if (Auth::check())
        var User = {!! Auth::user()->toJson() !!};
        var Account = {!! App\Account::selectedAccount()->toJson() !!};
        var AccountPlan = {!! App\Account::selectedAccount()->activeSubscriptionType()->toJson() !!};
    @else
        var User = null;
        var Account = null;
        var AccountPlan = null;
    @endif
</script>