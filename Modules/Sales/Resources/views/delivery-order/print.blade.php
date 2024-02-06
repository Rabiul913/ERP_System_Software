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
            /* margin: 120px 50px 50px 50px; */
        }
    </style>
    <title>Delivery Challan</title>
</head>

<body>
    <!-- LEft Section -->
    <div style="width:45%; float:left; border-right: 1px solid black; padding-right:60px; height:100%">
        <div style="text-align:center">
            <img class="float-right" style="height: 50px;width: 50%;" src="{{ asset('images/company_logo/' . $companyData->company_logo) }}" alt="Golden ispat Logo">
            <p style="font-size: 12px; text-align: center">{{ $companyData->primary_address }}</p>
            <p style="font-size: 12px; text-align: center">Phone: {{ $companyData->company_phone_1 }}</p>
            <p style="font-size: 12px; text-align: center; font-weight: bold;text-transform: uppercase; margin-bottom:10px; margin-top:10px; ">
                Delivery Order
            </p>
        </div>
        <div style="display: flex; flex-direction:row;">
            <div style="width:35%; text-align:left; float:left; border:1px solid black; padding:5px; height:100px;font-size:8pt;">
                <p>Date: <span style="font-weight: bold; ">{{ $deliveryOrder->date }}</span></p>
                <p>Order No: <span style="font-weight: bold; ">DO-{{ $deliveryOrder->id }}</span></p>
                <p style="align-content: center;  margin-top: 0px !important;">
                    Customer Info: <span style="font-weight: bold;">{{ $deliveryOrder->customer->name }}</span></p>
                <p> {{ $deliveryOrder->customer->address }}</p>
                <p> {{ $deliveryOrder->customer->contact_no }}</p>
            </div>
            <div style="width: 20%; float:left; margin-left:13px">
                <div style="border: 1px solid black; text-align:center; padding:15px; font-size:10pt">
                    <p> {{ $deliveryOrder->delivery_method }}</p>
                </div>
            </div>
            <div style="width:35%; text-align:right;float:right; border:1px solid black; padding:5px; height:100px; font-size:8pt;">
                <p style="align-content: center; margin-top: 0px !important;">
                    Delivery Address: <span style="font-weight: bold;">{{ $deliveryOrder->delivery_address }}</span></p>

                <p>Vehicle No: <span style="font-weight: bold;">{{ $deliveryOrder->vehicle_no }}</span></p>
                <p>Sales Person : <span>{{ $deliveryOrder->employee->emp_name }}</span></p>
                <p>Mobile Number : <span>{{ $deliveryOrder->employee->phone_1 }}</span></p>
            </div>
        </div>

        <div class="" style="margin-top: 15px;height:50%">
            <table id="customers" style="font-size:8pt !important;">
                <thead>
                    <tr>
                        <th>SL </th>
                        <th>Description </th>

                        <th>Unit </th>
                        <th>Rate </th>
                        <th>Quantity</th>
                        <th>Total Price </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deliveryOrder->deliveryOrderDetails as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $data->product->name }}</td>
                        <td>{{ $data->product->measuringunit->name }}</td>
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
                        <td colspan="5" style="text-align: right;">Total</td>
                        <td>
                            {{ $total }}

                        </td>
                    </tr>
                    {{-- <tr>
                    <td colspan="4" style="text-align: right;"> + Vat</td>
                    <td>
                        {{ $deliveryOrder->vat }}
                    </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;"> + Tax</td>
                        <td>
                            {{ $deliveryOrder->tax }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;"> + Rent</td>
                        <td>
                            {{ $deliveryOrder->rent }}
                        </td>
                    </tr>
                    @if ($deliveryOrder->labor > 0)
                    <tr>
                        <td colspan="4" style="text-align: right;"> + Labor</td>
                        <td>
                            {{ $deliveryOrder->labor }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="4" style="text-align: right;"> - Discount</td>
                        <td>
                            {{ $deliveryOrder->discount }}
                        </td>
                    </tr> --}}
                    {{-- <tr>
                    <td colspan="4" style="text-align: right;">Net Total</td>
                    <td>
                        {{ $netTotal = $total + $deliveryOrder->vat + $deliveryOrder->tax + $deliveryOrder->rent + $deliveryOrder->labor - $deliveryOrder->discount }}
                    </td>
                    </tr> --}}
                    <tr style="border-top: 1px solid black !important;">
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
                    </tr>
                </tfoot>
            </table>

        </div>

        <div class="text-xs justify-between">
            <div>
                <div style="width:23%; float:left; border: 1px solid black; padding: 50px 0px 5px 0px;  font-size:7px !important">
                    <div>
                        <div class="text-center"> </div>
                        <div class="text-center">Prepared By</div>
                    </div>
                </div>
                <div style="width:23%; float:left; margin-left: 14px; border: 1px solid black; padding: 50px 0px 5px 0px;font-size:7px !important">
                    <div>
                        <div class="text-center">General Manager</div>
                    </div>
                </div>
                <div style="width:23%; float:left; margin-left: 14px; border: 1px solid black; padding: 50px 0px 5px 0px; font-size:7px !important">
                    <div>
                        <div class="text-center">Director</div>
                    </div>
                </div>
                <div style="width:23%; float:left; margin-left: 14px; border: 1px solid black; padding: 35px 0px 5px 0px; font-size:7px !important">
                    <div>
                        <div class="text-center">Managing Director</div>
                    </div>
                </div>

            </div>
            <div>
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
            </div>
        </div>
    </div>


    <!-- Right Section -->
    <div style="width:45%; float:right; ">
        <div style="text-align:center">
            <img class="float-right" style="height: 50px;width: 50%;" src="{{ asset('images/company_logo/' . $companyData->company_logo) }}" alt="Golden ispat Logo">
            <p style="font-size: 12px; text-align: center">{{ $companyData->primary_address }}</p>
            <p style="font-size: 12px; text-align: center">Phone: {{ $companyData->company_phone_1 }}</p>
            <p style="font-size: 12px; text-align: center; font-weight: bold;text-transform: uppercase; margin-bottom:10px; margin-top:10px; ">
                Delivery Order
            </p>
        </div>
        <div style="display: flex; flex-direction:row;">
            <div style="width:35%; text-align:left; float:left; border:1px solid black; padding:5px; height:100px;font-size:8pt;">
                <p>Date: <span style="font-weight: bold; ">{{ $deliveryOrder->date }}</span></p>
                <p>Order No: <span style="font-weight: bold; ">DO-{{ $deliveryOrder->id }}</span></p>
                <p style="align-content: center;  margin-top: 0px !important;">
                    Customer Info: <span style="font-weight: bold;">{{ $deliveryOrder->customer->name }}</span></p>
                <p> {{ $deliveryOrder->customer->address }}</p>
                <p> {{ $deliveryOrder->customer->contact_no }}</p>
            </div>
            <div style="width: 20%; float:left; margin-left:13px">
                <div style="border: 1px solid black; text-align:center; padding:15px; font-size:10pt">
                    <p> {{ $deliveryOrder->delivery_method }}</p>
                </div>
            </div>
            <div style="width:35%; text-align:right;float:right; border:1px solid black; padding:5px; height:100px; font-size:8pt;">
                <p style="align-content: center; margin-top: 0px !important;">
                    Delivery Address: <span style="font-weight: bold;">{{ $deliveryOrder->delivery_address }}</span></p>

                <p>Vehicle No: <span style="font-weight: bold;">{{ $deliveryOrder->vehicle_no }}</span></p>
                <p>Sales Person : <span>{{ $deliveryOrder->employee->emp_name }}</span></p>
                <p>Mobile Number : <span>{{ $deliveryOrder->employee->phone_1 }}</span></p>
            </div>
        </div>

        <div class="" style="margin-top: 15px;height:50%">
            <table id="customers" style="font-size:8pt !important;">
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
                        <td>{{ $data->product->measuringunit->name }}</td>
                        <td>{{ $data->quantity }}</td>
                    </tr>
                    @php
                    $total += $data->unit_price * $data->quantity;
                    @endphp
                    @endforeach
                </tbody>

            </table>

        </div>

        <div class="text-xs justify-between">
            <div>
                <div style="width:23%; float:left; border: 1px solid black; padding: 50px 0px 5px 0px;  font-size:7px !important">
                    <div>
                        <div class="text-center"> </div>
                        <div class="text-center">Prepared By</div>
                    </div>
                </div>
                <div style="width:23%; float:left; margin-left: 14px; border: 1px solid black; padding: 50px 0px 5px 0px;font-size:7px !important">
                    <div>
                        <div class="text-center">General Manager</div>
                    </div>
                </div>
                <div style="width:23%; float:left; margin-left: 14px; border: 1px solid black; padding: 50px 0px 5px 0px; font-size:7px !important">
                    <div>
                        <div class="text-center">Director</div>
                    </div>
                </div>
                <div style="width:23%; float:left; margin-left: 14px; border: 1px solid black; padding: 35px 0px 5px 0px; font-size:7px !important">
                    <div>
                        <div class="text-center">Managing Director</div>
                    </div>
                </div>

            </div>
            <div>
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
            </div>
        </div>
    </div>


</body>

</html>
