<div class="col-md-12 tab-pane fade-in active" id="stop-working">
    <h4>{{__('Stop working Merchant since ' .$date)}} ({{$stopWorkingCount}}) </h4>
    <section id="spacing" class="card">
        <div class="card-header">

        </div>
        <div class="card-body collapse in">
            <div class="card-block">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <td>{{__('ID')}}</td>
                            <td>{{__('Merchant Name')}}</td>
                            <td>{{__('Seller')}}</td>
                            <td>{{__('Created At')}}</td>
                            <td>{{__('Wallet Trans')}}</td>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($stopWorkingMerchant as $value)
                            @if($value->walletowner)

                                <tr>
                                    <td>{{$value->walletowner->id}}</td>
                                    <td><a target="_blank" href = "{{route('merchant.merchant.show',$value->walletowner->id)}}"> {{$value->walletowner->{'name_'.\DataLanguage::get()} }}</a></td>
                                    <td><a target="_blank" href = "{{route('system.staff.show',$value->walletowner->staff->id)}}">{{$value->walletowner->staff->Fullname}}</a></td>
                                    <td>{{$value->walletowner->created_at->diffForHumans()}}</td>
                                    <td> <button class="btn btn-primary" type="button" onclick='location = "{{route('system.wallet.show',$value->id)}}"'><i class="ft-eye"></i></button></td>

                                </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>


<div class="col-md-12 tab-pane fade-in "  id="not-working">
    <h4>{{__('Not working Merchants')}} ({{$notWorkingCount}})</h4>
    <section id="spacing" class="card">
        <div class="card-header">
        </div>
        <div class="card-body collapse in">
            <div class="card-block">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <td>{{__('ID')}}</td>
                            <td>{{__('Merchant Name')}}</td>
                            <td>{{__('Seller')}}</td>
                            <td>{{__('Created At')}}</td>
                            <td>{{__('Wallet Trans')}}</td>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($result as $value)

                            @if($value->walletowner)

                                <tr>
                                    <td>{{$value->walletowner->id}}</td>
                                    <td><a target="_blank" href="{{route('merchant.merchant.show',$value->walletowner->id)}}"> {{$value->walletowner->{'name_'.\DataLanguage::get()} }}</a></td>
                                    <td><a target="_blank" href = "{{route('system.staff.show',$value->walletowner->staff->id)}}">{{$value->walletowner->staff->Fullname}}</a></td>
                                    <td>{{$value->walletowner->created_at->diffForHumans()}}</td>
                                    <td> <button class="btn btn-primary" type="button" onclick='location = "{{route('system.wallet.show',$value->id)}}"'><i class="ft-eye"></i></button></td>
                                </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>