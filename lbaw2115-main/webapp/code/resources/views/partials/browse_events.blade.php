<div class="row mb-4">
    <div class="col my-auto" style="max-width: 300px;">
        <img src="{{ $event->pictureurl }}"
            class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
    </div>
    <div class="col-10">
        <h2 class="font-weight-bold">{{ $event->eventname }}</h2>
        <div class="row mb-2">
            <div class="col-1 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-calendar-event text-center" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                    <path
                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                </svg>
            </div>
            <div class="col-sm-auto my-auto">
                <h5 class="my-auto"> {{ $event->startdate }} - {{ $event->enddate }}</h5>
            </div>
            <div class="col-1 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-clock" viewBox="0 0 16 16">
                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                </svg>
            </div>
            <div class="col-sm-auto my-sm-auto">
                <h5 class="my-sm-auto"> {{ $event->duration }}</h5>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-1 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-map" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z" />
                </svg>
            </div>
            <div class="col-sm-auto my-sm-auto">
                <h5 class="my-sm-auto"> {{ $event->place }} </h5>
            </div>

        </div>
        <div class="row mb-3">
            <div class="col-1 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-tag" viewBox="0 0 16 16">
                    <path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0z" />
                    <path
                        d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1zm0 5.586 7 7L13.586 9l-7-7H2v4.586z" />
                </svg>
            </div>
            <div class="col">
                <h5> {{ $event->tag($event->id)->tagname }}</h5>
            </div>
        </div>

        <a class="button font-weight-bold pb-2" href="{{ url('/event/' . $event->id) }}"><h5>Find out more...</h5></a>
    </div>
</div>
