<script>
    @if (Auth::check())
        var User = {!! Auth::user()->toJson() !!};
    @else
        var User = null;
    @endif
</script>