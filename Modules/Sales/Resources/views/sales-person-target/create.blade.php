@extends('layouts.backend-layout')
@section('title', 'Sales Person Target')

@section('breadcrumb-title')
@if ($formType == 'edit')
Edit
@else
Create
@endif
Sales Person Target
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
<a class="btn btn-out-dashed btn-sm btn-warning" href="{{ route('sales-person-targets.index') }}"><i class="fas fa-database"></i></a>
@endsection

@section('sub-title')
<span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'col-md-12 col-lg-12 col-sm-12 my-3')

@section('content')
@if ($formType == 'create')
{!! Form::open([
'url' => 'sales/sales-person-targets',
'method' => 'POST',
'class' => 'custom-form',
'files' => true,
'enctype' => 'multipart/form-data',
]) !!}
@else
{!! Form::open([
'url' => "sales/sales-person-targets/$sales_target->id",
'method' => 'PUT',
'class' => 'custom-form',
'files' => true,
'enctype' => 'multipart/form-data',
]) !!}
@endif

<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="input-group input-group-sm input-group-primary">
            <label style="" class="input-group-addon" for="month">Month <span class="text-danger">*</span></label>
            {{ Form::month(
                    'month',
                    old('month') ? old('month') : (!empty($sales_target->month) ? $sales_target->month : null),
                    [
                        'class' => 'form-control',
                        'id' => 'month',
                        'placeholder' => 'Enter month Here',
                        'required',
                    ],
                ) }}
            @error('month')
            <p class="text-danger">{{ $errors->first('month') }}</p>
            @enderror
        </div>
    </div>
</div><!-- end row -->
<hr class="bg-success">

<div class="row">
    <table class="table table-striped table-bordered" id="raw_materials_table">
        <thead>
            <tr>
                <th>Employees</th>
                <th>Target</th>
                <th>
                    <button type="button" class="btn btn-sm add-row">
                        <i class="ti-plus"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody>

            @if ($formType == 'create')
            <tr>
                <td>
                    {{ Form::select(
                        'target_details[0][employee_id]',
                            $employee,
                            old('target_details[0][employee_id]')
                            ? old('target_details[0][employee_id]')
                            : (!empty($sales_target->employee_id)
                            ? $sales_target->employee_id
                            : null),
                            [
                                'class' => 'form-control select2',
                                'id' => 'employee_id',
                                'placeholder' => 'Select Employee',
                                'required',
                            ],
                        )
                    }}
                </td>


                <td>
                    <input min="1" type="number" name="target_details[0][target]" value="0" min="1" class="form-control quantity" id="target" />

                </td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="ti-minus"></i>
                    </button>
                </td>

            </tr>
            @else
            @foreach (old('target_details', $sales_target->target_order_details ?? []) as $index => $value)
            <tr>
                <td>
                    <select class="select2" name="target_details[{{ $index }}][employee_id]">
                        <option value="" disabled>Select Employee</option>
                        @foreach ($employee as $key => $name)
                        <option value="{{ $key }}" @selected($key==$value->employee_id)>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input min="1" type="number" name="target_details[{{ $index }}][target]" value="{{ $value->target }}" class="form-control quantity" id="target" />

                </td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="ti-minus"></i>
                    </button>
                </td>
            </tr>
            @endforeach

            @endif

        </tbody>

    </table>
</div>

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

    // let deliveryOrder = JSON.parse('<?= isset($sales_target->target_order_details) ? $sales_target->target_order_details : '{}' ?>');
    let salesTarget = JSON.parse('<?= isset($sales_target) ? $sales_target : '{}' ?>');
    let rawMaterialCount = (Object.keys(salesTarget) != 0 && salesTarget.target_order_details
            .length > 0) ? salesTarget.target_order_details.length : 1;
    // let rawMaterialCount = 0;
    $(document).on("click", ".add-row", function() {
        var html = "";
        html += `<tr>

                        <td>
                            {{ Form::select(
                                'target_details[${rawMaterialCount}][employee_id]',
                                    $employee,
                                    old('target_details[${rawMaterialCount}][employee_id]')
                                    ? old('target_details[${rawMaterialCount}][employee_id]')
                                    : (!empty($sales_target->employee_id)
                                    ? $sales_target->employee_id
                                    : null),
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'employee_id',
                                        'placeholder' => 'Select Employee',
                                        'required',
                                    ],
                                )
                            }}
                        </td>


                        <td>
                            <input type="number" name="target_details[${rawMaterialCount}][target]" value="0"
                                class="form-control quantity" id="target" min="1" />

                        </td>

                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="ti-minus"></i>
                            </button>
                        </td>

                    </tr>`;
        $("tbody").append(html);
        rawMaterialCount++;
    });

    $(document).on("click", ".remove-row", function() {
        $(this).closest("tr").remove();
    });
</script>

@endsection
