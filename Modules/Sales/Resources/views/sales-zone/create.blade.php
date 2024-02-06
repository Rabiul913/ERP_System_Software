@extends('layouts.backend-layout')
@section('title', 'Sales Zone')

@section('breadcrumb-title')
@if ($formType == 'edit')
Edit
@else
Create
@endif
Sales Zone
@endsection

@section('style')
<style>
    .input-group-addon {
        min-width: 120px;
    }
</style>
@endsection
@section('breadcrumb-button')
<a class="btn btn-out-dashed btn-sm btn-warning" href="{{ route('sales-zones.index') }}"><i
        class="fas fa-database"></i></a>
@endsection

@section('sub-title')
<span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'offset-md-1 col-md-10 offset-lg-2 col-lg-8 my-3')

@section('content')
@if ($formType == 'create')
{!! Form::open(['url' => 'sales/sales-zones', 'method' => 'POST', 'class' => 'custom-form', 'files' =>
true,'enctype'=>'multipart/form-data']) !!}
@else
{!! Form::open([
'url' => "sales/sales-zones/$sales_zone->id",
'method' => 'PUT',
'class' => 'custom-form',
'files' => true,
'enctype'=>'multipart/form-data',
]) !!}
@endif

<div class="row">
    <div class="col-12">
        <div class="input-group input-group-sm input-group-primary">
            <label style="min-width: 22% !important; max-width:22% !important;" class="input-group-addon"
                for="zone">Zone Name <span class="text-danger">*</span></label>
            {{ Form::text(
            'zone',
            old('zone')
            ? old('zone')
            : (!empty($sales_zone->zone)
            ? $sales_zone->zone
            : null),
            ['class' => 'form-control', 'id' => 'zone', 'placeholder' => 'Enter sales zone Here', 'required'],
            ) }}
            @error('zone')
            <p class="text-danger">{{ $errors->first('zone') }}</p>
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
