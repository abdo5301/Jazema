@component('mail::message')
# Introduction

{{$data['message']}}

@component('mail::button', ['url' => route('web.index')])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
