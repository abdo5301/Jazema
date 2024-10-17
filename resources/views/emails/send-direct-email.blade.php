@component('mail::message')
# {{__('Daily Summary Report For')}} {{$data['date']}}

@component('mail::table')
    | {{__('Key')}} | {{__('Value')}} |
    | ------------- |:-------------:|
    | {{__('New Merchants (count)')}} | {{$data['new_merchants']}} |
    | {{__('Bee Balance')}} | {{$data['sdk_wallet']}} |
    | {{__('Momkn Balance')}} | {{$data['momkn_wallet']}} |
    | {{__('Bee Actual Balance')}} | {{$data['actual_balance']}} |
    | {{__('Supervisors Wallet Balance')}} | {{$data['supervisor_wallets']}} |
    | {{__('Sales Wallet Balance')}} | {{$data['sales_wallets']}} |
    | {{__('Merchants Wallet Balance')}} | {{$data['merchant_wallets']}} |
    | {{__('Total Invoices (amount)')}} | {{$data['invoices_amount']}} |
    | {{__('Total Invoices (count)')}} | {{$data['invoices_count']}} |
    | {{__('System Commission')}} | {{$data['system_commission']}} |
    | {{__('Merchants Commission')}} | {{$data['merchant_commission']}} |
@endcomponent


{{__('Thanks')}},<br>
{{ config('app.name') }} {{__('Team')}}
@endcomponent
