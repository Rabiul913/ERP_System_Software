@extends('layouts.backend-layout')
@section('title', 'DO Wise Delivery Challan Report')

@section('breadcrumb-title')
    @if ($formType == 'edit')
        Edit
    @else
        Create
    @endif
    DO Wise Delivery Challan Report
@endsection

@section('style')
    <style scoped>
        .input-group-addon {
            min-width: 120px;
        }

        .border-none>tr>td {
            border: none !important;
        }
    </style>
@endsection
@section('breadcrumb-button')
    {{-- <a class="btn btn-out-dashed btn-sm btn-warning" href="{{ route('delivery-challans.index') }}"><i
            class="fas fa-database"></i></a> --}}
@endsection

@section('sub-title')
    <span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'col-md-12 col-lg-12 col-sm-12 my-3')

@section('content')
    @if ($formType == 'do-wise-delivery-challan')
        {!! Form::open([
            'url' => 'sales/do-wise-delivery-challan/report',
            'method' => 'POST',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @endif

    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="date">From Date <span
                        class="text-danger">*</span></label>
                {{ Form::date(
                    'from_date',
                    old('from_date'),
                    [
                        'class' => 'form-control',
                        'id' => 'from_date',
                        'placeholder' => 'Enter from_date Here',
                        'required',
                    ],
                ) }}

            </div>
                @error('from_date')
                    <p class="text-danger">{{ $errors->first('from_date') }}</p>
                @enderror
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="date">To Date <span
                        class="text-danger">*</span></label>
                {{ Form::date(
                    'to_date',
                    old('to_date'),
                    [
                        'class' => 'form-control',
                        'id' => 'to_date',
                        'placeholder' => 'Enter to_date Here',
                        'required',
                    ],
                ) }}

            </div>
            @error('to_date')
                    <p class="text-danger">{{ $errors->first('to_date') }}</p>
                @enderror
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="customer_id">Customer</label>
                {{Form::select('customer_id', $customers, old('customer_id'),['class' => 'form-control','id' => 'customer_id', 'placeholder'=>"All", 'autocomplete'=>"off", ""])}}
                @error('customer_id')
                    <p class="text-danger">{{ $errors->first('customer_id') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="date">Category</label>
                {{Form::select('category_id', $categories, old('category_id'),['class' => 'form-control','id' => 'category_id', 'placeholder'=>"All", 'autocomplete'=>"off", ""])}}
                @error('category_id')
                    <p class="text-danger">{{ $errors->first('category_id') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="product_id">Product</label>
                {{-- {{Form::select('product_id', [], old('product_id'),['class' => 'form-control','id' => 'product_id', 'placeholder'=>"All", 'autocomplete'=>"off", ""])}} --}}
                <select name="product_id" class="form-control product_id"
                                id="product_id" autocomplete="off">
                                <option value="">All</option>
                            </select>
                @error('product_id')
                    <p class="text-danger">{{ $errors->first('product_id') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="zone_id">Zone</label>
                {{Form::select('zone_id', $zones, old('zone_id'),['class' => 'form-control','id' => 'zone_id', 'placeholder'=>"All", 'autocomplete'=>"off", ""])}}
                @error('zone_id')
                    <p class="text-danger">{{ $errors->first('zone_id') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="employee_id">Executive</label>
                {{Form::select('employee_id', $employees, old('employee_id'),['class' => 'form-control','id' => 'employee_id', 'placeholder'=>"All", 'autocomplete'=>"off", ""])}}
                @error('employee_id')
                    <p class="text-danger">{{ $errors->first('employee_id') }}</p>
                @enderror
            </div>
        </div>




    </div>

    {{-- <hr class="bg-success"> --}}



    <div class="row">
        <div class="offset-md-4 col-md-4 mt-2 ">
            <div class="input-group input-group-sm ">
                <button class="btn btn-success btn-round btn-block py-2" formtarget="_blank">Submit</button>
            </div>
        </div>
    </div> <!-- end row -->

    {!! Form::close() !!}
@endsection
@section('script')

<script>
    $(document).ready(function() {
        $('#category_id').change(()=>{
            $.ajax({
                url: "{{ route('getCategoryWiseProducts') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: $('#category_id').val()
                },
                success: function(response) {
                    $('.product_id').empty();
                    $('.product_id').append('<option value="">' + "All" + '</option>');
                    $.each(response, function(index, value) {
                        console.log(index, value);
                        $('.product_id').append('<option value="' + value.id + '">' + value
                            .text + '</option>');
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        });
    });

</script>

@endsection
