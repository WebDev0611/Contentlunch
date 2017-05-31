<div class="row">

    @if($promotion && !$userIsOnPaidAccount)
        <div class="col-md-8 col-md-offset-2 promo-notification">
            <div class="alert alert-info text-center" role="alert">
                <b>You are eligible to receive a discount on your content order!</b> <br>
                A $150 credit will be added to your account if you upgrade to a Premium Plan. <br>
                <a href="{{route('subscription')}}"><button class="btn btn-default">Upgrade now</button></a>
            </div>
        </div>
    @endif

    {!! Form::open([ 'url' => 'get_content_written/partials' ]) !!}
    <div class="col-md-8 col-md-offset-2" id='writerAccessForm'>
        @include('partials.error')

        <div class="row">
            <div class="col-md-8">
                <div class="input-form-group">
                    <label for="writer_access_asset_type">CONTENT TYPE</label>
                    <div class="select">
                        <select name="writer_access_asset_type" id="writer_access_asset_type">
                            @foreach($contentTypes  as $contentType)
                                <option value="{{ $contentType->writer_access_id }}">
                                    {{ $contentType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-form-group">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="writer_access_asset_type">WORD COUNT</label>
                            <div class="select">
                                <select name="writer_access_word_count" id="writer_access_word_count">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="#">WRITER LEVEL</label>
                            <div class="select">

                                <select type="text"
                                    class="input"
                                    name="writer_access_writer_level"
                                    id="writer_access_writer_level"
                                    aria-label="Writer Level">

                                    <option value="4">4 Star Writer</option>
                                    <option value="5">5 Star Writer</option>
                                    <option value="6">6 Star Writer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">







                    <div class="col-md-6">
                        <div class="input-form-group">
                            <label for="#">NUMBER OF ORDERS</label>
                            <div class="range-form input-group">
                                <span class="input-group-addon">
                                    <button class="button button-small button-outline-secondary" id='writer_access_count_dec'>
                                        <i class="icon-big-caret-left"></i>
                                    </button>
                                </span>
                                <input type="text"
                                       class="input"
                                       name="writer_access_order_count"
                                       id="writer_access_order_count"
                                       value="1"
                                       aria-label="Number of content titles to order.">

                                <span class="input-group-addon">
                                    <button class="button button-small button-outline-secondary" id='writer_access_count_inc'>
                                        <i class="icon-big-caret-right"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-form-group">
                            <label for="#">Project Deadline</label>
                            @php
                                $dueDateOptions = [
                                    'class' => 'input-calendar datetimepicker input form-control',
                                    'id' => 'dueDate'
                                ];
                            @endphp
                            {!! Form::text('due_date', null, $dueDateOptions) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="create-tabs-priceline">
                    <span>TOTAL</span>
                    @if($isAgencyAccount)
                    <h6>INCLUDING -10% AGENCY DISCOUNT</h6>
                    @endif
                    <h4 id="total_cost">$40.70</h4>
                </div>
                <div class="create-tabs-priceline">
                    <span>COST PER ORDER</span>
                    <h4 id="price_each">$40.70</h4>
                </div>
                @if($promotion && $userIsOnPaidAccount)
                    <div class="create-tabs-priceline" style="margin-bottom: 20px">
                        <span>PROMO CREDIT</span>
                        <h4 id="promo_amount">+$ 0.00</h4>
                        <button id="use_credit" class="btn btn-default" type="button">Use credit</button>
                    </div>
                @endif
                <input id="promo_discount" name="promo_discount" type="hidden" value="0">
            </div>
        </div>
        <input
            type="submit"
            class='button button-extend text-uppercase'
            value='Submit and start ordering process'>
    </div>
    {!! Form::close() !!}
</div>