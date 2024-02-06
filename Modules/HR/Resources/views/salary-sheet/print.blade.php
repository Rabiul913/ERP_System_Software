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

        #ot_status_table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;

        }

        .delivery-order-details-table{
            border-collapse: collapse;
            width:100%;
        }

        #ot_status_table td {
            /* text-align: right; */
        }


        #ot_status_table td,
        #ot_status_table th {
            border: 1px solid black;
            padding: 5px;
        }

        #ot_status_table tr:nth-child(even) {
            /* background-color: #f2f2f2; */
        }

        #ot_status_table tr:hover {
            background-color: #ddd;
        }

        #ot_status_table th {
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

        #ot_status_table,
        #ot_status_table td,
        #ot_status_table th {
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
            margin: 150px 50px 90px 50px;
        }
    </style>
    <title>Salary Sheet</title>
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
                    Salary Sheet
                    {{-- date('d-m-Y', strtotime($user->from_date)); --}}
                </p>
                <p style="font-size: 12px; text-align: center; font-weight: bold; ">
                    {{-- Report of {{ date('d-M-Y', strtotime(Carbon\Carbon::now()->format('Y-m-d'))) }} --}}
                    Report of {{ \Carbon\Carbon::parse($month.'-1')->format('M-Y')   }}
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
            <table id="ot_status_table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Designation</th>
                        <th>Shift</th>
                        <th>Working Day</th>
                        <th>Working Amount</th>
                        <th>Late</th>
                        <th>OT Hour</th>
                        <th>OT Amount</th>
                        <th>Adjustment</th>
                        <th>House Rent</th>
                        <th>Medical Allowance</th>
                        <th>Transport Allowance</th>
                        <th>Food Allowance</th>
                        <th>Mobile Allowance</th>
                        <th>Other Allowance</th>
                        <th>Income Tax</th>
                        <th>Causal Salary</th>
                        <th>Total</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($reportData as $key => $data)
                    <tr>
                        <td>{{  $data->emp_code }}</td>
                        <td>{{  $data->emp_name }}</td>
                        <td>{{  $data->department_name }}</td>
                        <td>{{  $data->type }}</td>
                        <td>{{  $data->designation }}</td>
                        <td>{{  $data->shift_name }}</td>
                        <td class="text-right">{{  $data->total_working_day }}</td>
                        <td class="text-right">@money($data->total_working_amount)</td>
                        <td class="text-right">{{  $data->total_late_day }}</td>
                        <td class="text-right">{{  $data->total_ot_hour }}</td>
                        <td class="text-right">@money($data->total_ot_amount)</td>
                        <td class="text-right">@money($data->adjustment_amount ?? 0)</td>
                        <td class="text-right">@money($data->house_rent)</td>
                        <td class="text-right">@money($data->medical_allowance)</td>
                        <td class="text-right">@money($data->tansport_allowance)</td>
                        <td class="text-right">@money($data->food_allowance)</td>
                        <td class="text-right">@money($data->mobile_allowance)</td>
                        <td class="text-right">@money($data->other_allowance)</td>
                        <td class="text-right">@money($data->income_tax)</td>
                        <td class="text-right">@money($data->casual_salary)</td>
                        <td class="text-right">@money(round($data->total_working_amount + $data->total_ot_amount + $data->adjustment_amount + $data->house_rent + $data->medical_allowance + $data->tansport_allowance + $data->food_allowance + $data->mobile_allowance + $data->other_allowance - $data->income_tax +  $data->casual_salary))</td>
                    </tr>

                    @endforeach

                </tbody>

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
