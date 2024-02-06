@extends('layouts.backend-layout')
@section('title', 'Employee Target Report')

@section('breadcrumb-title')
    @if ($formType == 'edit')
        Edit
    @else
        Create
    @endif
    Employee Target Report
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
    @if ($formType == 'delivery-sales-collection-employee-target')
        {!! Form::open([
            'url' => 'sales/delivery-sales-collection-employee-target/report',
            'method' => 'POST',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @endif

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="month">Month <span
                        class="text-danger">*</span></label>
                {{-- {{ Form::date(
                    'date',
                    old('date'),
                    [
                        'class' => 'form-control',
                        'id' => 'date',
                        'placeholder' => 'Enter Date Here',
                        'required',
                    ],
                ) }} --}}
                <input type="month" class="form-control" name="month" id="month" placeholder="Select Month Here" required/>

            </div>
                @error('month')
                    <p class="text-danger">{{ $errors->first('month') }}</p>
                @enderror
        </div>
    </div>

    {{-- <hr class="bg-success"> --}}



    <div class="row">
        <div class="offset-md-4 col-md-4 mt-2 ">
            <div class="input-group input-group-sm ">
                <button class="btn btn-success btn-round btn-block py-2" type="submit" formtarget="_blank">Submit</button>
            </div>
        </div>
    </div> <!-- end row -->

    {!! Form::close() !!}
@endsection
@section('script')

@endsection
