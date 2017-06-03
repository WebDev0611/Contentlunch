<div class="row">
    <div class="col-md-8 col-md-offset-2">
{{--         <div class="input-form-group">
            <label for="authorName" class="checkbox-primary">
                <input id="authorName" type="checkbox">
                <span>Include Author Name into content</span>
            </label>
            <label for="#">APPLY TO CONTENT TYPES</label>
            <div class="select">
                <select name="" id="">
                    <option value="#">Select content types</option>
                </select>
            </div>
        </div>
        <div class="input-form-group">
            <label for="publishingDate" class="checkbox-primary">
                <input id="publishingDate" type="checkbox">
                <span>Allow Publishing Date to be edited</span>
            </label>
            <div class="select">
                <select name="" id="">
                    <option value="#">Select content types</option>
                </select>
            </div>
        </div>
        <div class="input-form-group">
            <label for="KeywordTags" class="checkbox-primary">
                <input id="KeywordTags" type="checkbox">
                <span>Use Keyword Tags</span>
            </label>
            <div class="select">
                <select name="" id="">
                    <option value="#">Select content types</option>
                </select>
            </div>
        </div> --}}

        {{ Form::open([ 'route' => 'guidelines.update' ]) }}

        <div class="input-form-group">
            <label for="#">CONTENT PUBLISHING GUIDELINES</label>
            {{
                Form::textarea(
                    'publishing_guidelines',
                    old('publishing_guidelines', $guidelines->publishing_guidelines),
                    [ 'class' => 'input', 'rows' => 3 ]
                )
            }}
            <p class="help-block">
                Explain most important elements of company’s publishing strategy.
                This information will be provided to each collaborator and guest
                on the Content Editor.
            </p>
        </div>
        <div class="input-form-group">
            <label for="#">EXPLAIN CONTENT STRATEGY IN SHORT</label>
            {{
                Form::textarea(
                    'company_strategy',
                    old('company_strategy', $guidelines->company_strategy),
                    [ 'class' => 'input', 'rows' => 3 ]
                )
            }}
            <p class="help-block">
                Create more content, Invite more collaborators, Schedule a Consultation, Write more blog posts.
            </p>
        </div>
        <div class="input-form-group">
            {{ Form::submit('Save Changes', [ 'class' => 'button button-extend' ]) }}
        </div>
        {{ Form::close() }}
    </div>
</div>