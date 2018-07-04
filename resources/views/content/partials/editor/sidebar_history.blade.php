<ul class="list-unstyled">
    @if(isset($content))
        @foreach($content->history() as $activity)
        <li>
            @if ($activity->subject_type == 'App\Content')
                @php
                    $user = $activity->causer;
                @endphp

                <div>
                    {{ $user->name }} - {{ $activity->updated_at->diffForHumans() }}
                    <a class="btn btn-link showChanges" data-class="changes-{{ $activity->id }}">Show Changes</a>
                </div>

                <div class="changes-{{ $activity->id }} changes">
                    <h4 class="text-center">Before</h4>
                    <table class="table table-striped">
                        <tr>
                            <th class="text-center">Field</th>
                            <th class="text-center">Before</th>
                        </tr>
                        @if(isset($activity->properties['old']))
                            @foreach($activity->properties['old'] as $key => $before)
                            <tr>
                                <td><strong>{{ \App\Content::fieldName($key) }}</strong></td>
                                <td>{{ \App\Content::cleanedHistoryContent($key, $before) }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
                <div class="changes-{{ $activity->id }} changes">
                    <h4 class="text-center">After</h4>
                    <table class="table table-striped">
                        <tr>
                            <th class="text-center">Field</th>
                            <th class="text-center">After</th>
                        </tr>
                        @foreach($activity->properties['attributes'] as $key => $after)
                        <tr>
                            <td><strong>{{ \App\Content::fieldName($key) }}</strong></td>
                            <td>{{ \App\Content::cleanedHistoryContent($key, $after) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            @endif

            @if ($activity->subject_type == 'App\Task')
                <div>
                    @php
                        $task = $activity->subject;
                    @endphp

                    {{ $activity->statusDescription }}
                    <a  href="{{ route('tasks.edit', $task->id) }}"
                        target="_blank"
                        class="btn btn-link showChanges">
                        Show Task
                    </a>
                </div>
            @endif
        </li>
        @endforeach
    @endif
</ul>