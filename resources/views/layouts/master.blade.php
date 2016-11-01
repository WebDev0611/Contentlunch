<!DOCTYPE html>
<html>
<head lang=en>
    <meta charset=utf-8>
    <meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
    <title>Content Launch</title>
    <meta name=description content="Content Launch">
    <meta name=viewport content="initial-scale=1.0,width=device-width">
    <link rel=stylesheet href=/css/main.css>

    <link rel="stylesheet" href="/css/plugins/dropzone/basic.min.css">
    <link rel="stylesheet" href="/css/plugins/dropzone/dropzone.min.css">
    @yield('styles')
</head>
<body>

@include('elements.navigation')
@include('elements.searchbar')

@include('partials.flash')
@yield('content')

<!-- Create overlay -->
<div class="create-overlay">
  <div class="inner">
    <ul class="list-inline list-createmenu">
      <li class="first">
        <a href="/plan">
          <i class="icon-idea"></i>
          <p class="title">New Idea</p>
          <p>Conceptualize &amp; brainstorm a new content topic with your team!</p>
        </a>
      </li>
      <li class="second">
        <a href="/create">
          <i class="icon-content-alert"></i>
          <p class="title">Content</p>
          <p>Start writing your content or have our team of writers do it for you!</p>
        </a>
      </li>
      <!--
      <li class="third">
        <a href="/campaign">
          <i class="icon-alert"></i>
          <p class="title">Campaign</p>
          <p>Branding campaign? Product launch? Trade show? Capture it here!</p>
        </a>
      </li>
      <li class="fourth">
        <a href="/calendar">
          <i class="icon-calendar"></i>
          <p class="title">Calendar Entry</p>
          <p>Schedule your content, your tasks, your workflow and more! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        </a>
      </li>-->
    </ul>

  </div>
</div>

@include('partials.taskmodal')

<script src="/js/vendor.js"></script>
<script src="/js/plugins.js"></script>
<script src="/js/app.js"></script>
<!-- Page Specific JS -->
@yield('scripts')

<script type="text/javascript">
    var TaskattachmentUploader = new Dropzone('#task-attachment-uploader', {
        headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
        url: '/task/attachments'
    });

    TaskattachmentUploader.on('success', function(file, response) {
        var hiddenField = $('<input/>', {
            class: 'task-attached-files',
            name: 'files[]',
            type: 'hidden',
            value: response.file
        });

        hiddenField.appendTo($('#addTaskModal'));
    });
</script>

</body>
</html>