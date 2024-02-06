<!DOCTYPE html>
<html>

<head>
    @php
        $user = Auth::user();
        $companyData = DB::table('company_infos')
            ->where('com_id', $user->com_id)
            ->first();

        $totalQty = 0;

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
            return ($Taka ? $Taka . 'taka ' : '') . $poysa;
        }
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

        .delivery-order-details-table{
            border-collapse: collapse;
            width:100%;
        }

        #customers td {
            /* text-align: right; */
        }


        #customers td,
        #customers th {
            border: 1px solid black;
            padding: 5px;
        }

        #customers tr:nth-child(even) {
            /* background-color: #f2f2f2; */
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #ffffff;
        }

        /* table tfoot td {
            font-weight: bold;
            border: 1px solid #ffffff;
            background-color: #ffffff !important;
        } */

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

        #customers,
        #customers td,
        #customers th {
            padding: 5px;
            border-collapse: collapse;
            border: 1px solid black;

        }



        .text-4xl {
            font-size: 24px;

        }

        #orderinfo-table tr td {
            border: 1px solid #ffffff;
        }

        #orderinfo-table2 tr td {
            border: 1px solid #ffffff;
            text-align: left;
        }

        .search-criteria-container{
            font-size: 12px;
        }
        .search-criteria-container h6{
            font-size: 12px;
            margin: 0;
        }




        @page {
            header: page-header;
            footer: page-footer;
            /* margin: 120px 50px 50px 50px; */
            margin: 130px 50px 90px 50px;
        }
    </style>
    <title>Delivery Challan Report</title>
</head>

