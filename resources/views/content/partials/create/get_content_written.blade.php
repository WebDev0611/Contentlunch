<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="input-form-group">
            <label for="#">PROJECT NAME</label>
            <input type="text" class="input" placeholder="Enter project name">
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <label for="#">NUMBER OF TITLES</label>
                        <div class="range-form input-group">
                            <span class="input-group-addon">
                                <button class="button button-small button-outline-secondary" disabled>
                                    <i class="icon-big-caret-left"></i>
                                </button>
                            </span>
                            <input type="text" class="input" name="writer_access_count" id="writer_access_count" value="1" aria-label="Number of content titles to order.">
                            <span class="input-group-addon">
                                <button class="button button-small button-outline-secondary">
                                    <i class="icon-big-caret-right"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-form-group">
                            <label for="#">Project Deadline</label>
                            <input type="text" class="input datepicker" name="dealine" id="deadline" placeholder="Project Deadline!">
                        </div>
                    </div>
                </div>
                <div class="input-form-group">
                    <label for="writer_access_asset_type">CONTENT TYPE</label>
                    <div class="select">
                        <select name="writer_access_asset_type" id="writer_access_asset_type">
                            @foreach($contentTypes  as $contentType)
                                <option value="{{$contentType->writer_access_id}}">{{$contentType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-form-group">
                    <label for="writer_access_asset_type">WORD COUNT</label>
                    <div class="select">
                        <select name="writer_access_word_count" id="writer_access_word_count">

                        </select>
                    </div>
                </div>
                <div class="input-form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="#">WRITER LEVEL</label>
                            <div class="select">

                                <select type="text" class="input" name="writer_access_writer_level" id="writer_access_writer_level" aria-label="Amount (to the nearest dollar)">
                                    <option value="4">4 Star Writer</option>
                                    <option value="5">5 Star Writer</option>
                                    <option value="6">6 Star Writer</option>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-form-group">
                                <label for="#">Base Priceline</label>
                                <input type="text" class="input" disabled name="writer_access_base_price" id="writer_access_base_price"  placeholder="Base Priceline">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="create-tabs-priceline">
                    <span>TOTAL ORDER</span>
                    <h4 id="total_cost">$40.70</h4>
                </div>
                <div class="create-tabs-priceline">
                    <span>COST / ORDER</span>
                    <h4 id="price_each">$40.70</h4>
                </div>
            </div>
        </div>
        <button href="javascript:;" onclick="window.location.href = '/get_written';" class="button button-extend text-uppercase">
            SUBMIT AND START ORDERING PROCESS
        </button>

        <script type="text/javascript">
            // This is brilliant... seriously!
            var prices =  (function(){ return {!! $pricesJson !!}; })();
        </script>
    </div>
</div>