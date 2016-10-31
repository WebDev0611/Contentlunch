{{ Form::hidden('_token', csrf_token()) }}
<div id="personas-view">
    <div class="row">
        <div class="col-md-8">
            <p class="settings-text">
                These Personas will be used in content and can be changed as needed.
            </p>
        </div>
        <div class="col-md-4">
            <button class="button button-small pull-right" id='new-persona'>
                <i class="icon-add"></i>
                New Persona
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="settings-table" id='personasTable'>
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

<script type='text/template' id='personaRowTemplate'>
    <td><%= name %></td>
    <td><%= description %></td>
    <td>
        <a href class='delete'>
            <i class="icon-trash"></i>
        </a>
    </td>
</script>

<div id="modal-new-persona" class="sidemodal large" style="display: none">

    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">Create new Persona</h4>
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
                    <label for="persona-name">Persona Name</label>
                    {{
                        Form::text(
                            'persona-name',
                            null,
                            [
                                'placeholder' => 'Persona name',
                                'class' => 'input',
                                'id' => 'persona-name'
                            ]
                        )
                    }}
                </div>
                <div class="input-form-group">
                    <label for="persona-description">Description</label>
                    {{
                        Form::text(
                            'persona-description',
                            null,
                            [
                                'class' => 'input',
                                'id' => 'persona-description'
                            ]
                        )
                    }}
                </div>
                <div class="input-form-group" >
                    <button
                        id='submit-persona'
                        class="button button-primary button-small text-uppercase">
                        Create Persona
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>