@component('mail::message')
# {{__('Request Transfer Report For')}} {{$date}}

@component('mail::table',['border'=>'1'])
    | {{__('To')}} | {{__('Date')}} | {{__('Amount')}} | {{__('Created By')}} | {{__('Status')}} |
    | ------------- |:-------------:|
    @foreach($data as $key => $value)
    | {{getWalletOwnerName($value->to_wallet,'en')}} | {{$value->created_at->format('Y-m-d H:i:s')}} | {{amount($value->amount,true)}} | {{$value->staff->fullname}} | {{$value->status}} @if($value->action_staff) <br/> {{__('By')}} {{$value->action_staff->fullname}} @else -- @endif |
    @endforeach

@endcomponent


{{__('Thanks')}},<br>
{{ config('app.name') }} {{__('Team')}}
@endcomponent
