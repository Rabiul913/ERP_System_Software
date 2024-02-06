@extends('layouts.backend-layout')
@section('title', 'User')

@section('breadcrumb-title')
    @if ($formType == 'edit')
        Edit
    @else
        Create
    @endif
    Sales Collection
@endsection

@section('style')
    <style scoped>
        .input-group-addon {
            min-width: 120px;
        }

        .radio-input {
            height: 20px !important;
        }
    </style>
@endsection
@section('breadcrumb-button')
    <a class="btn btn-out-dashed btn-sm btn-warning" href="{{ route('sales-collections.index') }}"><i
            class="fas fa-database"></i></a>
@endsection

@section('sub-title')
    <span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'col-md-12 col-lg-12 col-sm-12 my-3')

@section('content')
    @if ($formType == 'create')
        {!! Form::open([
            'url' => 'sales/sales-collections',
            'method' => 'POST',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @else
        {!! Form::open([
            'url' => "sales/sales-collections/$salesCollection->id",
            'method' => 'PUT',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @endif

    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="customer_id">Customer <span
                        class="text-danger">*</span></label>
                {{ Form::select(
                    'customer_id',
                    $customers,
                    old('customer_id')
                        ? old('customer_id')
                        : (!empty($salesCollection->customer_id)
                            ? $salesCollection->customer_id
                            : null),
                    [
                        'class' => 'form-control select2',
                        'id' => 'customer_id',
                        'placeholder' => 'Select Customer',
                        'required',
                    ],
                ) }}
                @error('customer_id')
                    <p class="text-danger">{{ $errors->first('customer_id') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="customer_id">Customer <span
                        class="text-danger">*</span></label>
                {{ Form::select(
                    'employee_id',
                    $employees,
                    old('employee_id')
                        ? old('employee_id')
                        : (!empty($salesCollection->employee_id)
                            ? $salesCollection->employee_id
                            : null),
                    [
                        'class' => 'form-control select2',
                        'id' => 'employee_id',
                        'placeholder' => 'Select Sales Person',
                        'required',
                    ],
                ) }}
                @error('employee_id')
                    <p class="text-danger">{{ $errors->first('employee_id') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="date">Date <span
                        class="text-danger">*</span></label>
                {{ Form::date(
                    'date',
                    old('date') ? old('date') : (!empty($salesCollection->date) ? $salesCollection->date : null),
                    [
                        'class' => 'form-control datepicker',
                        'id' => 'date',
                        'placeholder' => 'Select Date',
                        'required',
                    ],
                ) }}
                @error('date')
                    <p class="text-danger">{{ $errors->first('date') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary" style="align-items: center;">
                <label style="" class="input-group-addon" for="amount">Payment Type <span
                        class="text-danger">*</span></label>

                {{ Form::radio(
                    'payment_type',
                    'cash',
                    old('payment_type')
                        ? old('payment_type')
                        : (!empty($salesCollection->payment_type)
                            ? $salesCollection->payment_type
                            : null),
                    [
                        'class' => 'radio-input form-control',
                        'id' => 'payment_type',
                        'placeholder' => 'Select Payment Type',
                        'style' => 'height: 20px !important; box-shadow: 0 0 5px rgb(255 255 255 / 75%) !important;',
                        'required',
                    ],
                ) }}
                Cash
                {{ Form::radio(
                    'payment_type',
                    'bank',
                    old('payment_type')
                        ? old('payment_type')
                        : (!empty($salesCollection->payment_type)
                            ? $salesCollection->payment_type
                            : null),
                    [
                        'class' => 'radio-input form-control',
                        'id' => 'payment_type',
                        'placeholder' => 'Select Payment Type',
                        'style' => 'height: 20px !important; box-shadow: 0 0 5px rgb(255 255 255 / 75%) !important;',
                        'required',
                    ],
                ) }}
                Bank
                @error('payment_type')
                    <p class="text-danger">{{ $errors->first('payment_type') }}</p>
                @enderror
            </div>
        </div>


        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="bank_name">Bank Name <span
                        class="text-danger">*</span></label>
                {{ Form::text(
                    'bank_name',
                    old('bank_name') ? old('bank_name') : (!empty($salesCollection->bank_name) ? $salesCollection->bank_name : null),
                    [
                        'class' => 'form-control',
                        'id' => 'bank_name',
                        'placeholder' => 'Enter Bank Name',
                        'required',
                    ],
                ) }}
                @error('bank_name')
                    <p class="text-danger">{{ $errors->first('bank_name') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="cheque_no">Check Number <span
                        class="text-danger">*</span></label>
                {{ Form::text(
                    'cheque_no',
                    old('cheque_no') ? old('cheque_no') : (!empty($salesCollection->cheque_no) ? $salesCollection->cheque_no : null),
                    [
                        'class' => 'form-control',
                        'id' => 'cheque_no',
                        'placeholder' => 'Enter Check Number',
                        'required',
                    ],
                ) }}
                @error('cheque_no')
                    <p class="text-danger">{{ $errors->first('cheque_no') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="width: 100%;" class="input-group-addon" for="amount">Amount <span
                        class="text-danger">*</span></label>
                {{ Form::number(
                    'amount',
                    old('amount') ? old('amount') : (!empty($salesCollection->amount) ? $salesCollection->amount : null),
                    [
                        'class' => 'form-control',
                        'id' => 'amount',
                        'placeholder' => 'Enter Amount',
                        'required',
                    ],
                ) }}
                @error('amount')
                    <p class="text-danger">{{ $errors->first('amount') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="remark">remark <span
                        class="text-danger">*</span></label>
                {{ Form::textarea(
                    'remark',
                    old('remark') ? old('remark') : (!empty($salesCollection->remark) ? $salesCollection->remark : null),
                    [
                        'class' => 'form-control',
                        'id' => 'remark',
                        'placeholder' => 'Enter remark',
                        'required',
                    ],
                ) }}
                @error('remark')
                    <p class="text-danger">{{ $errors->first('remark') }}</p>
                @enderror
            </div>
        </div>




    </div><!-- end row -->

    <div class="row">
        <div class="offset-md-4 col-md-4 mt-2 ">
            <div class="input-group input-group-sm ">
                <button class="btn btn-success btn-round btn-block py-2">Submit</button>
            </div>
        </div>
    </div> <!-- end row -->
    {!! Form::close() !!}
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            if ($('input[name="payment_type"]:checked').val() == 'bank') {
                $('#bank_name').parent().parent().show();
                $('#cheque_no').parent().parent().show();
            } else {
                $('#bank_name').parent().parent().hide();
                $('#cheque_no').parent().parent().hide();
            }
        });

        $('input[name="payment_type"]').on('change', function() {
            if ($(this).val() == 'bank') {
                $('#bank_name').parent().parent().show();
                $('#cheque_no').parent().parent().show();
            } else {
                $('#bank_name').parent().parent().hide();
                $('#cheque_no').parent().parent().hide();
            }
        });
    </script>

@endsection
