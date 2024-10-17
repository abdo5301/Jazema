<table class="table">
    <thead>
        <tr>
            <th>{{__('Merchant ID')}}</th>
            <th>{{__('Merchant Name')}}</th>
            <th>{{__('Sales')}}</th>
            <th>{{$date1}}</th>
            <th>{{$date2}}</th>
            <th>{{__('Comparison')}}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($merchants as $key => $value)


        @if(isset($first[$value->id]))
            @php
                $FF = $first[$value->id];
            @endphp
        @else
            @php
                $FF = 0;
            @endphp
        @endif


        @if(isset($second[$value->id]))
            @php
                $SS = $second[$value->id];
            @endphp
        @else
            @php
                $SS = 0;
            @endphp
        @endif

        @php
            $display = '';
        @endphp

        @if( ( $status == 'down' && !($FF > $SS) ) || ( $status == 'positive' && !($SS > $FF) ) || ( $status == 'equal' && !($SS == $FF) ) )
                @php
                  continue; // $display = 'display:none';
                @endphp
        @endif

        <tr style="{{$display}}">
            <td>{{$value->id}}</td>
            <td><a href="{{route('merchant.merchant.show',$value->id)}}" target="_blank">{{$value->name}}</a></td>
            <td><a href="{{route('system.staff.show',$value->staff_id)}}" target="_blank">{{$value->staff->Fullname}}</a></td>
            <td>{{amount($FF,true)}}</td>
            <td>{{amount($SS,true)}}</td>
            <td>
                @if($FF > $SS)
                    <b style="color: red;">{{__('Down')}}</b>
                @elseif($SS > $FF)
                    <b style="color: green;">{{__('Positive')}}</b>
                @else
                    {{__('Equal')}}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>