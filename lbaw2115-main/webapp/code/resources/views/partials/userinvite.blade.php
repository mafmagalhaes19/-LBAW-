<!-- $user_invite[0] is event, 
        $user_invite[1] is sender, 
        $user_invite[2] is invite id -->
<article class="user_invite">
    <div class="row user-event m-3 info-div rounded bg-light">
        <div class="row">
            <div class="col-sm-8 mt-3">
                <div class="row">
                    <a class="button font-weight-bold pb-2 mx-3" href="{{ url('/event/'.$user_invite[0]->id) }}"><h4 class="font-weight-bold pb-2">{{$user_invite[0]->eventname}}</h4></a>
                </div>
                <div class="row">
                    <div class="col-1 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event text-center"  viewBox="0 0 16 16">
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                        </svg>
                    </div>
                    <div class="col-sm-auto my-auto">
                        <h5 class="my-auto"> {{$event->startdate}} - {{$event->enddate}}</h5>
                    </div>
                    <div class="col-1 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z" />
                        </svg>
                    </div>
                    <div class="col-sm-auto my-auto">
                        <h5 class="my-auto"> {{$event->place}} </h5>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row mt-3">
                    <h4 class="font-weight-bold pb-2">Invited by:</h4>
                </div>
                <div class="row">
                    <a class="button font-weight-bold pb-2 mx-3" href="{{ url('/user/'.$user_invite[1]->id) }}"><h5 class="font-weight-bold pb-2">{{$user_invite[1]->username}}</h5></a>
                </div>
            </div>
            <div class="col-sm-1 d-flex align-items-center">
                <div class="m-2">
                    <a href="{{ route('invite.accept', [ 'user_id' => $user_id, 'invite_id' => $user_invite[2]])}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                        </svg>
                    </a>
                </div>
                <div class="m-2">
                    <a href="{{ route('invite.delete', [ 'user_id' => $user_id, 'invite_id' => $user_invite[2]])}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                            <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</article>
