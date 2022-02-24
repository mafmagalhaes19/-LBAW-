<ul>
@foreach ($participants as $participant)
    <a href="{{ url('/user/' . $participant->id) }}" class="">
        <li class="list-group-item m-2 rounded border-primary">
            {{ $participant->username }}
        </li>
    </a>
@endforeach

</ul>
