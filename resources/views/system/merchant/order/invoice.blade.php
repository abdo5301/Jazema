<div class="col-md-4">
    <section class="card">
        <div class="card-header">
            <h4 class="card-title">
                {{__('Order Information')}}
            </h4>
        </div>
        <div class="card-body collapse in">
            <div class="card-block">
                <div class="table-responsive">
                    <table class="table table-hover">

                        <tbody>
                        <tr>
                            <td>{{__('Merchant')}} :
                                <a target="_blank" href="{{route('merchant.merchant.show',$data['merchant']->id)}}">
                                    {{$data['merchant']->{'name_'.\DataLanguage::get()} }}
                                </a>
                        </tr>
                        <tr> </td>
                            <td>
                                {{__('Branch')}} : {{$data['merchant_branch']->{'name_'.\DataLanguage::get()} }}
                            </td></tr>
                        <tr>
                            <td>{{__('User')}} : <a target="_blank" href="{{route('system.users.show',$data['user']->id)}}">
                                    {{$data['user']->firstname .' '. $data['user']->lastname }}
                                </a></td>
                        </tr>
                        <tr>
                            <td>{{__('address')}} :   {{ $data['user_address']->area->{'name_'.\DataLanguage::get()} .'-'. $data['user_address']->building_number . $data['user_address']->street_name}}
                                - {{__('floor'). $data['user_address']->floor_number}} - {{__('flat') . $data['user_address']->flat_number}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Coupon')}} :
                            @if(isset($coupon))
                                    {{$coupon->{'name_'.\DataLanguage::get()} .' '.$coupon->discount }}
                                @if($coupon->type  == 'fixed') LE @else % @endif
                                @else --  @endif
                            </td>
                        </tr>
                        <tr><td>{{__('Discount')}} : {{$data['total_discount']}}</td></tr>
                        <tr><td>{{__('Sub-Total')}} : {{$data['sub_total']}}</td></tr>
                        <tr><td>{{__('Total')}} : {{$data['sub_total'] - $data['total_discount'] }}</td></tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </section>

</div>


<div class="col-md-8">
    <section class="card">
        <div class="card-header">
            <h4 class="card-title">
                {{__('Order Products')}}
            </h4>
        </div>
        <div class="card-body collapse in">
            <div class="card-block">

                <table class="table table-hover">
                    <thead>
                    <th>{{__('image')}}</th>
                    <th>{{__('Product')}}</th>
                    <th>{{__('Options')}}</th>
                    </thead>
                    <tbody>
                    @if($products)
                        @foreach($products as $row)
                    <tr>
                        <td>
                            @if ($row['info']->upload()->first())
                               <img style="    width: 150px;" src="{{asset('storage/' . $row['info']->upload()->first()->path)}}" alt="">
                                @endif
                        </td>
                        <td>
                            <p><a target="_blank" href="{{route('merchant.product.show',$row['info']->id)}}">{{$row['info']->{'name_'.\DataLanguage::get()} }}</a></p>
                            <p>{{__('Price')}} : {{$row['info']->price }}</p>
                            <p>{{__('Qty')}} : {{$row['quantity']}}</p>
                        </td>
                        <td>
                            <table class="table table-hover">
                                <thead>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Value')}}</th>
                                <th>{{__('Price')}}</th>
                                </thead>
                                <tbody>

                                @if(!empty($row['option']))
                                    @foreach($row['option'] as $value)

                                <tr>
                                    <td>{{$value['option']->{'name_'.\DataLanguage::get()} }}</td>
                                    @if($value['option']->type == 'text' || $value['option']->type == 'textarea' )
                                     <td>{{$value['value']}}</td><td></td>
                                        @elseif ($value['option']->type == 'check')
                                        <td colspan="2"><table>
                                        @foreach($value['value'] as $val)
                                            <tr>
                                            <td> {{$val->{'name_'.\DataLanguage::get()} }}  </td>
                                            <td>{{$val->price_prefix . $val->price }}</td>
                                            </tr>
                                        @endforeach
                                            </table> </td>
                                        @elseif ($value['option']->type == 'select' || $value['option']->type == 'radio')
                                      <td> {{$value['value']->{'name_'.\DataLanguage::get()} }}  </td>
                                      <td>{{$value['value']->price_prefix }} {{$value['value']->price }}</td>
                                    @endif
                                </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </td>
                    </tr>
                        @endforeach
                        @endif
                    </tbody></table>


            </div></div></section></div>

