@extends('layouts.backend-layout')
@section('title', 'User')

@section('breadcrumb-title')
    @if ($formType == 'edit')
        Edit
    @else
        Create
    @endif
    Sales Order
@endsection

@section('style')
    <style>
        .input-group-addon {
            min-width: 120px;
        }
    </style>
@endsection
@section('breadcrumb-button')
    <a class="btn btn-out-dashed btn-sm btn-warning" href="{{ route('sales-orders.index') }}"><i
            class="fas fa-database"></i></a>
@endsection

@section('sub-title')
    <span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'col-md-12 col-lg-12 col-sm-12 my-3')

@section('content')
    @if ($formType == 'create')
        {!! Form::open([
            'url' => 'sales/sales-orders',
            'method' => 'POST',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @else
        {!! Form::open([
            'url' => "sales/sales-orders/$salesOrder->id",
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
                    old('customer_id') ? old('customer_id') : (!empty($salesOrder->customer_id) ? $salesOrder->customer_id : null),
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
                <label style="" class="input-group-addon" for="date">Date <span
                        class="text-danger">*</span></label>
                {{ Form::date('date', old('date') ? old('date') : (!empty($salesOrder->date) ? $salesOrder->date : null), [
                    'class' => 'form-control',
                    'id' => 'date',
                    'placeholder' => 'Enter Date Here',
                    'required',
                ]) }}
                @error('date')
                    <p class="text-danger">{{ $errors->first('date') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="reference_employee_id">Sales Person <span
                        class="text-danger">*</span></label>
                {{ Form::text(
                    'reference_employee_id',
                    old('reference_employee_id')
                        ? old('reference_employee_id')
                        : (!empty($salesOrder->reference_employee_id)
                            ? $salesOrder->reference_employee_id
                            : null),
                    [
                        'class' => 'form-control',
                        'id' => 'reference_employee_id',
                        'placeholder' => 'Enter Sales Person Here',
                        'required',
                    ],
                ) }}
                @error('reference_employee_id')
                    <p class="text-danger">{{ $errors->first('reference_employee_id') }}</p>
                @enderror
            </div>
        </div>





    </div><!-- end row -->
    <hr class="bg-success">

    <div class="row mt-2 row_material_section">
        <table class="table table-striped table-bordered" id="raw_materials_table">
            <thead>
                <tr>
                    <th>Product Type</th>
                    <th>Product Name</th>
                    <th>Product Size</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
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
                            <select class="form-control product_type_id select2" id="product_type_id">
                                <option value="">Select Product Type</option>
                            </select>
                        </td>
                        <td>
                            <select name="raw_materials[0][product_id]" class="form-control product_id"
                                id="raw_material_id">
                                <option value="">Select Product Name</option>
                            </select>
                        </td>
                        <td>
                            <select name="raw_materials[0][size_id]" class="form-control product_size_id select2"
                                id="product_size_id">
                                <option value="">Select Product Size</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="raw_materials[0][quantity]" value="" class="form-control"
                                id="raw_material_quantity" />
                        </td>
                        <td>
                            <input type="text" name="raw_materials[0][unit_price]" value="" class="form-control"
                                id="raw_material_ratio" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="ti-minus"></i>
                            </button>
                        </td>
                    </tr>
                @else
                    @foreach (old('raw_materials', $salesOrder->salesOrderDetails ?? []) as $index => $product)
                        <tr>
                            <td>
                                <select class="select2">
                                    <option value="" disabled>Select Product Type</option>
                                    @foreach ($productType as $key => $name)
                                        <option value="{{ $key }}" @selected($key == $product->product->product_type_id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="raw_materials[{{ $index }}][product_id]"
                                    class="form-control product_id select2">
                                    <option value="" disabled>Select Product Name</option>
                                    @foreach ($allProducts as $key => $name)
                                        <option value="{{ $key }}" @selected($key == $product->product_id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>

                            </td>
                            <td>
                                <select name="raw_materials[{{ $index }}][size_id]"
                                    class="form-control product_size_id" id="product_size_id">
                                    <option value="" disabled></option>
                                    @foreach ($sizes as $key => $name)
                                        <option value="{{ $key }}" @selected($key == $product->size_id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="raw_materials[{{ $index }}][quantity]"
                                    value="{{ $product->quantity }}" class="form-control" id="raw_material_quantity" />
                            </td>
                            <td>
                                <input type="text" name="raw_materials[{{ $index }}][unit_price]"
                                    value="{{ $product->ratio }}" class="form-control" id="raw_material_ratio" />
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
            <tfoot></tfoot>
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
        let salesOrder = JSON.parse('<?= isset($salesOrder) ? $salesOrder : '{}' ?>');
        let rawMaterialCount = (Object.keys(salesOrder) != 0 && salesOrder.sales_order_details
            .length > 0) ? salesOrder.sales_order_details.length : 1;
        const getProductType = () => {
            $.ajax({
                url: "{{ route('getProductTypes') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    search: ''
                },
                success: function(response) {
                    console.log("response product", response);
                    $.each(response, function(index, value) {
                        $('.product_type_id').append('<option value="' + value.id +
                            '">' + value
                            .text + '</option>');
                    });
                    $('.raw_material_id').select2({
                        placeholder: 'Select Product Type'
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        }
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('getProductSizes') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    search: ''
                },
                success: function(response) {
                    console.log("response product sizes", response);
                    $.each(response, function(index, value) {
                        $('#product_size_id').append('<option value="' + value.id + '">' + value
                            .text + '</option>');
                    });
                    // $('#raw_material_id').select2({
                    //     placeholder: 'Select Product Size'
                    // });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });

            getProductType();
        });

        $(document).on('change', '.product_type_id', function() {
            var product_type_id = $(this).val();
            $(this).closest('tr').find('.product_id').val(null).trigger('change');

            $('.product_id').last().select2({
                ajax: {
                    url: "{{ route('products.getProductByType') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            _token: '{{ csrf_token() }}',
                            product_type_id: product_type_id,
                            search: params.term
                        }
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                },
            });
        });



        $(document).on("click", ".add-row", function() {
            var html = "";
            html += `<tr>
                       <td>
                           <select class="form-control product_type_id select2"id="">
                                <option value="">Select Product Type</option>
                            </select>
                        </td>
                        <td>
                            <select name="raw_materials[${rawMaterialCount}][product_id]" class="form-control product_id"
                                >
                                <option value="">Select Product Name</option>
                            </select>
                        </td>
                        <td>
                            <select name="raw_materials[${rawMaterialCount}][size_id]" class="form-control product_size_id"
                                id="product_size_id">
                                <option value="">Select Product Size</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="raw_materials[${rawMaterialCount}][quantity]" value="" class="form-control"
                                id="raw_material_quantity" />
                        </td>
                        <td>
                            <input type="text" name="raw_materials[${rawMaterialCount}][unit_price]" value="" class="form-control"
                                id="raw_material_ratio" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="ti-minus"></i>
                            </button>
                        </td>
                   </tr>`;
            $("tbody").append(html);
            rawMaterialCount++;
            $('.product_type_id').last().select2({
                ajax: {
                    url: "{{ route('getProductTypes') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            _token: '{{ csrf_token() }}',
                            search: params.term
                        }
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                },
            });
            $('.product_size_id').last().select2({
                ajax: {
                    url: "{{ route('getProductSizes') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            _token: '{{ csrf_token() }}',
                            search: params.term
                        }
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                },
            });
        });

        $(document).on("click", ".remove-row", function() {
            $(this).closest("tr").remove();
        });
    </script>

@endsection
