@extends('layouts.backend-layout')
@section('title', 'Processed Salary')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/Datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb-title')
List of Processed Salary
@endsection

@section('style')

@endsection
@section('breadcrumb-button')
@endsection
@section('sub-title')
Total: {{ count($processed_salaries) }}
@endsection


@section('content')
<style>
    table th, table td{
        border: 1px solid #D3E7FB !important;        
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .modal-loader {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10000;
    }
    .custom-form .input-group-addon {
        min-width: 110px !important;
    }
</style>
{!! Form::open([
    'url' => 'hr/salary/processed',
    'method' => 'POST',
    'class' => 'custom-form',
    'id' => 'salary_processed_form',
    'files' => true,
    'enctype' => 'multipart/form-data',
    ]) !!}


<div class="row px-4 pt-2 pb-4">
        <div class="col-md-4">
            <div class="input-group input-group-sm input-group-primary">
                <label class="input-group-addon" for="year">Month <span class="text-danger">*</span></label>
                {{ Form::month(
                    'month',
                    old('month')
                        ? old('month'):'',
                    [
                        'class' => 'form-control',
                        'id' => 'month',
                        'placeholder' => '',
                        'required'
                    ],
                ) }}
    
                @error('month')
                <p class="text-danger">{{ $errors->first('month') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group input-group-sm input-group-primary">
                <label class="input-group-addon" for="department_id">Department<span class="text-danger">*</span></label>
                {{Form::select('department_id',$departments, old('department_id') ? old('department_id') : null,['class' => 'form-control select2 employee_active','id' => 'department_id', 'placeholder' =>
                'Select Bank', 'required'] )}}
                @error('department_id') <p class="text-danger">{{ $errors->first('department_id') }}</p> @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="offset-md-4 col-md-8">
                <div class="input-group input-group-sm">
                    {{-- <a class="btn btn-out-dashed btn-md btn-danger rounded text-white">Processed Data</a> --}}
                    <button class="btn btn-warning btn-round btn-block py-2" id="processed_btn">
                        Processed
                    </button>
                </div>
            </div>
        </div>

    <!-- Background shadow -->
    <div class="modal-overlay"></div>
    <!-- Processed Loader -->
    <div class="modal-loader">
        <div class="loader animation-start">
            <span class="circle delay-1 size-2"></span>
            <span class="circle delay-2 size-4"></span>
            <span class="circle delay-3 size-6"></span>
            <span class="circle delay-4 size-7"></span>
            <span class="circle delay-5 size-7"></span>
            <span class="circle delay-6 size-6"></span>
            <span class="circle delay-7 size-4"></span>
            <span class="circle delay-8 size-2"></span>
        </div>
    </div>
</div>
{!! Form::close() !!} 

<div class="dt-responsive table-responsive">
    <table id="dataTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#SL</th>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Month</th>
                <th>Total working days</th>
                <th>Total OT hour</th>
                <th>Adjustment Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#SL</th>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Month</th>
                <th>Total working days</th>
                <th>Total OT hour</th>
                <th>Adjustment Amount</th>
                <th>Total Amount</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($processed_salaries as $key => $data)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $data->employee->emp_code }}</td>
                    <td class="text-left">{{ $data->employee->emp_name }}</td>
                    <td class="text-center">{{ $data->month }}</td>
                    <td class="text-center">{{ $data->total_working_day }}</td>
                    <td class="text-center">{{ $data->total_ot_hour }}</td>
                    <td class="text-center">{{ $data->adjustment_amount }}</td>
                    <td class="text-center">{{ $data->total_working_day ?(($data->total_working_amount + $data->total_ot_amount + $data->house_rent + $data->medical_allowance + $data->tansport_allowance + $data->food_allowance + $data->other_allowance + $data->mobile_allowance + $data->grade_bonus + $data->skill_bonus + $data->management_bonus + $data->adjustment_amount) - $data->income_tax) :'0' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection

@section('script')
<script src="{{ asset('js/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>

    $(document).ready(function () {
        $('#processed_btn').click(function () {
            var form = document.getElementById("salary_processed_form");    
            if(form.checkValidity()) {           
                $('.modal-overlay').fadeIn();
                $('.modal-loader').fadeIn();
            }
        });
    });
</script>
<script>
    $(window).scroll(function() {
        //set scroll position in session storage
        sessionStorage.scrollPos = $(window).scrollTop();
    });
    var init = function() {
        //get scroll position in session storage
        $(window).scrollTop(sessionStorage.scrollPos || 0)
    };
    window.onload = init;

</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var monthField = document.getElementById("month");
        var currentMonth = new Date().toISOString().slice(0, 7);
        var currentDate = new Date(currentMonth + '-01');
        var previous = new Date(currentDate);
        previous.setMonth(previous.getMonth() - 1);
        var previousMonth = previous.toISOString().slice(0, 7);
        monthField.min = previousMonth;
        monthField.max = currentMonth;
        monthField.value = null;

    });
    
</script>
@endsection
