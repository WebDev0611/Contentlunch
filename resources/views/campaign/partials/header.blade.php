<div class="panel-options">
    <div class="row">
        <div class="col-md-offset-6 col-md-6 text-right">
            <div class="head-actions">
                {{
                    Form::submit('SAVE', [
                        'class' => 'button button-outline-secondary button-small delimited',
                    ])
                }}

                <div class="btn-group">
                    <button
                        type="button"
                        class="button button-small">DONE</button>

                    <button
                        type="button"
                        class="button button-small dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">

                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#">Park</a></li>
                        <li><a href="#">Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>