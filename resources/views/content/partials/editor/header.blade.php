<div class="panel-header">
    <div class="panel-options">
        <div class="row">
            <div class="col-md-6">
                <h4>Content editor</h4>
            </div>
            <div class="col-md-6 text-right">
                <div class="head-actions">
                    <button
                        type="submit"
                        class="button button-outline-secondary button-small delimited"
                        name="action"
                        @if (!$isCollaborator || $isPublished)
                        disabled="disabled"
                        @endif
                        value="written_content">
                        SAVE
                    </button>

                    @if (isset($content))
                        <button
                            type='submit'
                            class="button button-small"
                            name="action"
                            @if (!$isCollaborator || $isPublished)
                            disabled="disabled"
                            @endif
                            value="publish">
                            PUBLISH
                        </button>
                    @endif

                    <div class="btn-group">
                        <button
                            type="submit"
                            class="button button-small"
                            name="action"
                            @if (!$isCollaborator || $isPublished)
                            disabled="disabled"
                            @endif
                            value="ready_to_publish">
                            SUBMIT
                        </button>

                        @if ($isCollaborator)
                        <button type="button" class="button button-small dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            {{-- <li><a href="#">Preview</a></li> --}}
                            {{-- <li><a href="#">Park</a></li> --}}
                            @if (!$isPublished)
                                <li><a href="{{ route('archived_contents.update', $content) }}">Archive</a></li>
                                <li><a href="{{ route('contentDelete', $content->id) }}">Delete</a></li>
                            @endif
                            <li><a id="export-word" href="{{route('export.content', [$content->id, 'docx'])}}" target="_blank">Export as Word</a></li>
                            <li><a id="export-pdf" href="{{route('export.content', [$content->id, 'pdf'])}}" target="_blank">Export as PDF</a></li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End Panel Header -->