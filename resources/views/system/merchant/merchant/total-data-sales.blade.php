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
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{$pageTitle}}</h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">

                                        <table style="text-align: center; background: cornsilk;" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">{{__('Year')}}</th>
                                                    <th style="text-align: center;">{{__('Month')}}</th>
                                                </tr>
                                            </thead>

                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <th style="text-align: center;">{{date('Y-01-01')}} : {{date('Y-m-d')}}</th>
                                                    <th style="text-align: center;">{{date('Y-m-01')}} : {{date('Y-m-d')}}</th>
                                                </tr>
                                            </tbody>

                                        </table>

                                        @foreach($result as $key => $value)

                                            <table style="text-align: center;" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">{{__('Staff Name')}}</th>
                                                        <th style="text-align: center;" title="{{__('Total Visits This Year')}}">{{__('V/Y')}}</th>
                                                        <th style="text-align: center;" title="{{__('Total Visits This Month')}}">{{__('V/M')}}</th>

                                                        <th style="text-align: center;" title="{{__('New Merchants This Year')}}">{{__('M/Y')}}</th>
                                                        <th style="text-align: center;" title="{{__('New Merchants This Month')}}">{{__('M/M')}}</th>

                                                        <th style="text-align: center;" title="{{__('Total Consumed This Year')}}">{{__('C/Y')}}</th>
                                                        <th style="text-align: center;" title="{{__('Total Consumed This Month')}}">{{__('C/M')}}</th>


                                                        <th style="text-align: center;" title="{{__('Total Commission This Year')}}">{{__('CO/Y')}}</th>
                                                        <th style="text-align: center;" title="{{__('Total Commission This Month')}}">{{__('CO/M')}}</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr style="background: aliceblue;">
                                                        <td><a href="{{route('system.staff.show',$value['id'])}}" target="_blank">{{$value['fullname']}} ( {{__('Total')}} )</a></td>
                                                        <td>{{number_format($value['count']['visitsThisYear'])}}</td>
                                                        <td>{{number_format($value['count']['visitsThisMonth'])}}</td>
                                                        <td>{{number_format($value['count']['newMerchantsThisYear'])}}</td>
                                                        <td>{{number_format($value['count']['newMerchantsThisMonth'])}}</td>
                                                        <td>{{amount($value['count']['consumedThisYear'],true)}}</td>
                                                        <td>{{amount($value['count']['consumedThisMonth'],true)}}</td>

                                                        <td>{{amount($value['count']['commissionThisYear'],true)}}</td>
                                                        <td>{{amount($value['count']['commissionThisMonth'],true)}}</td>


                                                    </tr>


                                                    @foreach($value['staff'] as $staff)
                                                        <tr>
                                                            <td><a href="{{route('system.staff.show',$staff['id'])}}" target="_blank">{{$staff['fullname']}}</a></td>
                                                            <td>{{number_format($staff['visitsThisYear'])}}</td>
                                                            <td>{{number_format($staff['visitsThisMonth'])}}</td>
                                                            <td>{{number_format($staff['newMerchantsThisYear'])}}</td>
                                                            <td>{{number_format($staff['newMerchantsThisMonth'])}}</td>
                                                            <td>{{amount($staff['consumedThisYear'],true)}}</td>
                                                            <td>{{amount($staff['consumedThisMonth'],true)}}</td>

                                                            <td>{{amount($staff['commissionThisYear'],true)}}</td>
                                                            <td>{{amount($staff['commissionThisMonth'],true)}}</td>

                                                        </tr>
                                                    @endforeach



                                                </tbody>

                                            </table>

                                        @endforeach



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection

@section('header')

@endsection

@section('footer')

@endsection