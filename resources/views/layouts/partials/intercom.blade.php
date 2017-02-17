@if (Auth::check())
    <script>
        @php
            $userHash = hash_hmac('sha256', Auth::user()->email, 'gXyMZUYrIYUPGSGe0OE1QuvclBp59zhNUJMno4vH');
        @endphp

        window.intercomSettings = {
            app_id: "s2eiwuo9",
            user_id: User.id || "",
            name: User.name || "",
            email: User.email || "",
            created_at: moment(User.created_at).unix(),
            user_hash: "{!! $userHash !!}" || "",
        };
    </script>
    <script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/s2eiwuo9';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
@else
    <script>
        var User = null;

        window.intercomSettings = {
            app_id: "s2eiwuo9"
        };
    </script>
    <script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/s2eiwuo9';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
@endif