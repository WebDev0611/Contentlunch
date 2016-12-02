<div class="modal fade"
    id="create-subaccount"
    tabindex="-1"
    role="dialog"
    aria-labelledby="Create Sub-Account">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Sub-Account</h4>
            </div>
            <div class="modal-body">
                <div class="inner">
                    {{ Form::open([ 'url' => '/agencies' ]) }}
                    <p class="intro">
                        Create a new account attached to your current one
                    </p>

                    <div class="input-form-group">
                        <label>Account Name</label>
                        {{ Form::text('account_name', null,
                            [ 'class' => 'input', 'placeholder' => 'e.g.: Marketing Account for the First Order']) }}
                    </div>

                    <input type="submit"
                        class='button button-extend text-uppercase'
                        value='Create Sub-Account'>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>