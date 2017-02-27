<div class="popup-pointer">
    <p class="play-left"></p>
</div>
<div id="freemium-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-body">

                <h4>You are using free version of the</h4>
                <img src="{{asset('images/logo-full.svg')}}" alt="cl-logo">

                <p>You can still use all app features but not without limits. Certain functionality is limited by number
                    of searches and you can create single Campaign only. To remove limitation switch to paid
                    account.</p>

                <a href="{{route('subscription')}}">
                    <button type="button" class="btn btn-default btn-upgrade">Upgrade now</button>
                </a>
                <button type="button" class="btn btn-default btn-hide" data-dismiss="modal">Hide</button>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var hidePopupCookieName = 'hide_freemium_popup';
        var hidePopupCookie = readCookie(hidePopupCookieName);
        var premiumAccount = false;

        @if(!App\Account::selectedAccount()->activeSubscriptions()->isEmpty())
        {!! 'premiumAccount = true;' !!}
        @endif

        if (premiumAccount == false && (hidePopupCookie == null || hidePopupCookie != '1')) {
            $('.popup-pointer').show();
            $('#freemium-modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        }

        $('.btn-hide').click(function () {
            $('.popup-pointer').hide();
            createCookie(hidePopupCookieName, '1', 3);
        });
    });

    function createCookie(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        else var expires = "";

        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function eraseCookie(name) {
        createCookie(name, "", -1);
    }
</script>