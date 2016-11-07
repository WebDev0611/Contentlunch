@extends('layouts.master')

@section('content')
<div class="workspace">
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
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-delimiter">
                                <span>
                                    <em>Target Audience</em>
                                </span>
                            </div>
                            <div class="input-form-group">
                                <label for="#">WHO IS YOUR TARGET AUDIENCE</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='target_audience' value='customers' type="radio">
                                            <span>Customers</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='target_audience' value='prospect_customers' type="radio">
                                            <span>Prospect Customers</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='target_audience' value='knowledge_seekers' type="radio">
                                            <span>Knowledge Seekers</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-form-group">
                                <label for="#">WHAT IS THEIR COMPREHENSION LEVEL</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='comprehension' value='newbies' type="radio">
                                            <span>Newbies</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='comprehension' value='basic_knowledge' type="radio">
                                            <span>Basic Knowledge</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='comprehension' value='gurus' type="radio">
                                            <span>Gurus</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-form-group">
                                <label for="#">WHAT IS YOUR TARGET DEMOGRAPHIC</label>
                                @for ($i = 0; $i < 12; $i++)
                                    @if ($i % 3 == 0)
                                        <div class="row">
                                    @endif

                                    <div class="col-md-4">
                                        <label class="checkbox-tag">
                                            <input type="checkbox">
                                            <span>Diesel engines</span>
                                        </label>
                                    </div>

                                    @if ($i % 3 == 2)
                                        </div>
                                    @endif
                                @endfor
                            </div>
                            <div class="input-form-group">
                                <label for="#">OTHER</label>
                                <input type="text" class="input" name='other_demographic' placeholder="Separate by commas">
                            </div>
                            <div class="input-form-group">
                                <label for="#">DESCRIBE YOUR AUDIENCE</label>
                                <textarea
                                    class="input"
                                    placeholder="Who are your customers and what are their characteristics">
                                </textarea>
                            </div>
                            <div class="form-delimiter">
                                <span>
                                    <em>Tone of Writing</em>
                                </span>
                            </div>
                            <div class="input-form-group">
                                <label for="#">WHICH CATEGORY BEST MATCHES THE TONE YOU SEEK</label>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_radio' value='Extremely Informal' type="radio">
                                            <span>Extremely Informal</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_radio' value='Journalistic' type="radio">
                                            <span>Journalistic</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_radio' value='Business Formal' type="radio">
                                            <span>Business Formal</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_radio' value='Everyday Informal' type="radio">
                                            <span>Everyday Informal</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_radio' value='Business / Copywriting' type="radio">
                                            <span>Business / Copywriting</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="radio-secondary">
                                            <input name='tone_radio' value='Other' type="radio">
                                            <span>Other</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-form-group">
                                <label for="#">DESCRIBE YOUR DESIRED TONE OF VOICE</label>
                                <textarea
                                    class="input"
                                    placeholder="Explain the tone of voice you are seeking in your orders">
                                </textarea>
                            </div>
                            <div class="form-delimiter">
                                <span>
                                    <em>SEO Instruction</em>
                                </span>
                            </div>
                            <div class="input-form-group">
                                <textarea class="input" placeholder="Enter any special SEO instructions"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <a href="/get_written/3" class="button button-extend text-uppercase">Next Step</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop