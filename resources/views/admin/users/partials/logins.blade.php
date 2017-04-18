<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Accounts</th>
        <th>Date Created</th>
        <th>Last Login</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($logins as $login)
        <tr>
            <td>{{ $login->user->id }}</td>
            <td>{{ $login->user->present()->name }}</td>
            <td>{{ $login->user->email }}</td>
            <td>
                @foreach ($login->user->accounts as $account)
                    <span class="label {{ $account->isAgencyAccount() ? 'label-info' : '' }}">{{ $account->name }}</span>
                @endforeach
            </td>
            <td>{{ $login->user->present()->createdAtFormat('m/d/Y H:i:s') }}</td>
            <td>{{ $login->present()->createdAtFormat('m/d/Y H:i:s') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>