<!DOCTYPE html>
<html>
<head lang=en>
    <meta charset=utf-8>
    <meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
    <title>Content Launch</title>
    <meta name=description content="Content Launch">
    <meta name=viewport content="initial-scale=1.0,width=device-width">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" >
    <link rel=stylesheet href=/css/main.css>

    <link rel="stylesheet" href="/css/plugins/dropzone/basic.min.css">
    <link rel="stylesheet" href="/css/plugins/dropzone/dropzone.min.css">
    <link rel="stylesheet" href="/css/plugins/sweetalert2/sweetalert2.min.css">
    @yield('styles')

    @yield('scripts.head')
</head>
<body>
<div id="root">

    @include('elements.navigation')
    @include('elements.searchbar')

    @include('partials.flash')
    @yield('content')

    @include('elements.create-overlay')

    {{--
        This is a token to help us with our ajax calls,
        to make sure all pages have tokens.
    --}}
    {{ csrf_field() }}

    @include('partials.taskmodal')

</div>

<script src="/js/vendor.js"></script>
<script src="/js/plugins.js"></script>

<script type='text/javascript'>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name=_token]').val()
        }
    });
</script>
<script src="/js/app.js"></script>
<!-- Page Specific JS -->
@yield('scripts')

<script type="text/javascript">

    (function() {

        window.taskAttachmentUploader = new Dropzone('#task-attachment-uploader', {
            headers: getCSRFHeader(),
            url: '/task/attachments'
        });

        window.taskAttachmentUploader.on('success', function(file, response) {
            var hiddenField = $('<input/>', {
                class: 'task-attached-files',
                name: 'files[]',
                type: 'hidden',
                value: response.file
            });

            hiddenField.appendTo($('#addTaskModal'));
        });

    })();
</script>
<script type='text/javascript'>

    (function() {

        $('.account-selector').click(function(event) {
            event.preventDefault();
            event.stopPropagation();

            var accountId = $(this).data('account-id');

            addLoadingGIF();
            fadeOutMainSelector();
            selectAccount(accountId).then(switchToSelectedAccount);
        });

        function fadeOutMainSelector() {
            $('.account-selector-main span').animate({ opacity: 0.3 }, 200);
        }

        function addLoadingGIF() {
            var loadingGIF = $('<img>', {
                class: 'loading-gif',
                src: '/images/ring.gif',
                style: 'max-height:30px'
            });

            $('.account-selector-main').prepend(loadingGIF);
        }

        function selectAccount(accountId) {
            return $.ajax({
                headers: getCSRFHeader(),
                method: 'post',
                url: '/agencies/select/' + accountId,
            });
        }

        function switchToSelectedAccount(response) {
            location.reload(true);
            var selectedAccount = $('.account-selector[data-account-id=' + response.account + ']');

            $('.account-selector-main')
                .fadeOut('fast', function() {
                    $(this).html(selectedAccount.html());
                })
                .fadeIn('fast')
                .animate({ opacity: 1 }, 200);
        }

    })();

</script>
<script>
    var path = document.location.pathname
    if (path === '/' || path == '/home') {
        new Vue({ el: '#root' });
    }
</script>

@if (Config::get('app.debug'))
    <script type="text/javascript">
        document.write('<script src="{{  Config::get('app.url') }}:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
    </script>
@endif

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-92080489-1', 'auto');
    ga('send', 'pageview');
</script>

@include('layouts.partials.js_user')
@include('layouts.partials.intercom')

</body>
</html>