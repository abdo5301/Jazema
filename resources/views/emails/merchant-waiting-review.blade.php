@component('mail::message')
    {{count($data) .' '. __('Merchants Waiting For Review')}}
    <a href="{{route('merchant.merchant.review')}}" >{{__('Click Here')}}<a>
            @component('mail::table')
                | {{__('ID')}} | {{__('Name')}} | {{__('Created By')}} | {{__('Created At')}} |
                | ------------- |:-------------:|
                @foreach($data as $key => $value)
                    | {{$value->id}} | {{$value->name_ar}} | {{$value->staff->fullName}} | {{$value->created_at}} |
                @endforeach


            @endcomponent




            {{__('Thanks')}},
    {{ config('app.name') }} {{__('Team')}}
@endcomponent
