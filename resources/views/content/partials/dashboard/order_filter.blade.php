<div class="panel-container-options white">
    <div class="row">
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-2">
                    <label class="select-horizontal-label">{{$countOrders == 1 ? $countOrders." Order" : $countOrders." Orders"}}</label>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-3 text-right"></div>
                        <div class="col-md-8">
                            <div class="select select-small extend">
                                <select name="statusFilter" id="statusFilter">
                                    <option value="all">All</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Pending Approval">Pending Approval</option>
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-3 text-right">
                            <label class="select-horizontal-label">By:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="select select-small extend">
                                <select name="#" id="#">
                                    <option value="#">Any one</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 text-right">
            <div class="create-link-dropdown">
                <a href="#" data-toggle="dropdown">
                    ALL FILTERS
                    <i class="caret"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="#">Do Something</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>