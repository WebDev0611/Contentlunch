<script src="//fast.appcues.com/14277.js"></script>
@if (Auth::check())
<script>
    Appcues.identify("{{ $user->id }}", {
        name: "{{ $user->name }}",
        email: "{{ $user->email }}",
        created_at: {{ $user->created_at->timestamp }},

        belongs_to_agency_account: "{!! Auth::user()->belongsToAgencyAccount() !!}" === '1',
    });
</script>
@endif