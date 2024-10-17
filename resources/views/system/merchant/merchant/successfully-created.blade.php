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
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Merchant Data')}}
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
                                                <td>{{__('Merchant Name')}}</td>
                                                <td>{{$requestData['merchant_name'] ?? 'NaN'}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Merchant ID')}}</td>
                                                <td>{{$requestData['merchant_id'] ?? 'NaN'}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Merchant Staff ID')}}</td>
                                                <td>{{$requestData['merchant_staff_id'] ?? 'NaN'}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Merchant Staff Password')}}</td>
                                                <td>{{$requestData['password'] ?? 'NaN'}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Payment Wallet ID')}}</td>
                                                <td>{{$requestData['payment_wallet'] ?? 'NaN'}} <button onclick="location = '{{route('system.wallet.transferMoneyWallets',['send_to'=>$requestData['payment_wallet']])}}'" class="btn btn-xs btn-success">{{__('Transfer Money')}}</button> </td>
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
@endsection

@section('footer')
@endsection
