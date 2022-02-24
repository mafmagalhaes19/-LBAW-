@foreach ($polls as $poll)

    <div class=" m-2 p-2 border border-success rounded"><span class="text-bold pl-1">
            @php
                echo App\Http\Controllers\EventController::poll_author($poll->id)->username;
            @endphp
            (Host):
        </span>
        <p class="text-bold m-2 p-2" s>{{ $poll->messagep }}</p>

        <div class="row justify-content-center">
        @foreach($pollOptions as $pollOption)
            @if($poll->id == $pollOption->pollid)
                <div class="col-sm-auto text-center mb-2"><a class="btn btn-sm btn-outline-success"
                   href= "{{ url('/event/' .$event->id.'/poll/'.$pollOption->pollid)}}">{{ $pollOption->messagepo }} : {{ $pollOption->countvote }}</a></div>
            @endif
        @endforeach
        </div>

    </div>

@endforeach