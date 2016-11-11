@extends('layouts.master')

@section('content')
<div class="workspace">
    {!! Form::open([ 'url' => 'writeraccess/partials/' . $order->id ]) !!}
    {!! Form::hidden('step', 2) !!}
    <h4 class="text-center">Get Content Written</h4>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="create-step">
                                <span class="create-step-point active"></span>
                                <span class="create-step-point active"></span>
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
                            <div class="form-delimiter">
                                <span>
                                    <em>Target Audience</em>
                                </span>
                            </div>
                            <div class="input-form-group">
                                <label>WHO IS YOUR TARGET AUDIENCE</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='target_audience' value='Customers' type="radio">
                                            <span>Customers</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='target_audience' value='Prospect Customers' type="radio">
                                            <span>Prospect Customers</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='target_audience' value='Knowledge Seekers' type="radio">
                                            <span>Knowledge Seekers</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-delimiter">
                                <span>
                                    <em>Tone of Writing</em>
                                </span>
                            </div>
                            <div class="input-form-group">
                                <label>WHICH CATEGORY BEST MATCHES THE TONE YOU SEEK</label>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_voice' value='Extremely Informal' type="radio">
                                            <span>Extremely Informal</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_voice' value='Journalistic' type="radio">
                                            <span>Journalistic</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_voice' value='Business Formal' type="radio">
                                            <span>Business Formal</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_voice' value='Everyday Informal' type="radio">
                                            <span>Everyday Informal</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_voice' value='Business / Copywriting' type="radio">
                                            <span>Business / Copywriting</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_voice' value='Other' type="radio">
                                            <span>Other</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-form-group">
                                <label>DESCRIBE YOUR DESIRED TONE OF VOICE</label>
                                <textarea
                                    class="input"
                                    name='tone_voice_description'
                                    placeholder="Explain the tone of voice you are seeking in your orders">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <input
                                type="submit"
                                class="button button-extend text-uppercase"
                                value='Next Step'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@stop