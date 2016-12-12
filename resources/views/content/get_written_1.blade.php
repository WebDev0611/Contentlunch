@extends('layouts.master')

@section('content')
<div class="workspace">

    <h4 class="text-center">Get Content Written</h4>
    <div class="container-fluid">
        <div class="row">
            {!! Form::open([ 'url' => 'writeraccess/partials/' . $order->id ]) !!}
            {!! Form::hidden('step', 1) !!}
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="create-step">
                                <span class="create-step-point active"></span>
                                <span class="create-step-point"></span>
                                <span class="create-step-point"></span>
                            </div>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger alert-forms" id="formError">
                            <p><strong>Oops! We had some errors:</strong>
                                <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </p>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="input-form-group">
                                <label for="#">CONTENT TITLE</label>
                                {!!
                                    Form::text('content_title', null, [
                                        'class' => 'input',
                                        'placeholder' => "Enter content title (visible to writer)"
                                    ])
                                !!}
                            </div>
                            <div class="input-form-group">
                                <label for="#">INSTRUCTIONS</label>
                                {!!
                                    Form::textarea('instructions', null, [
                                        'class' => 'input',
                                        'rows' => 3,
                                        'placeholder' => 'Enter instructions writer should follow ' .
                                            '(i.e. tone of the article, target group, specific things ' .
                                            'to mention / omit etc.)'
                                    ])
                                !!}
                            </div>
                            <div class="input-form-group">
                                <label>NARRATIVE VOICE</label>
                                <div class="select">
                                    <select name="narrative_voice">
                                        <option value="First Person Plural">First Person Plural</option>
                                        <option value="First Person Singular">First Person Singular</option>
                                        <option value="Second Person">Second Person</option>
                                        <option value="Third Person">Third Person</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-delimiter">
                                <span>
                                    <em>Attachments</em>
                                </span>
                            </div>

                            {{-- @if (isset($content))
                            <div class="input-form-group">
                                <ul>
                                    @foreach ($files as $file)
                                    <li><a href="{{ $file->filename }}">{{ $file->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif --}}

                            <div class="input-form-group">
                                <div class="dropzone" id='attachment-uploader'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <input
                                type="submit"
                                class='button button-extend text-uppercase'
                                value='Next Step'>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type='text/javascript'>
    (function() {

        var fileUploader = new Dropzone('#image-uploader', { url: '/edit/images' });

        fileUploader.on('success', function(file, response) {
            var hiddenField = $('<input/>', {
                name: 'images[]',
                type: 'hidden',
                value: response.file
            });

            hiddenField.appendTo($('form'));
        });

    })();
</script>
@stop