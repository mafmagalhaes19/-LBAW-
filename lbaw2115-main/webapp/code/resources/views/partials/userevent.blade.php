<article class="user_event">
    <div class="row user-event m-3 info-div rounded">
        <div class="col-sm-9">
            <div class="row">
                <h4 class="font-weight-bold pb-2">{{$event->eventname}}</h4>
            </div>
            <div class="row pb-1">
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
        <div class="col-sm-3 d-flex align-items-center">
            <h4 class="font-weight-bold pb-2">
                <a class="button" href="{{ url('/event/'.$event->id) }}">Find out more</a>
            </h4>
        </div>
    </div>
</article>
