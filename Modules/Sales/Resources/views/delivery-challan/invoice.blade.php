<!DOCTYPE html>
<html>

<head>
    @php
        $user = Auth::user();
        $companyData = DB::table('company_infos')
            ->where('com_id', $user->com_id)
            ->first();

        function getBangladeshCurrency($number)
        {
            $decimal = round($number - ($no = floor($number)), 2) * 100;
            $hundred = null;
            $digits_length = strlen($no);
            $i = 0;
            $str = [];
            $words = [
                0 => '',
                1 => 'one',
                2 => 'two',
                3 => 'three',
                4 => 'four',
                5 => 'five',
                6 => 'six',
                7 => 'seven',
                8 => 'eight',
                9 => 'nine',
                10 => 'ten',
                11 => 'eleven',
                12 => 'twelve',
                13 => 'thirteen',
                14 => 'fourteen',
                15 => 'fifteen',
                16 => 'sixteen',
                17 => 'seventeen',
                18 => 'eighteen',
                19 => 'nineteen',
                20 => 'twenty',
                30 => 'thirty',
                40 => 'forty',
                50 => 'fifty',
                60 => 'sixty',
                70 => 'seventy',
                80 => 'eighty',
                90 => 'ninety',
            ];
            $digits = ['', 'hundred', 'thousand', 'lakh', 'crore'];
            while ($i < $digits_length) {
                $divider = $i == 2 ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += $divider == 10 ? 1 : 2;
                if ($number) {
                    $plural = ($counter = count($str)) && $number > 9 ? 's' : null;
                    $hundred = $counter == 1 && $str[0] ? ' and ' : null;
                    $str[] = $number < 21 ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
                } else {
                    $str[] = null;
                }
            }
            $Taka = implode('', array_reverse($str));
            $poysa = $decimal ? ' and ' . ($words[$decimal / 10] . ' ' . $words[$decimal % 10]) . ' poysa' : '';
            return ($Taka ? $Taka . 'taka only' : '') . $poysa;
        }

        $total = 0;
    @endphp
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 20px !important;
            padding: 20px !important;

        }

        #orderinfo-table tr td {
            border: 1px solid #ffffff;
        }

        #orderinfo-table2 tr td {
            border: 1px solid #ffffff;
            text-align: left;
        }


        table {
            font-size: 10px;
        }

        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td {
            text-align: right;
        }


        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 5px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #0e2b4e;
            color: white;
        }

        p {
            margin: 0;
        }

        h1 {
            margin: 0;
        }

        h2 {
            margin: 0;
        }

        .container {
            margin: 20px;
        }

        .row {
            clear: both;
        }

        .head1 {
            width: 45%;
            float: left;
            margin: 0;
        }

        .head2 {
            width: 55%;
            float: left;
            margin: 0;
        }

        .head3 {
            width: 45%;
            float: left;
            padding-bottom: 20px;
        }

        .head4 {
            width: 45%;
            float: right;
            padding-bottom: 20px;
        }

        .textarea {
            width: 100%;
            float: left;
        }

        .text-center {
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-4xl {
            font-size: 1.5rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .text-base {
            font-size: 0.875rem;
        }

        .text-sm {
            font-size: 0.75rem;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .flex {
            display: flex;
        }

        .mt-12 {
            margin-top: 3rem;
        }

        .justify-between {
            justify-content: space-between;
        }

        .flex-col {
            flex-direction: column;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .justify-between {
            justify-content: space-between;
        }

        .font-semibold {
            font-weight: 600;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        table,
        td,
        th {
            padding: 5px;
            border-collapse: collapse;
            border: 1px solid #000;

        }

        .text-4xl {
            font-size: 24px;

        }

        .table-border-none td,
        .table-border-none tr,
        .table-border-none th,
        .table-border-none {
            border: none !important;
        }


        #invoice_table th {
            border: 1px solid #000;
        }

        #invoice_table {
            border-collapse: collapse;
            width: 100%;
        }

        #invoice_table td {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            border-bottom: 0px solid #fff;
            border-top: 0px solid #fff;
            padding: 15px;
        }

        #invoice_table tr {
            border-bottom: 0px solid #fff;
        }

        .row-invoice {
            display: flex;
            justify-content: space-between;
        }

        .invoice {
            width: 50%;
        }



        @page {
            header: page-header;
            footer: page-footer;
            margin: 120px 50px 50px 50px;
        }
    </style>
    <title>Delivery Challan</title>
</head>

<body>
    <htmlpageheader name="page-header">
        <div>
            &nbsp;
        </div>
        <div style="width: 20%; float:left;">
            <img class="float-right" style="height: 50px;width: 110px;"
                src="{{ asset('images/company_logo/' . $companyData->company_logo) }}" alt="Golden ispat Logo">
        </div>
        <div style="width: 60%; float:left;">
            <div style="margin-top: 20px;">
                <h1 style="font-size: 20px;  text-align: center">{{ $companyData->company_name }}</h1>
                <p style="font-size: 12px; text-align: center">{{ $companyData->primary_address }}</p>
                <p style="font-size: 12px; text-align: center">Phone: {{ $companyData->company_phone_1 }}</p>
                <p style="font-size: 12px; text-align: center; font-weight: bold;text-transform: uppercase; ">
                    Invoice
                </p>
            </div>
        </div>
        <div style="width: 20%; float:left;"></div>
    </htmlpageheader>


    <html-separator />
    {{-- <div class="flex mt-3">
        <div class="" style="width: 75%; float:left;">
            <h3 style="margin-bottom: 5px;">INV-{{ $deliveryChallan->id }}</h3>
            <span style="align-content: center; text-transform: uppercase; margin-top: 0px !important;">
                Buyer Info:</span>
            <p style="font-weight: bold;">{{ $deliveryChallan->customer->name }}</p>
            <p> {{ $deliveryChallan->customer->address }}</p>
            <p style="margin-bottom: 10px;"> {{ $deliveryChallan->customer->contact_no }}</p>

            <p>Date: <span style="font-weight: bold; ">{{ $deliveryChallan->delivery_date }}</span></p>

        </div>
        <div class="" style="width: 25%;">
            <span style="align-content: center; text-transform: uppercase; margin-top: 0px !important;">
                delivery Address:</span>
            <p style="font-weight: bold;">{{ $deliveryChallan->delivery_address }}</p>
        </div>

    </div> --}}

    <div class="flex mt-3" style="width: 100%;">
        <table style="width: 100%">
            <tr>
                <td width="50%">
                </td>
                <td style="padding: 10px;">
                    <table class="table-border-none">
                        <tr>
                            <td style="font-weight: bold;">Invoice No </td>
                            <td>:</td>
                            <td>INV-{{ $deliveryChallan->id }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;"> Invoice date :</td>
                            <td>:</td>
                            <td>{{ date('d-M-Y', strtotime($deliveryChallan->delivery_date)) }}</td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="table-border-none">
                        <tr>
                            <td style="font-weight: bold;">Buyer's Name </td>
                            <td>:</td>
                            <td>{{ $deliveryChallan->customer->name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;"> Address :</td>
                            <td>:</td>
                            <td>{{ $deliveryChallan->customer->address }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="table-border-none">
                        <tr>
                            <td style="font-weight: bold;">Delivery Address </td>
                            <td> : </td>
                            <td>{{ $deliveryChallan->delivery_address }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="" style="margin-top: 15px; ">
        <table id="invoice_table" >
            <thead>
                <tr>
                    <th>SL </th>
                    <th colspan="2">Description & Spacifications of Goods</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Unit Rate(Tk.)</th>
                    <th>Amount</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($deliveryChallan->deliveryChallanDetails as $key => $data)
                    <tr >
                        <td>{{ $key + 1 }}</td>
                        <td style="border-right: 0px solid #fff !important;">
                            <div>
                                <span>{{ $data->product->name }}</span> &nbsp; &nbsp;

                            </div>
                        </td>
                        <td style="border-left: 0px solid #fff !important;"> <span>
                                {{ $data->bundle_info }}
                            </span>
                        </td>

                        <td style="text-align:center;">{{ $data->measuringUnit->name }}</td>
                        <td>{{ $data->quantity }}</td>
                        <td>{{ $data->unit_price }}</td>
                        <td>{{ $data->unit_price * $data->quantity }}</td>
                    </tr>
                    @php
                        $total += $data->unit_price * $data->quantity;
                    @endphp
                @endforeach

                @for($i = 1; $i<= 18 - count($deliveryChallan->deliveryChallanDetails); $i++)
                <tr><td></td><td style="border-right: 0px solid #fff !important;"></td><td style="border-left: 0px solid #fff !important;"></td><td></td><td></td><td></td><td></td></tr>
                @endfor

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;">Total: </th>
                    <th colspan="2" style="text-align: right;">
                        {{ $deliveryChallan->deliveryChallanDetails->sum('quantity') }}
                    </th>
                    <th></th>
                    <th>
                        {{ $total }}
                    </th>
                </tr>
                <tr style="">
                    <th colspan="7" style="text-align: left;font-weight: 400 !important;">
                        Total In Word: {{ getBangladeshCurrency($total) }}
                    </th>
                </tr>
                <tr>
                    <th colspan="7" style="text-align: left;font-weight: 400 !important;">
                        Place Of Dispatch: {{ $deliveryChallan->delivery_address }}
                    </th>
                </tr>

            </tfoot>
        </table>
    </div>

    <htmlpagefooter name="page-footer">
        <div class="text-xs justify-between">
            <div>
                <div
                    style="width:48%; float:left; border: 1px solid black; padding: 50px 0px 5px 0px;  font-size:8pt !important">
                    <div>
                        <div class="text-center"> </div>
                        <div class="text-center">Buyer's Signature</div>
                    </div>
                </div>
                <div
                    style="width:48%; float:left; margin-left: 14px; border: 1px solid black; padding: 50px 0px 5px 0px;font-size:8pt !important">
                    <div>

                        <div class="text-center">Authorized Signatory</div>
                    </div>
                </div>
                {{-- <div
                    style="width:32%; float:left; margin-left: 14px; border: 1px solid black; padding: 50px 0px 5px 0px; font-size:8pt !important">
                    <div>

                        <div class="text-center">Client Sign</div>
                    </div>
                </div> --}}
            </div>
            <div>
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
            </div>
        </div>
    </htmlpagefooter>
    {{-- <div class="container">
        <div class="row" style="margin-top: 20px;">
            <p style="text-align: center;">If you have any questions about this purchase , Please contact <br> [Name,
                Phone #, E-mail]</p>
        </div>
    </div> --}}
</body>



</html>
