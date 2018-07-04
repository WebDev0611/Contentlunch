{{ Form::hidden('_token', csrf_token()) }}
<div id="buying-stages-view">
    <div class="row">
        <div class="col-md-8">
            <p class="settings-text">
                A Buying Stage is a point in the customer buying process, which describes the journey your customer goes through before they buy your product/service.
                Typical stages are "awareness", "consideration" and then "purchase".
                Buying stages will be used in the content you create in {{ trans('messages.company') }}, so you can target your content to them.
            </p>
        </div>
        <div class="col-md-4">
            <button class="button button-small pull-right" id='new-buying-stage'>
                <i class="icon-add"></i>
                New Buying Stage
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="settings-table" id='buyingStagesTable'>
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>DESCRIPTION</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type='text/template' id='buyingStageRowTemplate'>
    <td><%= name %></td>
    <td><%= description %></td>
    <td>
        <a href class='delete'>
            <i class="icon-trash"></i>
        </a>
    </td>
</script>

<div id="modal-new-buying-stage" class="sidemodal large" style="display: none">

    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">Create new Buying Stage</h4>
            </div>
            <div class="col-md-6 text-right">
                <button class="sidemodal-close normal-flow" data-dismiss="modal">
                    <i class="icon-remove"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="sidemodal-container">
        <div class="row">
            <div class="col-md-12">
                <div class="input-form-group">
                    <label for="buying-stage-name">Buying Stage Name</label>
                    {{
                        Form::text(
                            'buying-stage-name',
                            null,
                            [
                                'placeholder' => 'Buying Stage name',
                                'class' => 'input',
                                'id' => 'buying-stage-name'
                            ]
                        )
                    }}
                </div>
                <div class="input-form-group">
                    <label for="buying-stage-description">Description</label>
                    {{
                        Form::text(
                            'buying-stage-description',
                            null,
                            [
                                'class' => 'input',
                                'id' => 'buying-stage-description'
                            ]
                        )
                    }}
                </div>
                <div class="input-form-group" >
                    <button
                        id='submit-buying-stage'
                        class="button button-primary button-small text-uppercase">
                        Create Buying Stage
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>