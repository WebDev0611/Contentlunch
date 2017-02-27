<ul class="panel-tabs text-center">
    <li class="{{ (Request::is('subscription') ? 'active' : '') }}">
        <a href="{{ route('subscription') }}">Subscription</a>
    </li>
    @if($account->isAgencyAccount ())
        <li class="{{ (Request::is('subscription/clients') ? 'active' : '') }}">
            <a href="{{ route('subscription-clients') }}">Clients subscriptions</a>
        </li>
    @endif
</ul>