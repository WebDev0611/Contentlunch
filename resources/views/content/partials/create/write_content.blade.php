<div class="row">
{{ Form::open(['route' => 'contents.store']) }}

    <div class="col-md-8 col-md-offset-2">
        @if ($errors->content->any())
            <div class="alert alert-danger alert-forms" id="formError">
                <p>
                    <strong>Oops! We had some errors:</strong>
                </p>
                <ul>
                    @foreach($errors->content->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="input-form-group">
            <label for="#">Content Title</label>
            {!! Form::text('title', old('title'), ['placeholder' => 'Enter content title', 'class' => 'input input-larger form-control', 'id' => 'title']) !!}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="input-form-group">
                    <label for="#">CONTENT TYPE</label>
                    <div class="select">
                       {!! Form::select('content_type_id', $contenttypedd, old('content_type_id'), ['class' => 'input selectpicker form-control', 'id' => 'contentType', 'data-live-search' => 'true', 'title' => 'Choose Content Type']) !!}
                    </div>
                </div>
            </div>
            <!--
            <div class="col-md-6">
                <div class="input-form-group">
                    <label for="#">TEMPLATE</label>
                    <div class="select">
                        <select name="" id="">
                            <option value="#">Select Past Template</option>
                        </select>
                    </div>
                </div>
            </div>
            -->
        </div>

        {{--
        <div class="input-form-group">
            <label for="#">Campaign</label>
            <div class="select">
                {!! Form::select('campaign', $campaigndd, @isset($content)? $content->campaign_id : '' , array('class' => 'input form-control', 'id' => 'campaign')) !!}
            </div>
        </div>
        --}}

        <input value="CREATE CONTENT" type="submit" class="button button-extend text-uppercase">

    </div>
     {{ Form::close() }}
</div>