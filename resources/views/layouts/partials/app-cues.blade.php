<script src="//fast.appcues.com/14277.js"></script>
@if (Auth::check())
<script>
    Appcues.identify("{{ Auth::user()->id }}", {
        name: "{{ Auth::user()->name }}",
        email: "{{ Auth::user()->email }}",
        created_at: {{ Auth::user()->created_at->timestamp }},

        belongs_to_agency_account: "{!! Auth::user()->belongsToAgencyAccount() !!}" === '1',
    });
</script>
@endif