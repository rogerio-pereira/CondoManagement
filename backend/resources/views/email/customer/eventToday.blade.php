<p>Hello, <strong>{{$event->customer->name}}:</strong></p>

<p>
        This is just a reminder that we have and scheduled event today at {{$event->place}} on 
        {{$event->date->format('H:i')}}
</p>

<p>We're axious to meet you there.</p>