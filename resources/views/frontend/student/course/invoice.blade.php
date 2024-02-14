<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ __('Invoice') }}</title>
    <link href="{{ asset('frontend/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

</head>
<body>

<div style="background-color: #ffffff;  margin-left: auto; margin-right: auto; box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);">
    <table style="border-collapse: collapse; width: 100%; font-family: 'Arial', sans-serif">
        <tbody>
        <tr>
            <td style="padding: 16px;">
                <!--====== Clear fix ===========-->
                <div style="display: block; clear: both;"></div>

                <div style="display: block">
                    <div style="margin: 0 0 1.5rem 0; display: inline-block;">
                        <h4 style="margin: 0 0 1rem 0; color: #666666">{{ __('Purchase Invoice') }}</h4>
                        <p style="margin: 4px 0; color: #666666; font-size: 14px;">{{get_option('app_name')}}</p>
                        <p style="margin: 4px 0; color: #666666; font-size: 14px;">{{get_option('app_email')}}</p>
                        <p style="margin: 4px 0; color: #666666; font-size: 14px;">{{get_option('app_contact_number')}}</p>
                    </div>

                    <div style="margin: 0 0 1.5rem 0; display: inline-block; float: right">
                        <h2 style="margin: 8px 0; color: #666666; font-size: 24px;">#{{$item->order->order_number}}</h2>
                        <p style="margin: 4px 0; color: #666666; font-size: 14px;">{{ __('Purchase Date') }} //</p>
                        <p style="margin: 4px 0; color: #666666; font-size: 16px;">{{$item->created_at->format(get_option('app_date_format'))}}</p>
                    </div>
                </div>
                <!--====== Clear fix ===========-->
                <div style="display: block; clear: both;"></div>

                <!--================= Table ====================-->
                <table style="border-collapse: collapse;  width: 100%; font-size: 14px; margin-top: 25px; box-sizing: border-box">
                    <thead>
                    <tr style="background-color: #D5D7DC">
                        <th style="font-weight: normal; text-align: left; padding: 12px 16px">{{ __('Name') }}</th>
                        <th style="font-weight: normal; text-align: left; padding: 12px 16px">{{ __('Purchase Date') }}</th>
                        <th style="font-weight: normal; text-align: left; padding: 12px 16px">{{ __('Payment Method') }}</th>
                        <th style="font-weight: normal; text-align: center; padding: 12px 16px">{{ __('Amount') }}</th>
                    </tr>
                    </thead>
                    <tbody style="color: #444444">
                    <tr style="border-bottom: 1px solid #dddddd">
                        <td style="text-align: left; padding: 12px 16px">
                            {{$item->course ? @$item->course->title  : '' }}
                            {{$item->product_id ? @$item->product->title  : '' }}
                            {{$item->consultationSlot ? @$item->consultationSlot->user->instructor->full_name  : '' }}

                        </td>
                        <td style="text-align: left; padding: 12px 16px">{{$item->created_at->format(get_option('app_date_format'))}}</td>
                        <td style="text-align: left; padding: 12px 16px">{{ucwords($item->order->payment_method)}}</td>
                        <td style="text-align: center; padding: 12px 16px">
                            @if(get_currency_placement() == 'after')
                                {{$item->unit_price}} {{ get_currency_code() }}
                            @else
                                {{ get_currency_code() }} {{$item->unit_price}}
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td style="padding: 24px 16px;">

                <p style="margin-bottom: 12px; margin-top: 0; text-align: center; color: #666666">{{ __('We thank you for your business and continued use of') }} {{get_option('app_name')}}</p>
                <h2 style="text-align: center; color: #666666; font-weight: 600; margin-top: 0; margin-bottom: 0">Thank you from {{get_option('app_name')}} family</h2>
                <button style="" type="button" onclick="" class="btn btn-warning btn-icon icon-left float-end print-button"><i class="fas fa-print"></i> Print</button>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
<script src="{{ asset('frontend/assets/vendor/jquery/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

<script>
    printDiv()
    function printDiv(){
        $(".print-button").hide();

        var DocumentContainer = document.getElementById('print-div');
        var WindowObject = window.open('', "PrintWindow", "width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
        WindowObject.document.writeln(DocumentContainer.innerHTML);
        WindowObject.document.close();
        WindowObject.focus();
        WindowObject.print();
        WindowObject.close();
    }
</script>
</body>
</html>
