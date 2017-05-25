@extends('layouts.master')

@section('content')
<div class="workspace">
    <h4 class="text-center">Create Content</h4>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="create-tabs-container">
                <ul class="create-tabs">
                    <li class="active">
                        <a href="#WriteContent" data-toggle="tab">
                            <i class="icon-edit-content"></i>
                            <h3>Write Content</h3>
                            <p>
                                Create your blogs, ebooks, landing pages & more!
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#GetContentWritten" data-toggle="tab">
                            <i class="icon-edit-user"></i>
                            <h3>Get Content Written</h3>
                            <p>
                                Have our team of writers produce your content!
                            </p>
                        </a>
                    </li>

                    <li>
                        <a href="#ImportContent" data-toggle="tab">
                            <i class="icon-export"></i>
                            <h3>Import Content</h3>
                            <p>
                                Import content from a file or a web source.
                                &nbsp;
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#UploadContent" data-toggle="tab">
                            <i class="icon-entry"></i>
                            <h3>Upload Content</h3>
                            <p>
                                Upload content in any format from your computer
                            </p>
                        </a>
                    </li>

                </ul>
                <div class="create-tabs-wrapper tab-content">
                    <div id="WriteContent" class="create-tabs-content tab-pane active">
                        @include('content.partials.create.write_content')
                    </div>
                    <div id="GetContentWritten" class="create-tabs-content tab-pane">
                        @include('content.partials.create.get_content_written')
                    </div>
                    <div id="ImportContent" class="create-tabs-content tab-pane">
                        {{-- @include('content.partials.create.import_content') --}}
                        @include('content.partials.create.coming_soon')
                    </div>
                    <div id="UploadContent" class="create-tabs-content tab-pane">
                        {{-- @include('content.partials.create.upload_content') --}}
                        @include('content.partials.create.coming_soon')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="basecamp" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">AUTHORIZATION NEEDED</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-2">
                            <img src="/images/basecamp.png" alt="#" class="img-responsive center-block">
                        </div>
                        <div class="col-md-4 text-center">
                            <p>
                                To be able to import content from Basecamp you need to authorize ContentLaunch
                                to be able to access the Basecamp content. Curabitur maximus et augue eget accumsan.
                                Mauris quis augue vel lorem aliquet convallis sit amet ac dui.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <button class="button text-uppercase button-extend" data-dismiss="#basecamp" data-toggle="modal" data-target="#import">
                            Authorize
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="import" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select content to import from Basecamp</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <button class="button text-uppercase button-extend">IMPORT SELECTED</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(function () {
            // Javascript to enable link to tab
            var url = document.location.toString();
            if (url.match('#')) {
                $('.create-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            }

            // Change hash for page-reload
            $('.create-tabs a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
                window.scrollTo(0, 0);
            })
        });
    </script>

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
                    this.$el.find('#promo_discount').val(0);

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
                    this.$el.find('#promo_discount').val(total - this.total);
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