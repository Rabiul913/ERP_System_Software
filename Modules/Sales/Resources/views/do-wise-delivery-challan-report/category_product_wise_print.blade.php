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

        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;

        }

        .delivery-order-details-table{
            border-collapse: collapse;
            width:100%;
        }

        .customers td {
            /* text-align: right; */
        }


        .customers td,
        .customers th {
            border: 1px solid black;
            padding: 5px;
        }

        .customers tr:nth-child(even) {
            /* background-color: #f2f2f2; */
        }

        .customers tr:hover {
            background-color: #ddd;
        }

        .customers th {
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

        .customers,
        .customers td,
        .customers th {
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
    <title>DO Wise Delivery Challan Report</title>
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
                    {{ $search_criteria["Product"] != "All" ? "Product Wise " : ($search_criteria["Category"] != "All" ? "Category Wise " : '') }}DO Wise Delivery Challan Report ({{ $search_criteria["Product"] != "All" ? $search_criteria["Product"] : ($search_criteria["Category"] != "All" ? $search_criteria["Category"] : '') }})
                    {{-- date('d-m-Y', strtotime($user->from_date)); --}}
                </p>
                <p style="font-size: 12px; text-align: center; font-weight: bold;">
                    Report From {{ date('d-M-Y', strtotime($from_date)) }} - {{ date('d-M-Y', strtotime($to_date)) }}
                    {{-- date('d-m-Y', strtotime($user->from_date)); --}}
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


<div>
    @php
        $grand_total = 0;
    @endphp
    @php
        function gcd($x, $y)
        {
            if ($y == 0)
                return $x;
            return gcd($y, $x%$y);
        }
    @endphp
    @if(count($reportData))
        @foreach ($reportData as $key => $data)
        @php
            $sub_total = 0;
        @endphp
            <div>
                <h3>Product: {{ $data[0]->product?->name }}</h3>
                <div>
                    <table class="customers">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 7%;"> D/O No</th>
                                <th rowspan="2" style="width: 7%;">Date</th>
                                <th rowspan="2" style="width: 10%;">Customer Name</th>
                                <th rowspan="2" style="width: 10%;">Executive Name</th>
                                <th rowspan="2" style="width: 10%;"> Zone</th>

                                <th rowspan="2" style="width: 7%;"> Quantity</th>
                                <th rowspan="2" style="width: 7%;"> Unit Price</th>
                                {{-- <th colspan="5" rowspan="2">Product Details</th> --}}
                                <th rowspan="2" style="width: 10%;">Total Tk</th>
                                <th colspan="4" >Delivery Challan</th>
                            </tr>
                            <tr>
                                <th style="width: 8%;">D/C No</th>
                                <th style="width: 8%;">Delivery Date</th>

                                <th style="width: 8%;">Unit</th>
                                <th style="width: 8%;">Quantity</th>

                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($data as $deliveryOrderDetail)
                            @php
                                $x = count($deliveryOrderDetail->deliveryOrder?->deliveryChallans);
                                $y = 0;
                                foreach ($deliveryOrderDetail->deliveryOrder?->deliveryChallans as $deliveryChallan) {
                                    $y += count($deliveryChallan->deliveryChallanDetails->where('product_id', '=', $data[0]->product->id));
                                }

                                if($x == 0) $x = 1;
                                if($y == 0) $y = 1;


                                $rowspan = ($x*$y)/gcd($x,$y);
                                $delivery_challans_indx = 1;
                                $delivery_challan_details_indx = 1;
                            @endphp
                            <tr>
                                {{-- {{ dd("chk",$deliveryOrderDetail) }} --}}
                                <td rowspan="{{ $rowspan }}" class="text-center">DO-{{ $deliveryOrderDetail->deliveryOrder?->id }}</td>
                                <td rowspan="{{ $rowspan }}" class="text-center">{{ date('d-M-Y', strtotime($deliveryOrderDetail->deliveryOrder?->date)) }}</td>
                                <td rowspan="{{ $rowspan }}" class="">{{ $deliveryOrderDetail->deliveryOrder?->customer?->name }}</td>
                                <td rowspan="{{ $rowspan }}" class="">{{ $deliveryOrderDetail->deliveryOrder?->employee?->emp_name }}</td>
                                <td rowspan="{{ $rowspan }}" class="text-center">{{ $deliveryOrderDetail->deliveryOrder?->zone?->zone }}</td>

                                <td rowspan="{{ $rowspan }}" class="text-center">{{ $deliveryOrderDetail->quantity }}</td>
                                <td rowspan="{{ $rowspan }}" class="text-right">@money( $deliveryOrderDetail->unit_price )</td>
                                <td rowspan="{{ $rowspan }}" class="text-right">@money( $deliveryOrderDetail->quantity * $deliveryOrderDetail->unit_price )</td>
                                @php
                                    $sub_total += ($deliveryOrderDetail->quantity * $deliveryOrderDetail->unit_price);
                                @endphp
                                @if(count($deliveryOrderDetail->deliveryOrder?->deliveryChallans))
                                    <td rowspan="{{ $rowspan / $x }}" class="text-center">DCN-{{ $deliveryOrderDetail->deliveryOrder?->deliveryChallans[0]->id }}</td>
                                    <td rowspan= "{{ $rowspan / $x }}" class="text-center">{{ date('d-M-Y', strtotime($deliveryOrderDetail->deliveryOrder?->deliveryChallans[0]->delivery_date)) }}</td>
                                    @php
                                        $deliveryChallanDetails = $deliveryOrderDetail->deliveryOrder?->deliveryChallans[0]?->deliveryChallanDetails->where('product_id', '=', $data[0]->product->id)->values();
                                    @endphp
                                    @if(count($deliveryChallanDetails))
                                        <td rowspan= "{{ $rowspan / $y }}" class="text-center">{{ $deliveryChallanDetails[0]?->measuringUnit?->name }}</td>
                                        <td rowspan= "{{ $rowspan / $y }}" class="text-right">{{ $deliveryChallanDetails[0]?->quantity }}</td>
                                        @else
                                        <td rowspan= "{{ $rowspan / $y }}"></td>
                                        <td rowspan= "{{ $rowspan / $y }}"></td>
                                    @endif
                                @else
                                        <td rowspan= "{{ $rowspan / $x }}"></td>
                                        <td rowspan= "{{ $rowspan / $x }}"></td>
                                        {{-- <td rowspan= "{{ $rowspan / $y }}"></td> --}}
                                        <td rowspan= "{{ $rowspan / $y }}"></td>
                                        <td rowspan= "{{ $rowspan / $y }}"></td>
                                @endif
                            </tr>

                            @for ($m = 1; $m < $rowspan; $m++)
                            <tr>
                                @if(($m % $x == 0 || $x == $rowspan) && $delivery_challans_indx <= count($deliveryOrderDetail->deliveryOrder?->deliveryChallans) - 1)

                                    <td rowspan="{{ $rowspan / $x }}" class="text-center">DCN-{{ $deliveryOrderDetail->deliveryOrder?->deliveryChallans[$delivery_challans_indx]->id }}</td>
                                    <td rowspan= "{{ $rowspan / $x }}" class="text-center">{{ date('d-M-Y', strtotime($deliveryOrderDetail->deliveryOrder?->deliveryChallans[$delivery_challans_indx]->delivery_date)) }}</td>
                                    @php
                                        $delivery_challans_indx++;
                                        $delivery_challan_details_indx = 0;
                                    @endphp
                                @endif
                                @if(count($deliveryOrderDetail->deliveryOrder?->deliveryChallans))
                                @php
                                    $deliveryChallanDetails = $deliveryOrderDetail->deliveryOrder?->deliveryChallans[$delivery_challans_indx - 1]?->deliveryChallanDetails->where('product_id', '=', $data[0]->product->id)->values();
                                @endphp
                                    @if(($m % $y == 0 || $y == $rowspan) && $delivery_challan_details_indx <= count($deliveryChallanDetails) - 1)
                                    <td rowspan= "{{ $rowspan / $y }}" class="text-center">{{ $deliveryChallanDetails[$delivery_challan_details_indx]?->measuringUnit?->name }}</td>
                                    <td rowspan= "{{ $rowspan / $y }}" class="text-right">{{ $deliveryChallanDetails[$delivery_challan_details_indx]?->quantity }}</td>
                                        @php
                                            $delivery_challan_details_indx++;
                                        @endphp
                                    @endif
                                @endif



                            </tr>
                            @endfor
                            @endforeach

                            @php
                                $grand_total += $sub_total;
                            @endphp
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="7" class="text-right" style="width: 58%; font-weight:bold;">Sub-total</td>
                                <td style="width: 10%;" class="text-right">@money( $sub_total )</td>
                                <td style="width: 8%;"></td>
                                <td style="width: 8%;"></td>
                                <td style="width: 8%;"></td>
                                <td style="width: 8%;"></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        @endforeach
        <table class="customers" style="margin-top: 10px;">
            <thead>

            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="text-right" style="width: 58%; font-weight:bold;">Grand-total</td>
                    <td style="width:10%;" class="text-right">@money( $grand_total )</td>
                    <td style="width: 8%;"></td>
                    <td style="width: 8%;"></td>
                    <td style="width: 8%;"></td>
                    <td style="width: 8%;"></td>
                </tr>
            </tbody>

        </table>
    @else
        {{-- <h1 class="text-center" style="margin-top: 120px;">No results found</h1> --}}
        <div style="padding-top: 150px;">
            <h1 class="text-center" >No results found</h1>
        </div>
    @endif
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
