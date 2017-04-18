<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Accounts</th>
        <th>Date Created</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->present()->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @foreach ($user->accounts as $account)
                    <span class="label {{ $account->isAgencyAccount() ? 'label-info' : '' }}">{{ $account->name }}</span>
                @endforeach
            </td>
            <td>{{ $user->present()->createdAtFormat('m/d/Y H:i:s') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>