<ul class="list-unstyled">
    @if(isset($content))
        @foreach($content->history() as $adjustment)
        <li>
            @if ($adjustment['type'] == 'content')
                @php
                    $user = $adjustment['adjustment'];
                @endphp

                <div>
                    {{ $user->name }} - {{$user->pivot->updated_at->diffForHumans() }}
                    <a class="btn btn-link showChanges" data-class="changes-{{$user->pivot->id}}">Show Changes</a>
                </div>

                <div class="changes-{{$user->pivot->id}} changes">
                    <h4 class="text-center">Before</h4>
                    <table class="table table-striped">
                        <tr>
                            <th class="text-center">Field</th>
                            <th class="text-center">Before</th>
                        </tr>
                        @foreach(json_decode($user->pivot->before) as $key => $before)
                        <tr>
                            <td><strong>{{ \App\Content::fieldName($key) }}</strong></td>
                            <td>{{ \App\Content::cleanedHistoryContent($key, $before) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="changes-{{$user->pivot->id}} changes">
                    <h4 class="text-center">After</h4>
                    <table class="table table-striped">
                        <tr>
                            <th class="text-center">Field</th>
                            <th class="text-center">After</th>
                        </tr>
                        @foreach(json_decode($user->pivot->after) as $key => $after)
                        <tr>
                            <td><strong>{{ \App\Content::fieldName($key) }}</strong></td>
                            <td>{{ \App\Content::cleanedHistoryContent($key, $after) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            @endif

            @if ($adjustment['type'] == 'content_task')
                <div>
                    @php
                        $task = $adjustment['adjustment']->task;
                    @endphp
                    {{ $adjustment['adjustment']->statusChangeDescription() }}
                    <a href="{{ route('taskShow', $task->id) }}" target="_blank" class="btn btn-link showChanges">Show Task</a>
                </div>
            @endif
        </li>
        @endforeach
    @endif
</ul>