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

        #allowances {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;

        }

        .delivery-order-details-table{
            border-collapse: collapse;
            width:100%;
        }

        #allowances td {
            /* text-align: right; */
        }


        #allowances td,
        #allowances th {
            border: 1px solid black;
            padding: 5px;
        }

        #allowances tr:nth-child(even) {
            /* background-color: #f2f2f2; */
        }

        #allowances tr:hover {
            background-color: #ddd;
        }

        #allowances th {
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


        .flex-col {
            flex-direction: column;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .display {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            margin-left:20%;
            margin-right:20%;
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

        #allowances,
        #allowances td,
        #allowances th {
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
            margin: 130px 80px 90px 80px;
        }
    </style>
    <title>Pay Slip</title>
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
                    Pay Slip  {{($type == 'salary')? '(Salary)': '(Bonus)'}}  
                    {{-- date('d-m-Y', strtotime($user->from_date)); --}}
                </p>
                <p style="font-size: 12px; text-align: center; font-weight: bold; ">
                    {{date('F-Y', strtotime($dateString))}}
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
        <?php $sl=0; ?>
            
            @foreach ($reportData as $key => $data)
                
                <?php                
                    
                    if($type == 'salary'){
                        ['total_working_day' => $total_working_day, 'total_working_amount' => $total_working_amount, 'ot_hour'=> $total_ot_hour,'ot_amount'=>$total_ot_amount,'house_rent'=>$house_rent,'medical_allowance'=>$medical_allowance,
                        'tansport_allowance'=>$tansport_allowance,
                        'food_allowance'=>$food_allowance,
                        'other_allowance'=>$other_allowance,
                        'mobile_allowance'=>$mobile_allowance,
                        'grade_bonus'=>$grade_bonus,
                        'skill_bonus'=>$skill_bonus,
                        'management_bonus'=>$management_bonus,
                        'income_tax'=>$income_tax,
                        'casual_salary'=>$casual_salary,
                        'total_leaves'=>$total_leaves,
                        'adjustment_amount'=>$adjustment_amount] = $data->processed_salary;
       
                        $addition=0;
                        $deduction=0;
                        if ($adjustment_amount > 0) {
                            $addition= $adjustment_amount;
                        }else{
                            $deduction= (-1 * $adjustment_amount);
                        }
                        $total_earnings = $total_working_amount + $total_ot_amount + $house_rent + $medical_allowance + $tansport_allowance + $food_allowance + $other_allowance + $mobile_allowance + $casual_salary + $addition;
    
                        $total_deductions = $income_tax + $deduction;
                    }

                    $total_bonus=0;
                    
                ?>
                <div style="margin-left: 20%; margin-right:20%;" class="px-4">
                    <table id="allowances" >
                        <tbody>
                            <tr>
                                <td>Employee Name:</td>
                                <td>{{ $data->emp_name }}</td>
                                <td>Employee Code:</td>
                                <td>{{ $data->emp_code }}</td>
                            </tr>
                            <tr>
                                <td>Department:</td>
                                <td>{{ $data->department->name }}</td>
                                <td>Designation:</td>
                                <td>{{ $data->designation->name }}</td>
                            </tr>
                            @if ($type == 'salary')                                
                                <tr>
                                    <td>Total Working Days:</td>
                                    <td>{{ $totalDays }}</td>
                                    <td>Paid Days:</td>
                                    <td>{{ $total_working_day }}</td>
                                </tr>
                                <tr>
                                    <td>Leave Taked:</td>
                                    <td>{{ $total_leaves }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                        <thead>
                            @if ($type == 'salary')
                                <tr>
                                    <th colspan="2">Earnings</th>
                                    <th colspan="2">Deductions</th>
                                </tr>
                            @endif
                            @if ($type == 'bonus')
                                <tr>
                                    <th colspan="3">Earnings</th>
                                    <th>Total</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @if ($type == 'salary')
                                <tr>
                                    <td>Total Basic Amount</td>
                                    <td>{{$total_working_amount}}</td>
                                    <td>Income Tax</td>
                                    <td>{{$income_tax}}</td>
                                </tr>                        
                                <tr>
                                    <td>House Rent</td>
                                    <td>{{$house_rent}}</td>
                                    <td>Deduction Amount</td>
                                    <td>{{$deduction}}</td>
                                </tr>                        
                                <tr>
                                    <td>Medical Allowance</td>
                                    <td>{{$medical_allowance}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>                        
                                <tr>
                                    <td>Tansport Allowance</td>
                                    <td>{{$tansport_allowance}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>                        
                                <tr>
                                    <td>Food Allowance</td>
                                    <td>{{$food_allowance}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>                        
                                <tr>
                                    <td>Mobile Allowance</td>
                                    <td>{{$mobile_allowance}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>                        
                                <tr>
                                    <td>Other Allowance</td>
                                    <td>{{$other_allowance}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>                       
                                                
                                <tr>
                                    <td>Additional Amount</td>
                                    <td>{{$addition}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>   
                                <tr>
                                    <td>Casual Salary</td>
                                    <td>{{$casual_salary}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>  
                        
                                <tr>
                                    <th>Total Earnigns</th>
                                    <td>{{$total_earnings }}</td>
                                    <th>Total Deductions</th>
                                    <td>{{$total_deductions}}</td>
                                </tr>                        
                                <tr>
                                    <th colspan="3">Net Salary</th>
                                    <td>{{ ($total_earnings) - $total_deductions }}/=</td>
                                </tr>   
                            @endif
                            @if ($type == 'bonus')
                                @foreach ($data->processed_bonous as $bonuse)
                                    <?php
                                    $total_bonus += $bonuse->bonus_amount;
                                    ?>
                                    <tr>
                                        <th colspan="2">{{ ucfirst($bonuse->bonus_name) }}</th>
                                        <td>{{$bonuse->bonus_amount }}</td>
                                        <td></td>
                                    </tr> 
                                @endforeach
                                <tr>
                                    <th colspan="3">Net Bonus</th>
                                    <td>{{$total_bonus }}/=</td>
                                </tr> 
                            @endif                     
                        </tbody>
                    </table>
                </div>
                    <htmlpagefooter name="page-footer">
                        <div class="text-xs">
                            <div class="display">
                                <div style="width:49%;float:left; margin-left: 5px; border: 1px solid black; padding: 50px 0px 5px 0px;">
                                    <div>
                                        <div class="text-center">Employer Signature</div>
                                    </div>
                                </div>
                                <div style="width:49%;float:left; margin-left: 5px; border: 1px solid black; padding: 50px 0px 5px 0px;">
                                    <div>
                                        <div class="text-center">Employee Signature</div>
                                    </div>
                                </div>
                
                            </div>
                            <div>
                                &nbsp;
                            </div>
                        </div>
                    </htmlpagefooter>
                @if (count($reportData) -1 > $key)
                    <pagebreak>
                @endif
            @endforeach
        @else
            {{-- <h1 class="text-center" style="margin-top: 120px;">No results found</h1> --}}
            <div style="padding-top: 150px;">
                <h1 class="text-center" >No results found</h1>
            </div>
            <htmlpagefooter name="page-footer">
                <div class=" text-xs justify-between">
                    <div>
                        <div style="width:24%; float:left; margin-left: 5px; border: 1px solid black; padding: 50px 0px 5px 0px;">
                            <div>
                                <div class="text-center">Employer Signature</div>
                            </div>
                        </div>
                        <div style="width:24%; float:left; margin-left: 5px; border: 1px solid black; padding: 50px 0px 5px 0px;">
                            <div>
                                <div class="text-center">Employee Signature</div>
                            </div>
                        </div>
        
                    </div>
                    <div>
                        &nbsp;
                    </div>
                </div>
            </htmlpagefooter>
        @endif

    </div>

</body>

</html>
