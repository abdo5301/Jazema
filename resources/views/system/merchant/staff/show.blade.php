@extends('system.layouts')

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

                    <div class="col-md-12">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Merchant Staff')}}
                                    <span style="float: right;">
                                        <a class="btn btn-outline-primary" href="javascript:void(0);" onclick="urlIframe('{{route('merchant.staff.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a>

                                        @if(staffCan('merchant.staff.generate-temp-password'))
                                            <a class="btn btn-outline-primary" id="changeMerchantStaffPassButton" href="javascript:void(0);" onclick="changeMerchantStaffPassword();">
                                                <i class="fa fa-key"></i> {{__('Send New Password by SMS')}}
                                            </a>

                                            <a class="btn btn-outline-primary" id="generateTempPasswordButton" href="javascript:void(0);" onclick="generateTempPassword();">
                                                <i class="fa fa-key"></i> {{__('Generate Temp Password')}}
                                            </a>
                                        @endif
                                    </span>
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
                                                <td>{{__('Merchant ID')}}</td>
                                                <td>{{$result->merchant()->id}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('ID')}}</td>
                                                <td>{{$result->id}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Name')}}</td>
                                                <td>{{$result->firstname}} {{$result->lastname}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Username')}}</td>
                                                <td>{{$result->username}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('National ID')}}</td>
                                                <td>{{$result->national_id}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Email')}}</td>
                                                <td><a href="mailto:{{$result->email}}">{{$result->email}}</a></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Mobile')}}</td>
                                                <td><a href="tel:{{$result->mobile}}">{{$result->mobile}}</a></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Address')}}</td>
                                                <td><code>{{$result->address}}</code></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Birthdate')}}</td>
                                                <td>{{$result->birthdate}}</td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                <td>
                                                    @if($result->status == 'active')
                                                        <b style="color: green;">Active</b>
                                                    @else
                                                        <b style="color: red;">In-Active</b>
                                                    @endif
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Branches can access')}}</td>
                                                <td>
                                                    @foreach($branches as $value)
                                                        <a href="{{route('merchant.branch.show',$value->id)}}">{{$value->{'name_'.\DataLanguage::get()} }}</a> <hr>
                                                    @endforeach
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Last Login')}}</td>
                                                <td>
                                                    @if(!$result->lastlogin)
                                                        --
                                                    @else
                                                        {{$result->lastlogin->diffForHumans()}}
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



                </div>
            </div>
        </div>
    </div>

@endsection

@section('header')
@endsection;

@section('footer')
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.bootstrap3.js')}}"></script>

    <script type="text/javascript">

        function changeMerchantStaffPassword(){

            $('#changeMerchantStaffPassButton').html('{{__('Loading...')}}');
            $('#changeMerchantStaffPassButton').attr('disabled','disabled');

            if(!confirm('{{__('Do you want to change Password for this merchant ?')}}')){
                $('#changeMerchantStaffPassButton').html('<i class="fa fa-key"></i> {{__('Change Password')}}');
                $('#changeMerchantStaffPassButton').removeAttr('disabled');

                return false;
            }

            $.get('{{route('merchant.staff.edit',$result->id)}}?changePassword=true',function(data){
                $('#changeMerchantStaffPassButton').html('{{__('Done')}}');
                alertSuccess('{{__('Password Has been changed successfully')}}');
            });
        }


        @if(staffCan('merchant.staff.generate-temp-password'))
        function generateTempPassword(){

            $('#generateTempPasswordButton').html('{{__('Loading...')}}');
            $('#generateTempPasswordButton').attr('disabled','disabled');
            $('#generateTempPasswordButton').removeAttr('onclick');

            if(!confirm('{{__('Do you want to change Password for this merchant ?')}}')){
                $('#generateTempPasswordButton').html('<i class="fa fa-key"></i> {{__('Generate Temp Password')}}');
                $('#generateTempPasswordButton').removeAttr('disabled');
                $('#generateTempPasswordButton').attr('onclick','generateTempPassword();');

                return false;
            }

            $.post('{{route('merchant.staff.generate-temp-password',['id'=>$result->id])}}',function(data){
                $('#generateTempPasswordButton').html('{{__('New Password is:')}} '+data.code);
            });
        }
        @endif

        $(document).ready(function(){
            $('#product-list').treegrid({
                expanderExpandedClass: 'fa fa-minus',
                expanderCollapsedClass: 'fa fa-plus'
            });
        });
    </script>
@endsection
