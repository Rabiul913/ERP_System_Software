<!DOCTYPE html>
<html>

<head>
    @php
        $user = Auth::user();
        $companyData = DB::table('company_infos')
            ->where('com_id', $user->com_id)
            ->first();
        
        $total = 0;
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
                    Sales Order
                </p>
            </div>
        </div>
        <div style="width: 20%; float:left;"></div>
    </htmlpageheader>

    <html-separator />
    <div class="flex mt-3">
        <div class="head3" style=" padding: 10px;">
            <h5 style="align-content: center; text-transform: uppercase; margin-top: 0px !important;">
                Customer Info:</h5>
            <p style="font-weight: bold;">{{ $salesOrder->customer->name }}</p>
            <p> {{ $salesOrder->customer->address }}</p>

        </div>
        <div class="head2"></div>

    </div>



    <div class="container">
        <table id="customers">
            <thead>
                <tr>
                    <th>SL </th>
                    <th>Description </th>
                    <th>Unit Rate </th>
                    <th>Quantity</th>
                    <th>Total Price </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salesOrder->salesOrderDetails as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $data->product->name }}</td>
                        <td>{{ $data->unit_price }}</td>
                        <td>{{ $data->quantity }}</td>
                        <td>{{ $data->unit_price * $data->quantity }} </td>
                    </tr>



                    @php
                        $total += $data->unit_price * $data->quantity;
                    @endphp
                @endforeach


            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">Total</td>
                    <td>
                        {{ $total }}

                    </td>
                </tr>

            </tfoot>
        </table>

    </div>

    <htmlpagefooter name="page-footer">
        <div class=" text-xs justify-between">
            <div>
                <div style="width:24%; float:left; margin-left: 5px;">
                    <div>
                        <div class="text-center"> </div>
                        <hr class="w-32 border-gray-700" />
                        <div class="text-center">Prepared By</div>
                    </div>
                </div>
                <div style="width:24%; float:left; margin-left: 5px;">
                    <div>

                        <hr class="w-32 border-gray-700" />
                        <div class="text-center">Client Sign</div>
                    </div>
                </div>
                <div style="width:24%; float:left; margin-left: 5px;">
                    <div>

                        <hr class="w-32 border-gray-700" />
                        <div class="text-center">Driver Sign</div>
                    </div>
                </div>
                <div style="width:24%; float:left; margin-left: 5px;">
                    <div>
                        <div class="text-center"></div>
                        <hr class="w-32 border-gray-700" />
                        <div class="text-center">MD</div>
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
