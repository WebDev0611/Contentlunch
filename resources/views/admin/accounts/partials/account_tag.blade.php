<a class="label {{ $account->isAgencyAccount() ? 'label-info' : '' }}"
   href="{{ route('admin.accounts.show', $account) }}">
    {{ $account->name }}
</a>