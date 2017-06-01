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
            total = this.applyAgencyDiscount(total);
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
            totalPrice = this.applyAgencyDiscount(totalPrice);

            this.$el.find('#price_each').text(this.formatPrice(orderPrice));
            this.$el.find('#total_cost').text(this.formatPrice(totalPrice));

            this.resetPromoCredit();
        },

        applyAgencyDiscount(totalPrice) {
            return !isAgencyAccount ? totalPrice : (totalPrice - (totalPrice * 10/100));
        },

        formatPrice: function(price) {
            return '$ ' + parseFloat(price).toFixed(2);
        },
    });

    var writerAccessForm = new WriterAccessView({ el: '#writerAccessForm' });

})();