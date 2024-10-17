
@if(staffCan('download.total-consumed.excel') && $downloadExcel == 'false')
    <a style="float: right;margin-bottom: 10px" onclick="downloadExcel()"  class="btn btn-outline-primary"><i class="ft-download"></i> {{__('Download Excel')}}</a>
 @endif

@foreach($merchants as $key =>$value)

            <table class="table table-bordered table-striped" style="width: 100%">
                <thead>

                <tr>
                    <th colspan="2" style="background-color: aliceblue;">
                        <h3 style="text-align: center;">
                            <a target="_blank" href="{{route('system.staff.show',$key)}}">
                                {{$managedStaff[$key]}}
                                {{$managedStaff2[$key]}}
                            </a>
                        </h3>
                    </th>
                </thead>
                <tbody>
                <tr>
                    <th>{{__('Merchant')}}</th>
                    <th>{{__('Total Consumed')}}</th>
                </tr>
                @php
                    $totalPaid = 0;
                @endphp
                @foreach ($value as $kMerchant => $merchant)
                    @php
                        $totalPaid+= $merchant->total_paid;
                    @endphp

                    <tr>
                        <td>
                            <a href="{{route('merchant.merchant.show',$merchant->id)}}" target="_blank">{{$merchant->name}}</a>
                            (<a target="_blank" href="{{route('payment.invoice.index',['merchant_id'=>$merchant->id])}}">{{__('Invoices')}}</a>)
                        </td>
                        <td>{{amount($merchant->total_paid,true)}}</td>
                    </tr>
                @endforeach

                <tr style="background: beige;">
                    <td>{{__('Total')}}:</td>
                    <td>{{amount($totalPaid,true)}}</td>
                </tr>


                </tbody>
            </table>

  
@endforeach



     @if(staffCan('download.total-consumed.excel') && $downloadExcel == 'false')


     <script>
         function downloadExcel(){
             $url = "{!! route('merchant.merchant.total-consumed-ajax',['downloadExcel'=>'true','id'=>$id,'created_at2'=>$created_at2,'created_at1'=>$created_at1]) !!}";
            console.log($url);

             window.location = $url
         }
     </script>


     @endif