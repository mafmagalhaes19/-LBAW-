<h1 class = "pb-2">Events</h1>
<h3 class="ms-1">Participant</h3>
<div class="border border-primary rounded bg-light text-dark ms-1">
    @if (count($events_as_participant) > 0)
        @foreach ($events_as_participant as $event)
            @include('partials.userevent', ['event' => $event])
        @endforeach
    @else
        <p class="font-weight-bold pb-2 m-3">This user is not participating in any events</p>
    @endif
</div>

<h3 class="pt-4">Host</h3>
<div class="border border-primary  bg-light text-dark rounded">
    @if (count($events_as_host) > 0)
        @foreach ($events_as_host as $event)
            @include('partials.userevent', ['event' => $event])
        @endforeach
    @else
        <p class="font-weight-bold pb-2 m-3">This user is not hosting any events</p>
    @endif
</div>

@if ((Auth::user()->id == $user->id))
<h3 class="pt-4">Invites</h3>
<div class="border border-primary  bg-light text-dark rounded">
        @if (count($user_invites) > 0)
            @foreach ($user_invites as $user_invite)
                @include('partials.userinvite', ['user_invite' => $user_invite, 'user_id' => $user->id])
            @endforeach
        @else
            <p class="font-weight-bold pb-2 m-3">You have no pending invites.</p>
        @endif
</div>
@endif
