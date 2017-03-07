<div class="panel">

    <!-- Content Block -->
    <div class="panel-content">

        <div class="panel-header">
            <div class="panel-sidebar-title">

                <div class="row">
                    <div class="col-md-6">
                        <p>{{ $accounts->count() }} Clients</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <button
                            type="button"
                            class="button button-small withstarticon"
                            data-toggle="modal"
                            data-target="#create-subaccount">

                            <i class="icon-add"></i> NEW SUB-ACCOUNT
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div class="dashboard-content-box height-double">
            <table class="table table-list">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th># of Projects</th>
                        <th>Collaborators</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                    <tr>
                        <td>
                            <div class="clientlogo">
                                <img src="{{ $account->present()->account_image }}" alt="{{ $account->name }}"/>
                            </div>
                            <p class="title">{{ $account->name }}</p>
                        </td>
                        <td>{{ $account->contents->count() }}</td>
                        <td>{{ $account->users->count() }}</td>
                        <td class="tbl-right">
                            {{-- <div class="actionbtnbox">
                                <button
                                    type="button"
                                    class="button button-action"
                                    data-toggle="dropdown">

                                <i class="icon-add-circle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="#">Action 1</a>
                                        <a href="#">Action 2</a>
                                        <a href="#">Action 3</a>
                                    </li>
                                </ul>
                            </div> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> <!-- End Content Block -->
</div>