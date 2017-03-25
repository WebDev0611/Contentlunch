<div class="row">


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
            },

            renderOrdersCount: function() {
                this.$el.find('#writer_access_order_count').val(this.orderCount);
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
            },

            formatPrice: function(price) {
                return '$ ' + price + '.00';
            },
        });

        var writerAccessForm = new WriterAccessView({ el: '#writerAccessForm' });

    })();
</script>
@stop