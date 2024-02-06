@extends('layouts.backend-layout')
@section('title', 'Delivery Challan')

@section('breadcrumb-title')
    @if ($formType == 'edit')
        Edit
    @else
        Create
    @endif
    Delivery Challan
@endsection

@section('style')
    <style scoped>
        .input-group-addon {
            min-width: 120px;
        }

        .border-none>tr>td {
            border: none !important;
        }

        .select2-readonly+.select2-container .select2-selection--single,
        .select2-readonly+.select2-container .select2-selection--single .select2-selection__rendered {
            background-color: #e9dfdf !important;
            cursor: not-allowed !important;
        }
    </style>
@endsection
@section('breadcrumb-button')
    <a class="btn btn-out-dashed btn-sm btn-warning" href="{{ route('delivery-challans.index') }}"><i
            class="fas fa-database"></i></a>
@endsection

@section('sub-title')
    <span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'col-md-12 col-lg-12 col-sm-12 my-3')

@section('content')
    @if ($formType == 'create')
        {!! Form::open([
            'url' => 'sales/delivery-challans',
            'method' => 'POST',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @else
        {!! Form::open([
            'url' => "sales/delivery-challans/$deliveryChallan->id",
            'method' => 'PUT',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @endif

    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="so_no">DO No <span class="text-danger">*</span></label>
                <?php
                $select2Class = 'form-control select2';
                if ($formType === 'edit') {
                    $select2Class .= ' select2-readonly';
                }
                ?>
                {{ Form::select(
                    'delivery_order_id',
                    $deliveryOrders,
                    old('delivery_order_id')
                        ? old('delivery_order_id')
                        : (!empty($deliveryChallan->delivery_order_id)
                            ? $deliveryChallan->delivery_order_id
                            : null),
                    [
                        'class' => $select2Class,
                        'id' => 'delivery_order_id',
                        'placeholder' => 'Select Do No Here',
                        'required',
                    ],
                ) }}
                @error('delivery_order_id')
                    <p class="text-danger">{{ $errors->first('delivery_order_id') }}</p>
                @enderror
            </div>
        </div>
        {{-- <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="challan_no">Challan No <span
                        class="text-danger">*</span></label>
                {{ Form::text(
                    'challan_no',
                    old('challan_no')
                        ? old('challan_no')
                        : (!empty($deliveryChallan->challan_no)
                            ? $deliveryChallan->challan_no
                            : null),
                    [
                        'class' => 'form-control',
                        'id' => 'challan_no',
                        'placeholder' => 'Enter Challan No Here',
                        'required',
                    ],
                ) }}
    @error('challan_no')
    <p class="text-danger">{{ $errors->first('challan_no') }}</p>
    @enderror
</div>
</div> --}}

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="customer_id">Customer <span
                        class="text-danger">*</span></label>
                {{ Form::select(
                    'customer_id',
                    $customers,
                    old('customer_id')
                        ? old('customer_id')
                        : (!empty($deliveryChallan->customer_id)
                            ? $deliveryChallan->customer_id
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
                <label style="" class="input-group-addon" for="date">Date <span
                        class="text-danger">*</span></label>
                {{ Form::date(
                    'delivery_date',
                    old('delivery_date')
                        ? old('delivery_date')
                        : (!empty($deliveryChallan->delivery_date)
                            ? $deliveryChallan->delivery_date
                            : now()->format('Y-m-d')),
                    [
                        'class' => 'form-control',
                        'id' => 'delivery_date',
                        'placeholder' => 'Enter delivery_date Here',
                        'required',
                    ],
                ) }}
                @error('delivery_date')
                    <p class="text-danger">{{ $errors->first('delivery_date') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="delivery_address">Delivery Address</label>
                {{ Form::text(
                    'delivery_address',
                    old('delivery_address')
                        ? old('delivery_address')
                        : (!empty($deliveryChallan->delivery_address)
                            ? $deliveryChallan->delivery_address
                            : null),
                    [
                        'class' => 'form-control',
                        'id' => 'delivery_address',
                        'placeholder' => 'Enter delivery_address Here',
                    ],
                ) }}
                @error('delivery_address')
                    <p class="text-danger">{{ $errors->first('delivery_address') }}</p>
                @enderror
            </div>
        </div>


        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="vehicle_no">Vehicle No </label>
                {{ Form::text(
                    'vehicle_no',
                    old('vehicle_no')
                        ? old('vehicle_no')
                        : (!empty($deliveryChallan->vehicle_no)
                            ? $deliveryChallan->vehicle_no
                            : null),
                    [
                        'class' => 'form-control',
                        'id' => 'vehicle_no',
                        'placeholder' => 'Enter vehicle_no Here',
                    ],
                ) }}
                @error('vehicle_no')
                    <p class="text-danger">{{ $errors->first('vehicle_no') }}</p>
                @enderror
            </div>
        </div>


    </div>


    <hr class="bg-success">

    <div class="row">
        <table class="table table-striped table-bordered" id="products_table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Unit</th>
                    <!-- <th>Product Size</th>/ -->
                    {{-- <th>Unit Price</th> --}}
                    <th>Quantity</th>
                    <th>Bundle Info</th>

                    <th>
                        <button type="button" class="btn btn-sm add-row" disabled>
                            <i class="ti-plus"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>

                @if ($formType == 'create')
                    <!-- <tr>
                                <td>
                                    <select name="products[0][product_id]" class="form-control product_id select2"
                                        id="raw_material_id">
                                        <option value="">Select Product Name</option>
                                    </select>
                                </td>

                                <td>
                                    {{ Form::select(
                                        'products[0][measuring_unit_id]',
                                        $units,
                                        old('measuring_unit_id')
                                            ? old('products[0][measuring_unit_id]')
                                            : (!empty($deliveryChallan->measuring_unit_id)
                                                ? $deliveryChallan->measuring_unit_id
                                                : null),
                                        [
                                            'class' => 'form-control select2 measuring_unit_id',
                                            'id' => 'measuring_unit_id',
                                            'placeholder' => 'Select Unit',
                                            'required',
                                        ],
                                    ) }}
                                </td>
                                <td>
                                    <select name="products[0][product_size_id]" class="form-control product_size_id select2">
                                        <option value="">Select Product Size</option>
                                    </select>
                                </td>

                                <td>
                                    <input type="number" name="products[0][quantity]" value="0" class="form-control quantity"
                                        id="quantity" />
                                </td>
                                <td>
                                    <input type="text" name="products[0][bundle_info]" value=""
                                        class="form-control" id="bundle_info" />
                                </td>


                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                        <i class="ti-minus"></i>
                                    </button>
                                </td>

                            </tr> -->
                @else
                    @foreach (old('products', $deliveryChallan->deliveryChallanDetails ?? []) as $index => $product)
                        <tr>
                            <td>
                                <!-- <input class="form-control" name="products[{{ $index }}][product_id]" type="hidden" value="{{ $product->product_id }}" />
                            <input class="form-control" readonly type="text" value="{{ $product->product->name }}" /> -->

                                <select class="select2" name="products[{{ $index }}][product_id]">
                                    <option value="" disabled>Select Product</option>
                                    @foreach ($products as $key => $name)
                                        <option value="{{ $key }}" @selected($key == $product->product_id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input class="form-control" name="products[{{ $index }}][measuring_unit_id]"
                                    type="hidden" value="{{ $product->product->unit_id }}" />
                                <input class="form-control" readonly type="text"
                                    value="{{ $product->product->unit->name }}" />


                            </td>
                            <!-- <td>
                            <select name="products[{{ $index }}][product_size_id]" class="form-control size_id" id="product_size_id">
                                <option value="" disabled>select </option>
                                @foreach ($sizes as $key => $name)
    <option value="{{ $key }}" @selected($key == $product->product_size_id)>
                                    {{ $name }}
                                </option>
    @endforeach
                            </select>
                        </td> -->
                            {{-- <td>
                                <input type="number" name="products[{{ $index }}][unit_price]"
                value="{{ $product->unit_price }}" class="form-control unit_price" id="unit_price" />
                </td> --}}
                            <td>
                                <input type="number" name="products[{{ $index }}][quantity]"
                                    value="{{ $product->quantity }}" class="form-control quantity"
                                    id="raw_material_quantity" />
                            </td>

                            <td>
                                <input type="text" name="products[{{ $index }}][bundle_info]"
                                    value="{{ $product->bundle_info }}" class="form-control bundle_info"
                                    id="bundle_info" />
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
        let deliveryChallan = JSON.parse('<?= isset($deliveryChallan) ? $deliveryChallan : '{}' ?>');
        let rawMaterialCount = (Object.keys(deliveryChallan) != 0 && deliveryChallan.delivery_challan_details
            .length > 0) ? deliveryChallan.delivery_challan_details.length : 1;

        let allProducts = null;
        let allUnits = JSON.parse('<?= isset($units) ? $units : '{}' ?>');
        let allSizes = null;

        let pageType = "{{ $formType }}";

        $(document).ready(function() {
            if (pageType == 'edit') {
                //make delivery order id readonly
                // $('#delivery_order_id').attr('readonly', true); not working
                // Add event listener to prevent user interaction
                $("#delivery_order_id").on("select2:opening select2:closing", function(event) {
                    event.preventDefault();
                });
            }
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
                        $('.product_size_id').append('<option value="' + value.id + '">' + value
                            .text + '</option>');
                    });
                    allSizes = response;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });

            $.ajax({
                url: "{{ route('allProducts') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    search: ''
                },
                success: function(response) {
                    $.each(response, function(index, value) {
                        $('.product_id').append('<option value="' + value.id + '">' + value
                            .text + '</option>');
                    });
                    allProducts = response;
                    console.log("response all products", allProducts)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        });

        $(document).on("change", "#delivery_order_id", function() {
            let DeliveryOrderId = $(this).val();

            if (DeliveryOrderId == '') {
                $('#products_table tbody').empty();
            } else {

                $.ajax({
                    url: `/sales/get-delivery-order-details/${DeliveryOrderId}`,
                    type: 'GET',
                    dataType: 'json',
                    params: {
                        _token: '{{ csrf_token() }}',
                        delivery_order_id: DeliveryOrderId
                    },
                    success: function(response) {
                        console.log("response", response);
                        $('#customer_id').val(response.customer_id).trigger('change');
                        $('#delivery_date').val(response.date);
                        $('#delivery_address').val(response.delivery_address);
                        rawMaterialCount = response.delivery_order_details.length;
                        $('#products_table tbody').empty();
                        // show product details from response data
                        $.each(response.delivery_order_details, function(index, value) {
                            let html = "";
                            html += `<tr>
                        <td>
                            <select class="select2" name="products[${index}][product_id]">
                                <option value="" disabled>Select Product</option>`;
                            $.each(allProducts, function(k, v) {

                                if (v.id == value.product_id) {
                                    html +=
                                        `<option value="${v.id}" selected>${v.text}</option>`;
                                } else {
                                    html +=
                                        `<option value="${v.id}">${v.text}</option>`;
                                }
                            })
                            html += `</select>

                        </td>
                        <td>

                            <input class="form-control" name="products[${index}][measuring_unit_id]" type="hidden" value="${value.product.unit_id}"/>
                            <input class="form-control" readonly type="text" value="${value.product.unit.name}"/>

                        </td>

                        <td>
                            <input type="number" name="products[${index}][quantity]" value="${value.remaining_quantity}" max="${value.remaining_quantity}" min="1" step="0.01" class="form-control quantity" id="quantity" onkeyup="calculcateProductTotal(this)" />
                        </td>

                        <td>
                            <input type="text" name="products[${index}][bundle_info]" value="" class="form-control bundle_info" id="bundle_info" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="ti-minus"></i>
                            </button>
                        </td>
                    </tr>`;
                            $("tbody").append(html);

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

                            $('.measuring_unit_id').last().select2();

                            $('.product_id').select2({
                                ajax: {
                                    url: "{{ route('allProducts') }}",
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

                        $('.select2').select2();
                    },
                });
            }

        });
        $(document).on("click", ".add-row", function() {
            var html = "";
            html += `<tr>
        <td>
            <select name="products[${rawMaterialCount}][product_id]" class="form-control product_id select2" id="raw_material_id">
                <option value="">Select Product Name</option>
            </select>
        </td>
        <td>
            {{ Form::select(
                'products[${rawMaterialCount}][measuring_unit_id]',
                $units,
                old('customer_id')
                    ? old('products[${rawMaterialCount}][measuring_unit_id]')
                    : (!empty($deliveryChallan->measuring_unit_id)
                        ? $deliveryChallan->measuring_unit_id
                        : null),
                [
                    'class' => 'form-control select2 measuring_unit_id',
                    'id' => 'measuring_unit_id',
                    'placeholder' => 'Select Unit',
                    'required',
                ],
            ) }}
        </td>


        <td>
            <input type="number" name="products[${rawMaterialCount}][quantity]" value="0" class="form-control quantity" id="quantity" onkeyup="calculcateProductTotal(this)" readonly/>
        </td>

        <td>
            <input type="text" name="products[${rawMaterialCount}][bundle_info]" value="" class="form-control bundle_info" id="bundle_info" />
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remove-row">
                <i class="ti-minus"></i>
            </button>
        </td>
    </tr>`;
            $("tbody").append(html);
            rawMaterialCount++;

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

            $('.measuring_unit_id').last().select2();

            $('.product_id').last().select2({
                ajax: {
                    url: "{{ route('allProducts') }}",
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
