(function($){

    function formatCurrency(total) {
        var neg = false;
        if(total < 0) {
            neg = true;
            total = Math.abs(total);
        }
        return (neg ? "-$" : '$') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
    }

    var $contentTypeSelect = $("#writer_access_asset_type"),
        $wordCountSelect = $("#writer_access_word_count"),
        $writerLevelSelect = $("#writer_access_writer_level"),
        $countInput = $("#writer_access_count"),
        $basePriceEl = $("#writer_access_base_price"),
        $totalCost = $("#total_cost"),
        $priceEach = $("#price_each"),
        $countMinusButton = $(".range-form button:eq(0)"),
        $countAddButton = $(".range-form button:eq(1)"),
        $deadlineDateInput = $(".datepicker");

    $contentTypeSelect.on("change", updatePrice);
    $writerLevelSelect.on("change", updatePrice);
    $wordCountSelect.on("change", updatePrice);
    $countInput.on("change", updatePrice);

    $countMinusButton.on("mouseup", function(e){
        $countInput.val(parseInt($countInput.val(), 10) - 1);

        if($countInput.val() < 2){
            $(this).prop("disabled", true);
        }

        updatePrice();
    });

    $countAddButton.on("mouseup", function(e){
        $countInput.val(parseInt($countInput.val(), 10) + 1);

        if($countMinusButton.prop("disabled")){
            $countMinusButton.prop("disabled", false);
        }

        updatePrice();
    });

    $deadlineDateInput.datetimepicker({autoclose: true});

    function updatePrice(){
        console.log(this);
        var prices = prices || {};
        if(!prices){
            console.error("Prices array was not found");
            return;
        }

        var $this = $(this),
            price = 0.0;

        console.log($this.prop("id"));
        // If the content type changes, update the wordcount options
        if($wordCountSelect.val() == null || $this.prop("id") == "writer_access_asset_type") {
            console.log("updating wordcounts");
            console.log($this.val());

            var wordcounts = Object.keys(prices[$this.val()]);
            $wordCountSelect.find("option").remove();
            for (var i = 0, x = wordcounts.length; i < x; i++) {

                var min = wordcounts[i];
                var max = !!wordcounts[i+1] ? wordcounts[i+1]-1 : wordcounts[i]*1.1;

                $wordCountSelect.append($("<option>", {
                    value: wordcounts[i],
                    text: min + " - " + max,
                    selected: i==0
                }));
            }
        }

        price = prices[$contentTypeSelect.val()][$wordCountSelect.val()][$writerLevelSelect.val()];

        $basePriceEl.val(formatCurrency(price));
        $priceEach.text(formatCurrency(price));
        $totalCost.text(formatCurrency(price*parseInt($countInput.val(), 10)));
    }

   // updatePrice.call(document.getElementById("writer_access_asset_type"));


})(jQuery);