<body>
    <htmlpageheader name="page-header">
        <div>
            &nbsp;
        </div>
        <div style="width: 24%; float:left;">
            <img class="float-right" style="height: 50px;"
                src="{{ asset('images/company_logo/' . $companyData->company_logo) }}" alt="Golden ispat Logo">
        </div>
        <div style="width: 50%; float:left;">
            <div style="margin-top: 20px;">
                <h1 style="font-size: 20px;  text-align: center">{{ $companyData->company_name }}</h1>
                <p style="font-size: 12px; text-align: center">{{ $companyData->primary_address }}</p>
                <p style="font-size: 12px; text-align: center">Phone: {{ $companyData->company_phone_1 }}</p>
                <p style="font-size: 12px; text-align: center; font-weight: bold;text-transform: uppercase; ">
                    Delivery Challan Report
                    {{-- date('d-m-Y', strtotime($user->from_date)); --}}
                </p>
                <p style="font-size: 12px; text-align: center; font-weight: bold; ">
                    Report From {{ date('d-M-Y', strtotime($from_date)) }} - {{ date('d-M-Y', strtotime($to_date)) }}
                </p>

            </div>
        </div>
        <div style="width: 25%; float:left;">
            {{-- Print: {{ date('d-M-Y H:i:s', strtotime($print_date_time)) }} --}}
            Print: {{ $print_date_time }}
            <div class="search-criteria-container">
                <h6>Search Criteria:</h6>
                <div>
                    @foreach ($search_criteria as $key=>$sc)
                        <p>{{ $key }} : {{ $sc }}</p>
                    @endforeach
                </div>
            </div>

        </div>
    </htmlpageheader>

    <html-separator />



    <div class="container" >
        @if(count($reportData))
            <table id="customers">
                <thead>
                    <tr>
                        <th rowspan="2"> SL</th>
                        <th rowspan="2"> D/C No</th>
                        <th rowspan="2"> D/O No</th>
                        <th colspan="5">Product Details</th>
                        <th rowspan="2">Delivery Date</th>
                        <th rowspan="2">Customer Name</th>
                        <th rowspan="2">Executive Name</th>
                        <th rowspan="2"> Zone</th>
                        {{-- <th rowspan="2">Delivery Address</th> --}}
                        {{-- <th >D/O Qty(MT) </th> --}}
                        {{-- <th >Rate(Tk)</th> --}}
                        {{-- <th rowspan="2">Labor Cost</th>
                        <th rowspan="2">Rent</th> --}}
                        {{-- <th rowspan="2">Discount</th> --}}
                        {{-- <th rowspan="2">Total Tk</th> --}}
                        {{-- <th >Previous Due</th> --}}
                        {{-- <th rowspan="2">Collection</th> --}}
                        {{-- <th rowspan="2">Due</th> --}}
                        {{-- <th colspan="4">
                            Collections Amount
                        </th> --}}
                        {{-- <th >Previous adv. Agt D/O</th>
                        <th >Previous Dues Agt D/O</th>
                        <th >Advance From Party</th>
                        <th >Cradit Sales</th>
                        <th >Chq. In hand</th>
                        <th >Net Advance</th>
                        <th >Remarks</th> --}}

                    </tr>
                    <tr>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                    </tr>
                    {{-- <tr>
                        <th>Sales Person</th>
                        <th>Adjust</th>
                        <th>Bank/Cash</th>
                        <th>Bank Name</th>
                    </tr> --}}

                </thead>
                <tbody>

                    @foreach ($reportData as $key => $data)
                        <tr>
                            <td style="width: 7%;" rowspan={{ $data->deliveryChallanDetails->count()  }} class="text-center">{{ $key+1 }}</td>
                            <td style="width: 7%;" rowspan={{ $data->deliveryChallanDetails->count()  }} class="text-center">DCN-{{ $data->id }}</td>
                            <td style="width: 7%;" rowspan={{ $data->deliveryChallanDetails->count()  }} class="text-center">DO-{{ $data->delivery_order_id }}</td>
                            @if(count($data->deliveryChallanDetails))
                                <td style="width: 10%;">{{ $data->deliveryChallanDetails[0]->product?->category?->name }}</td>
                                <td style="width: 10%;">{{ $data->deliveryChallanDetails[0]->product?->name }}</td>
                                <td style="width: 7%;">{{ $data->deliveryChallanDetails[0]->productSize?->name }}</td>
                                <td style="width: 7%;">{{ $data->deliveryChallanDetails[0]->measuringUnit?->name }}</td>
                                <td style="width: 7%;" class="text-center">{{ $data->deliveryChallanDetails[0]->quantity }}</td>
                            @endif
                            <td style="width: 8%;" rowspan={{  $data->deliveryChallanDetails->count() }} class="text-center">{{  date('d-M-Y', strtotime($data->delivery_date)) }}</td>
                            <td style="width: 10%;" rowspan={{  $data->deliveryChallanDetails->count() }}>{{ $data->customer?->name }}</td>
                            <td style="width: 10%;" rowspan={{  $data->deliveryChallanDetails->count() }}>{{ $data->deliveryOrder?->employee?->emp_name }}</td>
                            <td style="width: 10%;" rowspan={{ $data->deliveryChallanDetails->count() }} class="text-center">{{ $data->deliveryOrder?->zone?->zone }}</td>

                            {{-- <td rowspan={{ $data->deliveryChallanDetails->count() }}>{{ $data->delivery_address }}</td> --}}
                            {{-- <td rowspan={{ $data->deliveryChallanDetails->count() }} class="text-right">{{ $data->labor_cost }}</td>
                            <td rowspan={{ $data->deliveryChallanDetails->count() }} class="text-right">{{ $data->rent }}</td> --}}
                            {{-- <td rowspan={{ $data->deliveryChallanDetails->count() }} class="text-right">{{ $data->discount }}</td> --}}
                            {{-- <td rowspan={{ $data->deliveryChallanDetails->count() }} class="text-right">{{ $data->total }}</td> --}}
                            {{-- <td></td> --}}
                            {{-- <td rowspan={{ $data->deliveryChallanDetails->count() }} class="text-right">{{ $data->paid }}</td> --}}
                            {{-- <td rowspan={{ $data->deliveryChallanDetails->count() }} class="text-right">{{ $data->due }}</td> --}}
                        </tr>
                        @foreach ($data->deliveryChallanDetails as $key2=>$item)
                        @if($key2 == 0) @continue @endif
                                                    <tr>
                                                        <td>{{ $item->product?->category?->name }}</td>
                                                        <td>{{ $item->product?->name }}</td>
                                                        <td>{{ $item->productSize?->name }}</td>
                                                        <td>{{ $item->measuringUnit?->name }}</td>
                                                        <td class="text-center">{{ $item->quantity }}</td>
                                                    </tr>
                        @endforeach
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        {{-- <td colspan="10" class="text-right" style="width: 90%; font-weight:bold;">Total</td> --}}
                        {{-- <td class="text-right">{{ $reportData->sum('labor_cost') }}</td>
                        <td class="text-right">{{ $reportData->sum('rent') }}</td> --}}
                        {{-- <td class="text-right">{{ $reportData->sum('discount') }}</td> --}}
                        {{-- <td class="text-right">{{ $reportData->sum('total') }}</td>
                        <td class="text-right">{{ $reportData->sum('paid') }}</td>
                        <td class="text-right">{{ $reportData->sum('due') }}</td> --}}
                        {{-- <td>{{ $data->deliveryOrderDetails->sum(function ($detail) {
                            return $detail->quantity * $detail->unit_price;
                        }) }}</td> --}}
                    </tr>
                </tfoot>
            </table>
        @else
            {{-- <h1 class="text-center" style="margin-top: 120px;">No results found</h1> --}}
            <div style="padding-top: 150px;">
                <h1 class="text-center" >No results found</h1>
            </div>
        @endif


        {{-- <div class="flex mt-12">
            <div style="width: 50%; float:left;">
                <table id="orderinfo-table">
                    <tr>
                        <td>
                            <p style="font-weight: bold">In Word:</p>
                        </td>
                        <td>
                            @if ($productPurchaseOrder->purchase_type == 'Foreign')
                                {{ getBangladeshCurrency($grandTotal) }}
                            @endif
                            {{ getBangladeshCurrency($total) }}
                        </td>
                    </tr>
                </table>

            </div>
        </div> --}}
    </div>

    <htmlpagefooter name="page-footer">
        <div class=" text-xs justify-between">
            <div>
                <div style="width:24%; float:left; margin-left: 5px; border: 1px solid black; padding: 50px 0px 5px 0px;">
                    <div>
                        <div class="text-center">Prepared By</div>
                    </div>
                </div>
                <div style="width:24%; float:left; margin-left: 5px; border: 1px solid black; padding: 50px 0px 5px 0px;">
                    <div>
                        <div class="text-center">General Manager</div>
                    </div>
                </div>
                <div style="width:24%; float:left; margin-left: 5px; border: 1px solid black; padding: 50px 0px 5px 0px;">
                    <div>
                        <div class="text-center">Director</div>
                    </div>
                </div>
                <div style="width:24%; float:left; margin-left: 5px; border: 1px solid black; padding: 50px 0px 5px 0px;">
                    <div>
                        <div class="text-center">Managing Director</div>
                    </div>
                </div>

            </div>
            <div>
                &nbsp;
            </div>
        </div>
    </htmlpagefooter>
</body>

</html>
