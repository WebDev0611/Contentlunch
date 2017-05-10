<div class="row">

    @if($promotion && !$userIsOnPaidAccount)
        <div class="col-md-8 col-md-offset-2 promo-notification">
            <div class="alert alert-info text-center" role="alert">
                You are eligible for getting 3 free content orders! <br>
                $150 extra credit will be added to your account if you upgrade to one of our Premium Plans. <br>
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
            </div>
        </div>
        <input
            type="submit"
            class='button button-extend text-uppercase'
            value='Submit and start ordering process'>
    </div>
    {!! Form::close() !!}
</div>

@section('scripts')
<script type="text/javascript">
    var prices =  (function() { return {!! $pricesJson !!}; })();

    @if($promotion && $userIsOnPaidAccount)
        var promoCreditAmount = {{$promotion->credit}};
    @else
        var promoCreditAmount = 0.00;
    @endif
    var creditLeft = promoCreditAmount;


    $('.datetimepicker').datetimepicker({
        format: 'MM-DD-YYYY'
    });

    (function() {

        var WriterAccessView = Backbone.View.extend({
            events: {
                'click #writer_access_count_inc': 'increaseWriterAccessCount',
                'click #writer_access_count_dec': 'decreaseWriterAccessCount',
                'change #writer_access_asset_type': 'render',
                'change #writer_access_word_count': 'calculateOrderPrices',
                'change #writer_access_writer_level': 'calculateOrderPrices',
                'click #use_credit': 'applyPromoCredit',
            },

            initialize: function() {
                this.orderCount = 1;
                this.render();
            },

            render: function () {
                this.renderOrdersButton();
                this.renderOrdersCount();
                this.populateWordCountSelect();
                this.calculateOrderPrices();
                this.renderPromoCredit();
            },

            renderOrdersCount: function() {
                this.$el.find('#writer_access_order_count').val(this.orderCount);
            },

            resetPromoCredit: function () {
                creditLeft = promoCreditAmount;
                this.total = this.basePrice() * this.orderCount;

                this.renderPromoCredit();
            },

            renderPromoCredit: function () {
                this.$el.find('#promo_amount').text('+' + this.formatPrice(creditLeft));
                if(creditLeft === 0 || this.total === 0) {
                    this.$el.find('#use_credit').hide();
                } else {
                    this.$el.find('#use_credit').show();
                }
            },

            applyPromoCredit: function () {
                var total = this.basePrice() * this.orderCount;
                var diff = total - creditLeft;

                creditLeft = (diff < 0) ? Math.abs(diff) : 0;

                this.total = Math.max(0, diff);
                this.$el.find('#total_cost').text(this.formatPrice(this.total));
                this.renderPromoCredit();
            },

            increaseWriterAccessCount: function(e) {
                e.preventDefault();
                this.orderCount++;
                this.render();
            },

            decreaseWriterAccessCount: function(e) {
                e.preventDefault();
                if (this.orderCount > 2) {
                    this.orderCount--;
                } else {
                    this.orderCount = 1;
                }
                this.render();
            },

            renderOrdersButton: function() {
                if (this.orderCount == 1) {
                    this.$el.find('#writer_access_count_dec').prop('disabled', true);
                } else {
                    this.$el.find('#writer_access_count_dec').prop('disabled', false);
                }
            },

            populateWordCountSelect: function() {
                var assetId = this.getAssetId();
                var pricesObject = prices[assetId];
                var wordcounts = Object.keys(pricesObject);
                this.clearWordCount();

                for (var i = 0; i < wordcounts.length; i++) {
                    var wordcount = wordcounts[i];
                    var element = $('<option>', {
                        value: wordcount,
                        text: wordcount
                    })

                    element.appendTo('#writer_access_word_count');
                }
            },

            getAssetId: function() {
                return this.$el.find('#writer_access_asset_type').val();
            },

            clearWordCount: function() {
                this.$el.find('#writer_access_word_count').html('');
            },

            wordCount: function() {
                return parseInt(this.$el.find('#writer_access_word_count').val());
            },

            writerLevel: function() {
                return parseInt(this.$el.find('#writer_access_writer_level').val());
            },

            basePrice: function() {
                var assetId = this.getAssetId();
                var wordCount = this.wordCount();
                var writerLevel = this.writerLevel();

                return prices[assetId][wordCount][writerLevel];
            },

            calculateOrderPrices: function() {
                var orderPrice = this.basePrice();
                var totalPrice = orderPrice * this.orderCount;

                this.$el.find('#price_each').text(this.formatPrice(orderPrice));
                this.$el.find('#total_cost').text(this.formatPrice(totalPrice));

                this.resetPromoCredit();
            },

            formatPrice: function(price) {
                return '$ ' + price + '.00';
            },
        });

        var writerAccessForm = new WriterAccessView({ el: '#writerAccessForm' });

    })();
</script>
@stop