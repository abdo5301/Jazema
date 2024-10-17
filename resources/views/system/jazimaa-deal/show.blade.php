@extends('system.layouts')

@section('header')
    <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">


    <!-- x-editable (bootstrap version) -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>



@endsection

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">

                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-4">
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Order')}}
                                </h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Value')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{__('ID')}}</td>
                                                <td>{{$result->id}} </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Item')}}</td>
                                                <td>
                                                    {{--<a href="#" id="item_id" data-name="item_id" data-pk="5" data-type="select"   data-value="{{$result->item->{'name_'.\DataLanguage::get()} }}" data-url="{{route('system.deal.update',$result->id)}}"  data-title="{{__('choose Item')}}">{{$result->item->{'name_'.\DataLanguage::get()} }}</a>--}}
                                                    <a target="_blank" href="{{route('system.item.show',$result->item->id)}}">{{$result->item->{'name_'.\DataLanguage::get()} }}</a>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('User')}}</td>
                                                <td>
                                                    <a target="_blank" href="{{route('system.users.show',$result->user->id)}}">{{$result->user->Fullname }}</a>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Owner')}}</td>
                                                <td>
                                                    <a target="_blank" href="{{route('system.users.show',$result->owner->id)}}">{{$result->owner->Fullname }}</a>

                                                </td>
                                            </tr>

                                            {{--<tr>--}}
                                                {{--<td>{{__('Pay Type')}} </td>--}}
                                                {{--<td>--}}
                                                 {{--<a href="#" id="pay_type" data-name="pay_type" data-pk="1" data-type="select" data-pk="1" data-value="{{$result->pay_type}}" data-url="{{route('merchant.order.update',$result->id)}}"  data-title="{{__('choose type')}}">{{$result->pay_type}}</a>--}}
                                                 {{--</td>--}}
                                            {{--</tr>--}}

                                            <tr>
                                                <td>{{__('status')}}</td>
                                                <td><code>{{ $result->status }}</code></td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Price')}}</td>
                                                <td>{{amount($result->price,true)}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Total Price')}}</td>
                                                <td>{{amount($result->total_price,true)}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Created By')}}</td>
                                                <td>
                                                    {{--{!! adminDefineUserWithName($result->creatable_type,$result->creatable_id,\DataLanguage::get()) !!}--}}
                                                    @if(!empty($result->staff_id))
                                                    <td>
                                                        <a target="_blank" href="{{route('system.staff.show',$result->staff_id)}}">{{$result->staff->Fullname}}</a>
                                                    </td>
                                                        @else
                                                        <td>--</td>
                                                    @endif
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>
                                                    @if($result->created_at == null)
                                                        --
                                                    @else
                                                        {{$result->created_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>
                                                    @if($result->updated_at == null)
                                                        --
                                                    @else
                                                        {{$result->updated_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </section>

                    </div>

                    <!-- Start First Table-->
                    <div class="col-md-8">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Deal Option Values')}}
                                </h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">

                                                                    <thead>
                                                                    <th>{{__('ID')}}</th>
                                                                    <th>{{__('Price')}}</th>
                                                                    <th>{{__('Price Prefix')}}</th>
                                                                    <th>{{__('Values')}}</th>
                                                                    </thead>
                                                                    <tbody>
                                                                    @if($result->options->isNotEmpty())
                                                                    @foreach($result->options as $row)

                                                                        <tr>
                                                                            <td>{{$row->id}}</td>
                                                                            <td>{{amount($row->price,true)}}</td>
                                                                            <td>{{$row->prefix_price}}</td>
                                                                            @if(!empty($row->item_option_values))
                                                                                <td>
                                                                                    <div class="card-body collapse in">
                                                                                        <div class="card-block">
                                                                                            <div class="table-responsive">
                                                                                                <table class="table table-hover table-bordered">
                                                                                                    <thead>
                                                                                                    <th>{{__('ID')}}</th>
                                                                                                    <th>{{__('Name')}}</th>
                                                                                                    <th>{{__('Price')}}</th>
                                                                                                    <th>{{__('Price Prefix')}}</th>
                                                                                                    <th>{{__('Status')}}</th>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                    @foreach($row->item_option_values()->where('status','!=','deleted')->get() as $row)
                                                                                                        <tr>
                                                                                                            <td>{{$row->id}}</td>
                                                                                                            <td>{{$row->{'name_'.\DataLanguage::get()} }}</td>
                                                                                                            <td>{{$row->price}}</td>
                                                                                                            <td>{{$row->price_prefix}}</td>
                                                                                                            <td>{{$row->status}}</td>
                                                                                                        </tr>
                                                                                                    @endforeach
                                                                                                    </tbody>
                                                                                                    <tfoot>
                                                                                                    <th>{{__('ID')}}</th>
                                                                                                    <th>{{__('Name')}}</th>
                                                                                                    <th>{{__('Price')}}</th>
                                                                                                    <th>{{__('Price Prefix')}}</th>
                                                                                                    <th>{{__('Status')}}</th>
                                                                                                    </tfoot>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            @else
                                                                                <td><code>--</code></td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach
                                                                    @endif
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <th>{{__('ID')}}</th>
                                                                    <th>{{__('Price')}}</th>
                                                                    <th>{{__('Price Prefix')}}</th>
                                                                    <th>{{__('Values')}}</th>
                                                                    </tfoot>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!--End first Table-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')



    <script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/js/bootstrap-editable.min.js"></script>

    <script type="text/javascript">

  $(document).ready(function() {
            //toggle `popup` / `inline` mode
      $.fn.editable.defaults.mode = 'popup';
      $.fn.editable.defaults.ajaxOptions = {type: "PUT"};

      $('.editableText').editable({
          success: function(response, newValue) {
              if(response.status == false)
                  return response.msg;
              else {
                  setTimeout(function(){ location.reload(); }, 3000);
              }
          },
      });




      $('#pay_type').editable({
          success: function(response, newValue) {
              if(response.status == false)
              return response.msg;
          },
          source: [
              {value: 'one', text: 'one'},
              {value: 'multi', text: 'multi'},
          ]
      });


      $('#user_id').editable({
          success: function(response, newValue) {
              if(response.status == false)
                  return response.msg;
              else {
                  setTimeout(function(){ location.reload(); }, 3000);
                }
          },
          source: [
              @if(isset($users))
                  @foreach($users as $user)
              {value: '{{$user->id}}', text: '{{$user->Fullname}}'},
              @endforeach
              @endif

          ]
      });
  $('#item_owner_id').editable({
          success: function(response, newValue) {
              if(response.status == false)
                  return response.msg;
              else {
                  setTimeout(function(){ location.reload(); }, 3000);
                }
          },
          source: [
              @if(isset($users))
                  @foreach($users as $user)
              {value: '{{$user->id}}', text: '{{$user->Fullname}}'},
              @endforeach
              @endif

          ]
      });

      $('#item_id').editable({
          success: function(response, newValue) {
              if(response.status == false)
                  return response.msg;
          },
          source: [
                  @if(isset($items))
                  @foreach($items as $item)
              {value: '{{$item->id}}', text: '{{$item->{'name_'.\DataLanguage::get()} }}'},
              @endforeach
              @endif

          ]
      });

      $('#user_address_id').editable({
          success: function(response, newValue) {
              if(response.status == false)
                  return response.msg;
          },
          source: [
                  @if(isset($users_addresses))
                  @foreach($users_addresses as $address)
              {value: '{{$address->id}}', text: '{{$address->FullAddress }}'},
              @endforeach
              @endif

          ]
      });
      
        });


    </script>
@endsection
