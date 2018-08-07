<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date Created</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($accounts as $account)
        <tr>
            <td>{{ $account->id }}</td>
            <td>{{ $account->name }}</td>
            <td>{{ $account->present()->createdAtFormat('m/d/Y H:i:s') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>