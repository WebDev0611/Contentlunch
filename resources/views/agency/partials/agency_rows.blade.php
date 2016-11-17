@foreach ($accounts as $account)
<tr>
    <td>
        <div class="clientlogo">
            <img src="/images/logo-client-fake.jpg" alt="XX"/>
        </div>
        <p class="title">{{ $account->name }}</p>
    </td>
    <td>15</td>
    <td>2</td>
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