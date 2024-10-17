<table class="table">
    <thead>
    <tr>
        <td>#</td>
        <td>{{__('Value')}}</td>
    </tr>
    </thead>
    <tbody>

    <tr>
        <td>{{__('ID')}}</td>
        <td>{{$result->id}}</td>
    </tr>

    <tr>
        <td>{{__('Name')}}</td>
        <td>{{$result->name}}</td>
    </tr>

    <tr>
        <td>{{__('E-mail')}}</td>
        <td><a href="mailto:{{$result->email}}">{{$result->email}}</a></td>
    </tr>

    <tr>
        <td>{{__('Mobile')}}</td>
        <td><a href="tel:{{$result->mobile}}">{{$result->mobile}}</a></td>
    </tr>

    <tr>
        <td>{{__('Subject')}}</td>
        <td>{{$result->subject}}</td>
    </tr>


    <tr>
        <td>{{__('Message')}}</td>
        <td>{{$result->message}}</td>
    </tr>


    @if(!is_null($result->read_by_staff_id))
        <tr>
            <td>{{__('Seen By')}}</td>
            <td><a href="{{route('system.staff.show',$result->readBy->id)}}" target="_blank">{{$result->readBy->Fullname}}</a></td>
        </tr>
        <tr>
            <td>{{__('Seen about')}}</td>
            <td>{{$result->updated_at->diffForHumans()}}</td>
        </tr>
    @endif

    <tr>
        <td>{{__('Created At')}}</td>
        <td>{{$result->created_at->diffForHumans()}}</td>
    </tr>


    </tbody>
</table>