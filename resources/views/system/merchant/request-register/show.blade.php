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
        <td>{{__('Shop Name')}}</td>
        <td>{{$result->shop_name}}</td>
    </tr>

    <tr>
        <td>{{__('reseller')}}</td>
        <td><a target="_blank" href="{{route('merchant.merchant.show',$result->reseller_id)}}" >{{$result->reseller_name}}</td>
    </tr>

    <tr>
        <td>{{__('Mobile')}}</td>
        <td><a href="tel:{{$result->mobile}}">{{$result->mobile}}</a></td>
    </tr>


    <tr>
        <td>{{__('mobile 2')}}</td>
        <td><a href="tel:{{$result->mobile2}}">{{$result->mobile2}}</a></td>
    </tr>




    <tr>
        <td>{{__('Address')}}</td>
        <td><code>{{$result->address}}</code></td>
    </tr>


    <tr>
        <td>{{__('National ID')}}</td>
        <td><a href="tel:{{$result->national_id}}">{{$result->national_id}}</a></td>
    </tr>


    <tr>
        <td>{{__('ID Front')}}</td>
        <td>
            <a target="_blank" href="data:image/png;base64,{{$result->id_front}}">{{__('View')}}</a>
        </td>
    </tr>

    <tr>
        <td>{{__('ID Back')}}</td>
        <td>
            <a target="_blank" href="data:image/png;base64,{{$result->id_back}}">{{__('View')}}</a>
        </td>
    </tr>
    <tr>
        <td>{{__('Utility Receipt')}}</td>
        <td>
            <a target="_blank" href="data:image/png;base64,{{$result->utility_receipt}}">{{__('View')}}</a>
        </td>
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