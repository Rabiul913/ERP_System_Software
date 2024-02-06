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
                    DO Wise Delivery Challan Report
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

    @php
        function gcd($x, $y)
        {
            if ($y == 0)
                return $x;
            return gcd($y, $x%$y);
        }
    @endphp



    <div class="container" >
        @if(count($reportData))
            <table id="customers">
                <thead>
                    <tr>
                        <th rowspan="3"> D/O No</th>
                        <th rowspan="3">Date</th>
                        <th rowspan="3">Customer Name</th>
                        <th rowspan="3">Executive Name</th>
                        <th rowspan="3"> Zone</th>
                        <th colspan="4" rowspan="2">Product Details</th>
                        <th rowspan="3">Total Tk</th>
                        <th rowspan="3">Collection</th>
                        <th rowspan="3">Due</th>
                        <th colspan="6">Delivery Challan</th>
                    </tr>
                    <tr>
                        <th rowspan="2">D/C No</th>
                        <th rowspan="2">Delivery Date</th>
                        <th colspan="4">Product Details</th>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <th>Product</th>

                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Category</th>
                        <th>Product</th>

                        <th>Unit</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($reportData as $key => $data)
                    {{-- @if($data->id != 12) @continue @endif --}}
                        <tr>
                            @php
                                $x = count($data->deliveryOrderDetails);
                                $y = count($data->deliveryChallans);
                                $z = 0;

                                foreach ($data->deliveryChallans as $challan) {
                                    $z += count($challan->deliveryChallanDetails);
                                }
                                if($y == 0) $y = 1;
                                if($z == 0) $z = 1;


                                $lcm_xy = ($x*$y)/gcd($x,$y);
                                $rowspan = ($lcm_xy*$z)/gcd($lcm_xy,$z);

                                $delivery_order_details_rowspan = $rowspan / $x;
                                $delivery_challans_rowspan = $rowspan / $y;
                                $delivery_challan_details_rowspan = $rowspan / $z;

                                $delivery_order_details_indx = 1;
                                $delivery_challans_indx = 1;
                                $delivery_challan_details_indx = 1;
                            @endphp
                            <td rowspan="{{ $rowspan }}" class="text-center">DO-{{ $data->id }}</td>
                            <td rowspan="{{  $rowspan }}" class="text-center">{{  date('d-M-Y', strtotime($data->date)) }}</td>
                            <td rowspan="{{  $rowspan }}">{{ $data->customer?->name }}</td>
                            <td rowspan="{{  $rowspan }}">{{ $data->employee?->emp_name }}</td>
                            <td rowspan="{{ $rowspan }}" class="text-center">{{ $data->zone?->zone }}</td>
                            @if(count($data->deliveryOrderDetails))
                                <td rowspan="{{ $rowspan / $x }}">{{ $data->deliveryOrderDetails[0]->product?->category?->name }}</td>
                                <td rowspan= "{{ $rowspan / $x }}">{{ $data->deliveryOrderDetails[0]->product?->name }}</td>

                                <td rowspan= "{{ $rowspan / $x }}" class="text-center">{{ $data->deliveryOrderDetails[0]->quantity }}</td>
                                <td rowspan= "{{ $rowspan / $x }}" class="text-right">@money( $data->deliveryOrderDetails[0]->unit_price )</td>
                            @endif
                            <td rowspan="{{ $rowspan }}" class="text-right">@money( $data->total )</td>
                            <td rowspan="{{ $rowspan }}" class="text-right">@money( $data->paid )</td>
                            <td rowspan="{{ $rowspan }}" class="text-right">@money( $data->due )</td>
                            @if(count($data->deliveryChallans))
                                <td rowspan="{{ $rowspan / $y }}">DCN-{{ $data->deliveryChallans[0]->id }}</td>
                                <td rowspan= "{{ $rowspan / $y }}">{{ date('d-M-Y', strtotime($data->deliveryChallans[0]->delivery_date)) }}</td>
                                @if(count($data->deliveryChallans[0]?->deliveryChallanDetails))
                                <td rowspan= "{{ $rowspan / $z }}">{{ $data->deliveryChallans[0]?->deliveryChallanDetails[0]?->product?->category?->name }}</td>
                                <td rowspan= "{{ $rowspan / $z }}">{{ $data->deliveryChallans[0]?->deliveryChallanDetails[0]?->product?->name }}</td>

                                <td rowspan= "{{ $rowspan / $z }}">{{ $data->deliveryChallans[0]?->deliveryChallanDetails[0]?->measuringUnit?->name }}</td>
                                <td rowspan= "{{ $rowspan / $z }}" class="text-right">{{ $data->deliveryChallans[0]?->deliveryChallanDetails[0]?->quantity }}</td>
                                @endif
                                @else
                                <td rowspan= "{{ $rowspan / $y }}"></td>
                                <td rowspan= "{{ $rowspan / $y }}"></td>
                                <td rowspan= "{{ $rowspan / $z }}"></td>
                                <td rowspan= "{{ $rowspan / $z }}"></td>

                                <td rowspan= "{{ $rowspan / $z }}"></td>
                                <td rowspan= "{{ $rowspan / $z }}"></td>
                            @endif

                        </tr>
                        @for ($m = 1; $m < $rowspan; $m++)
                        <tr>
                            @if(($m % $x == 0 || $x == $rowspan) && $delivery_order_details_indx <= count($data->deliveryOrderDetails) - 1)

                                <td rowspan="{{ $rowspan / $x }}">{{ $data->deliveryOrderDetails[$delivery_order_details_indx]->product?->category?->name }}</td>
                                <td rowspan= "{{ $rowspan / $x }}">{{ $data->deliveryOrderDetails[$delivery_order_details_indx]->product?->name }}</td>

                                <td rowspan= "{{ $rowspan / $x }}" class="text-center">{{ $data->deliveryOrderDetails[$delivery_order_details_indx]->quantity }}</td>
                                <td rowspan= "{{ $rowspan / $x }}" class="text-right">@money( $data->deliveryOrderDetails[$delivery_order_details_indx]->unit_price )</td>
                                @php
                                    $delivery_order_details_indx++;
                                @endphp
                            @endif

                            @if(($m % $y == 0 || $y == $rowspan) && $delivery_challans_indx <= count($data->deliveryChallans) - 1)

                                <td rowspan="{{ $rowspan / $y }}">DCN-{{ $data->deliveryChallans[$delivery_challans_indx]->id }}</td>
                                <td rowspan= "{{ $rowspan / $y }}">{{ date('d-M-Y', strtotime($data->deliveryChallans[$delivery_challans_indx]->delivery_date)) }}</td>
                                @php
                                    $delivery_challans_indx++;
                                    $delivery_challan_details_indx = 0;
                                @endphp
                            @endif
                            @if(count($data->deliveryChallans))
                                @if(($m % $z == 0 || $z == $rowspan) && $delivery_challan_details_indx <= count($data->deliveryChallans[$delivery_challans_indx - 1]?->deliveryChallanDetails) - 1)
                                <td rowspan= "{{ $rowspan / $z }}">{{ $data->deliveryChallans[$delivery_challans_indx - 1]?->deliveryChallanDetails[$delivery_challan_details_indx]?->product?->category?->name }}</td>
                                <td rowspan= "{{ $rowspan / $z }}">{{ $data->deliveryChallans[$delivery_challans_indx - 1]?->deliveryChallanDetails[$delivery_challan_details_indx]?->product?->name }}</td>

                                <td rowspan= "{{ $rowspan / $z }}">{{ $data->deliveryChallans[$delivery_challans_indx - 1]?->deliveryChallanDetails[$delivery_challan_details_indx]?->measuringUnit?->name }}</td>
                                <td rowspan= "{{ $rowspan / $z }}" class="text-right">{{ $data->deliveryChallans[$delivery_challans_indx - 1]?->deliveryChallanDetails[$delivery_challan_details_indx]?->quantity }}</td>
                                    @php
                                        $delivery_challan_details_indx++;
                                    @endphp
                                @endif
                            @endif



                        </tr>
                        @endfor


                        {{-- @foreach ($data->deliveryOrderDetails as $key2=>$item)
                            @if($key2 == 0) @continue @endif
                            <tr>
                                <td rowspan={{ $rowspan / $x }}>{{ $item->product?->category?->name }}</td>
                                <td rowspan={{ $rowspan / $x }}>{{ $item->product?->name }}</td>

                                <td rowspan={{ $rowspan / $x }} class="text-center">{{ $item->quantity }}</td>
                                <td rowspan={{ $rowspan / $x }} class="text-right">{{ $item->unit_price }}</td>
                            </tr>
                        @endforeach --}}
                        @php
                            // dd($data);
                        @endphp
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="9" class="text-right" style="font-weight:bold;">Total</td>
                        <td class="text-right">@money( $reportData->sum('total') )</td>
                        <td class="text-right">@money( $reportData->sum('paid') )</td>
                        <td class="text-right">@money( $reportData->sum('due') )</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        @else
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
