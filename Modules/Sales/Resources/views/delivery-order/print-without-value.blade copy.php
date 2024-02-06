<!DOCTYPE html>
<html>

<head>
    @php
        $user = Auth::user();
        $companyData = DB::table('company_infos')
            ->where('com_id', $user->com_id)
            ->first();
        
        $total = 0;
        $netTotal = 0;
        $grandTotal = 0;
    @endphp
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 20px !important;
            padding: 20px !important;
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

        table tfoot td {
            font-weight: bold;
            /* border: 1px solid #ffffff; */
            background-color: #ffffff !important;
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

        .d-none {
            display: none;
        }

        @page {
            header: page-header;
            footer: page-footer;
            margin: 120px 50px 50px 50px;
        }
    </style>
    <title>Delivery Order</title>
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
                    Delivery Order
                </p>
            </div>
        </div>
        <div style="width: 20%; float:left;"></div>
    </htmlpageheader>

    <html-separator />
    <div class="flex mt-3">

        <div class="" style="width: 75%; float:left;">
            <h3 style="margin-bottom: 5px;">DO-{{ $deliveryOrder->id }}</h3>
            <span style="align-content: center; text-transform: uppercase; margin-top: 0px !important;">
                Customer Info:</span>
            <p style="font-weight: bold;">{{ $deliveryOrder->customer->name }}</p>
            <p> {{ $deliveryOrder->customer->address }}</p>
            <p style="margin-bottom: 10px;"> {{ $deliveryOrder->customer->contact_no }}</p>
            <p>Date: <span style="font-weight: bold; ">{{ $deliveryOrder->date }}</span></p>


        </div>
        <div class="" style="width: 25%;">
            <span style="align-content: center; text-transform: uppercase; margin-top: 0px !important;">
                delivery Address:</span>
            <p style="font-weight: bold;">{{ $deliveryOrder->delivery_address }}</p>
            <p>Delivery Method: <span>{{ $deliveryOrder->delivery_method }}</span></p>
        </div>

    </div>



    <div style="margin-top: 15px;">
        <table id="customers">
            <thead>
                <tr>
                    <th>SL </th>
                    <th>Description </th>
                    <th>Unit </th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deliveryOrder->deliveryOrderDetails as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td style="text-align: left">{{ $data->product->name }}</td>
                        <td style="text-align: left">{{ $data->unit->name }}</td>
                        <td>{{ $data->quantity }}</td>
                    </tr>
                    @php
                        $total += $data->unit_price * $data->quantity;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                {{-- <tr>
                    <td colspan="5" style="text-align: right;">Total</td>
                    <td>
                        {{ $total }}

                    </td>
                </tr> --}}
                {{-- <tr>
                    <td colspan="5" style="text-align: right;"> + Vat</td>
                    <td>
                        {{ $deliveryOrder->vat }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right;"> + Tax</td>
                    <td>
                        {{ $deliveryOrder->tax }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right;"> + Rent</td>
                    <td>
                        {{ $deliveryOrder->rent }}
                    </td>
                </tr>
                @if ($deliveryOrder->labor > 0)
                    <tr>
                        <td colspan="5" style="text-align: right;"> + Labor</td>
                        <td>
                            {{ $deliveryOrder->labor }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="5" style="text-align: right;"> - Discount</td>
                    <td>
                        {{ $deliveryOrder->discount }}
                    </td>
                </tr> --}}
                {{-- <tr>
                    <td colspan="5" style="text-align: right;">Net Total</td>
                    <td>
                        {{ $netTotal = $total + $deliveryOrder->vat + $deliveryOrder->tax + $deliveryOrder->rent + $deliveryOrder->labor - $deliveryOrder->discount }}
                    </td>
                </tr> --}}
                {{-- <tr style="border-top: 1px solid black !important;">
                    <td colspan="5" style="text-align: right;"> Previous Due</td>
                    <td>
                        150
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right;"> Grand Total</td>
                    <td>
                        {{ $grandTotal = $total + 150 }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right;">Paid</td>
                    <td>
                        {{ $deliveryOrder->paid }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right;">Due</td>
                    <td>
                        {{ $deliveryOrder->due + 150 }}
                    </td>
                </tr> --}}
            </tfoot>
        </table>

    </div>

    <htmlpagefooter name="page-footer">
        <div class="text-xs justify-between">
            <div>
                <div
                    style="width:23%; float:left; border: 1px solid black; padding: 50px 0px 5px 0px;  font-size:8pt !important">
                    <div>
                        <div class="text-center"> </div>
                        {{-- <hr class="w-32 border-gray-700" /> --}}
                        <div class="text-center">Prepared By</div>
                    </div>
                </div>
                <div
                    style="width:23%; float:left; margin-left: 14px; border: 1px solid black; padding: 50px 0px 5px 0px;font-size:8pt !important">
                    <div>

                        {{-- <hr class="w-32 border-gray-700" /> --}}
                        <div class="text-center">General Manager</div>
                    </div>
                </div>
                <div
                    style="width:23%; float:left; margin-left: 14px; border: 1px solid black; padding: 50px 0px 5px 0px; font-size:8pt !important">
                    <div>

                        {{-- <hr class="w-32 border-gray-700" /> --}}
                        <div class="text-center">Director</div>
                    </div>
                </div>
                <div
                    style="width:23%; float:left; margin-left: 14px; border: 1px solid black; padding: 50px 0px 5px 0px; font-size:8pt !important">
                    <div>
                        <div class="text-center"></div>
                        {{-- <hr class="w-32 border-gray-700" /> --}}
                        <div class="text-center">Manageing Director</div>
                    </div>
                </div>

            </div>
            <div>
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
