@extends('layouts.backend-layout')
@section('title', 'Customers')

@section('breadcrumb-title')
    @if ($formType == 'edit')
        Edit
    @else
        Create
    @endif
    Customer
@endsection

@section('style')
    <style>
        .input-group-addon {
            min-width: 120px;
        }
    </style>
@endsection
@section('breadcrumb-button')
    <a class="btn btn-out-dashed btn-sm btn-warning" href="{{ route('customers.index') }}"><i class="fas fa-database"></i></a>
@endsection

@section('sub-title')
    <span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'col-md-12 col-lg-12 col-sm-12 my-3')

@section('content')
    @if ($formType == 'create')
        {!! Form::open([
            'url' => 'sales/customers',
            'method' => 'POST',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @else
        {!! Form::open([
            'url' => "sales/customers/$customer->id",
            'method' => 'PUT',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @endif

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:22% !important;" class="input-group-addon"
                    for="name">Customer Name <span class="text-danger">*</span></label>
                {{ Form::text('name', old('name') ? old('name') : (!empty($customer->name) ? $customer->name : null), [
                    'class' => 'form-control',
                    'id' => 'name',
                    'placeholder' => 'Enter Customer name Here',
                    'required',
                ]) }}
                @error('name')
                    <p class="text-danger">{{ $errors->first('name') }}</p>
                @enderror
            </div>
        </div>


        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:22% !important;" class="input-group-addon"
                    for="address">Address<span class="text-danger">*</span></label>
                {{ Form::text(
                    'address',
                    old('address') ? old('address') : (!empty($customer->address) ? $customer->address : null),
                    [
                        'class' => 'form-control',
                        'id' => 'address',
                        'placeholder' => 'Enter Address Here',
                        'required',
                    ],
                ) }}
                @error('address')
                    <p class="text-danger">{{ $errors->first('address') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:22% !important;" class="input-group-addon"
                    for="address">Business Type<span class="text-danger">*</span></label>
                {{ Form::text(
                    'business_type',
                    old('business_type') ? old('business_type') : (!empty($customer->business_type) ? $customer->business_type : null),
                    [
                        'class' => 'form-control',
                        'id' => 'business_type',
                        'placeholder' => 'Enter Business Type Here',
                        'required',
                    ],
                ) }}
                @error('business_type')
                    <p class="text-danger">{{ $errors->first('business_type') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:22% !important;" class="input-group-addon"
                    for="bin_no">BIN No<span class="text-danger">*</span></label>
                {{ Form::text(
                    'bin_no',
                    old('bin_no') ? old('bin_no') : (!empty($customer->bin_no) ? $customer->bin_no : null),
                    [
                        'class' => 'form-control',
                        'id' => 'bin_no',
                        'placeholder' => 'Enter BIN No Here',
                        'required',
                    ],
                ) }}
                @error('bin_no')
                    <p class="text-danger">{{ $errors->first('bin_no') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:22% !important;" class="input-group-addon"
                    for="tin_no">TIN No<span class="text-danger">*</span></label>
                {{ Form::text(
                    'tin_no',
                    old('tin_no') ? old('tin_no') : (!empty($customer->tin_no) ? $customer->tin_no : null),
                    [
                        'class' => 'form-control',
                        'id' => 'tin_no',
                        'placeholder' => 'Enter TIN No Here',
                        'required',
                    ],
                ) }}
                @error('tin_no')
                    <p class="text-danger">{{ $errors->first('tin_no') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:22% !important;" class="input-group-addon"
                    for="trade_license_no">Trade License No<span class="text-danger">*</span></label>
                {{ Form::text(
                    'trade_license_no',
                    old('trade_license_no')
                        ? old('trade_license_no')
                        : (!empty($customer->trade_license_no)
                            ? $customer->trade_license_no
                            : null),
                    [
                        'class' => 'form-control',
                        'id' => 'trade_license_no',
                        'placeholder' => 'Enter Trade License No Here',
                        'required',
                    ],
                ) }}
                @error('trade_license_no')
                    <p class="text-danger">{{ $errors->first('trade_license_no') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:22% !important;" class="input-group-addon"
                    for="limit">Limit<span class="text-danger">*</span></label>
                {{ Form::text('limit', old('limit') ? old('limit') : (!empty($customer->limit) ? $customer->limit : null), [
                    'class' => 'form-control',
                    'id' => 'limit',
                    'placeholder' => 'Enter Limit Here',
                    'required',
                ]) }}
                @error('limit')
                    <p class="text-danger">{{ $errors->first('limit') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:25% !important;" class="input-group-addon"
                    for="country">Country<span class="text-danger">*</span></label>
                {{ Form::text(
                    'country',
                    old('country') ? old('country') : (!empty($customer->country) ? $customer->country : null),
                    [
                        'class' => 'form-control',
                        'id' => 'country',
                        'placeholder' => 'Enter Country Here',
                        'required',
                    ],
                ) }}
                @error('country')
                    <p class="text-danger">{{ $errors->first('country') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:25% !important;" class="input-group-addon"
                    for="contact_person_name">Contact Person Name<span class="text-danger">*</span></label>
                {{ Form::text(
                    'contact_person_name',
                    old('contact_person_name')
                        ? old('contact_person_name')
                        : (!empty($customer->contact_person_name)
                            ? $customer->contact_person_name
                            : null),
                    [
                        'class' => 'form-control',
                        'id' => 'contact_person_name',
                        'placeholder' => 'Enter Contact Person Name Here',
                        'required',
                    ],
                ) }}
                @error('contact_person_name')
                    <p class="text-danger">{{ $errors->first('contact_person_name') }}</p>
                @enderror
            </div>
        </div>


        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:25% !important;" class="input-group-addon"
                    for="nid_no">NID No<span class="text-danger">*</span></label>
                {{ Form::text(
                    'nid_no',
                    old('nid_no') ? old('nid_no') : (!empty($customer->nid_no) ? $customer->nid_no : null),
                    [
                        'class' => 'form-control',
                        'id' => 'nid_no',
                        'placeholder' => 'Enter NID No Here',
                        'required',
                    ],
                ) }}
                @error('nid_no')
                    <p class="text-danger">{{ $errors->first('nid_no') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:25% !important;" class="input-group-addon"
                    for="contact_no">Contact No<span class="text-danger">*</span></label>
                {{ Form::text(
                    'contact_no',
                    old('contact_no') ? old('contact_no') : (!empty($customer->contact_no) ? $customer->contact_no : null),
                    [
                        'class' => 'form-control',
                        'id' => 'contact_no',
                        'placeholder' => 'Enter Contact No Here',
                        'required',
                    ],
                ) }}
                @error('contact_no')
                    <p class="text-danger">{{ $errors->first('contact_no') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:25% !important;" class="input-group-addon"
                    for="email">
                    Email <span class="text-danger">*</span>
                </label>
                {{ Form::text('email', old('email') ? old('email') : (!empty($customer->email) ? $customer->email : null), [
                    'class' => 'form-control',
                    'id' => 'email',
                    'placeholder' => 'Enter Email Here',
                    'required',
                ]) }}
                @error('email')
                    <p class="text-danger">{{ $errors->first('email') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:25% !important;" class="input-group-addon"
                    for="sales_person_id">
                    Sale Person <span class="text-danger">*</span>
                </label>
                {{ Form::select(
                    'sales_person_id',
                    $employees,
                    old('sales_person_id')
                        ? old('sales_person_id')
                        : (!empty($customer->sales_person_id)
                            ? $customer->sales_person_id
                            : null),
                    [
                        'class' => 'form-control select2',
                        'id' => 'sales_person_id',
                        'placeholder' => 'Select Sale Representative',
                    ],
                ) }}
                @error('sales_person_id')
                    <p class="text-danger">{{ $errors->first('sales_person_id') }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:25% !important;" class="input-group-addon"
                    for="sales_person_number">
                    Sale Person Number <span class="text-danger">*</span>
                </label>
                {{ Form::text(
                    'sales_person_number',
                    old('sales_person_number')
                        ? old('sales_person_number')
                        : (!empty($customer->sales_person_number)
                            ? $customer->sales_person_number
                            : null),
                    [
                        'class' => 'form-control',
                        'id' => 'sales_person_number',
                        'placeholder' => 'Enter Sale Representative Number Here',
                    ],
                ) }}
                @error('sales_person_number')
                    <p class="text-danger">{{ $errors->first('sales_person_number') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:25% !important;" class="input-group-addon"
                    for="sales_person_id">
                    Sales Zone <span class="text-danger">*</span>
                </label>
                {{ Form::select(
                    'zone_id',
                    $zones,
                    old('zone_id')
                        ? old('zone_id')
                        : (!empty($customer->zone_id)
                            ? $customer->zone_id
                            : null),
                    [
                        'class' => 'form-control select2',
                        'id' => 'zone_id',
                        'placeholder' => 'Select Sales Zone',
                    ],
                ) }}
                @error('zone_id')
                    <p class="text-danger">{{ $errors->first('zone_id') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="min-width: 25% !important; max-width:22% !important;" class="input-group-addon"
                    for="name">Status<span class="text-danger">*</span></label>
                {{ Form::select(
                    'status',
                    [
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ],
                    old('status') ? old('status') : (!empty($customer->status) ? $customer->status : 'Active'),
                    ['class' => 'form-control', 'id' => 'status', 'placeholder' => 'Select Status', 'required'],
                ) }}
                @error('status')
                    <p class="text-danger">{{ $errors->first('status') }}</p>
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
    <script></script>

@endsection
