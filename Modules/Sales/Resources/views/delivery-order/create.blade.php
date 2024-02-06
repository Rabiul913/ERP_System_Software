@extends('layouts.backend-layout')
@section('title', 'Delivery Order')

@section('breadcrumb-title')
@if ($formType == 'edit')
Edit
@else
Create
@endif
Delivery Order
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
<a class="btn btn-out-dashed btn-sm btn-warning" href="{{ route('delivery-orders.index') }}"><i class="fas fa-database"></i></a>
@endsection

@section('sub-title')
<span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'col-md-12 col-lg-12 col-sm-12 my-3')

@section('content')
@if ($formType == 'create')
{!! Form::open([
'url' => 'sales/delivery-orders',
'method' => 'POST',
'class' => 'custom-form',
'files' => true,
'enctype' => 'multipart/form-data',
]) !!}
@else
{!! Form::open([
'url' => "sales/delivery-orders/$deliveryOrder->id",
'method' => 'PUT',
'class' => 'custom-form',
'files' => true,
'enctype' => 'multipart/form-data',
]) !!}
@endif

<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="input-group input-group-sm input-group-primary">
            <label style="" class="input-group-addon" for="date">Date <span class="text-danger">*</span></label>
            {{ Form::date(
                    'date',
                    old('date') ? old('date') : (!empty($deliveryOrder->date) ? $deliveryOrder->date : now()->format('Y-m-d')),
                    [
                        'class' => 'form-control',
                        'id' => 'date',
                        'placeholder' => 'Enter Date Here',
                        'required',
                    ],
                ) }}
            @error('date')
            <p class="text-danger">{{ $errors->first('date') }}</p>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="input-group input-group-sm input-group-primary">
            <label style="" class="input-group-addon" for="customer_id">Customer <span class="text-danger">*</span></label>
            {{ Form::select(
                    'customer_id',
                    $customers,
                    old('customer_id')
                        ? old('customer_id')
                        : (!empty($deliveryOrder->customer_id)
                            ? $deliveryOrder->customer_id
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
            <label style="" class="input-group-addon" for="contact_no">Contact No <span class="text-danger">*</span></label>
            {{ Form::text(
                    'contact_no',
                    old('contact_no') ? old('contact_no') : (!empty($deliveryOrder->contact_no) ? $deliveryOrder->contact_no : null),
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


    {{-- <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="address">Address <span
                        class="text-danger">*</span></label>
                {{ Form::textarea(
                    'address',
                    old('address') ? old('address') : (!empty($deliveryOrder->address) ? $deliveryOrder->address : null),
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
</div> --}}
<div class="col-md-4 col-sm-12">
    <div class="input-group input-group-sm input-group-primary">
        <label style="" class="input-group-addon" for="employee_id">Sales Person <span class="text-danger">*</span></label>
        {{ Form::select(
                    'employee_id',
                    $employees,
                    old('employee_id')
                        ? old('employee_id')
                        : (!empty($deliveryOrder->employee_id)
                            ? $deliveryOrder->employee_id
                            : null),
                    [
                        'class' => 'form-control select2',
                        'id' => 'employee_id',
                        'placeholder' => 'Sales Person',
                        'required',
                    ],
                ) }}
        @error('employee_id')
        <p class="text-danger">{{ $errors->first('employee_id') }}</p>
        @enderror
    </div>
</div>

{{-- <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="employee_id">Sales Person Name <span
                        class="text-danger">*</span></label>
                {{ Form::text(
                    'employee_id',
                    old('employee_id')
                        ? old('employee_id')
                        : (!empty($deliveryOrder->employee_id)
                            ? $deliveryOrder->employee_id
                            : null),
                    [
                        'class' => 'form-control',
                        'id' => 'employee_id',
                        'placeholder' => 'Enter Sales Person Name Here',
                        'required',
                    ],
                ) }}
@error('employee_id')
<p class="text-danger">{{ $errors->first('employee_id') }}</p>
@enderror
</div>
</div> --}}

<div class="col-md-4 col-sm-12">
    <div class="input-group input-group-sm input-group-primary">
        <label class="input-group-addon" for="delivery_method">Delivery Method <span class="text-danger">*</span></label>
        {{ Form::select(
                    'delivery_method',
                    [
                        'EX Factory' => 'EX Factory',
                        'Side Delivery' => 'Side Delivery',
                    ],
                    old('delivery_method')
                        ? old('delivery_method')
                        : (!empty($deliveryOrder->delivery_method)
                            ? $deliveryOrder->delivery_method
                            : null),
                    [
                        'class' => 'form-control select2',
                        'id' => 'delivery_method',
                        'placeholder' => 'Select Delivery Method',
                        'required',
                    ],
                ) }}
        @error('delivery_method')
        <p class="text-danger">{{ $errors->first('delivery_method') }}</p>
        @enderror
    </div>
</div>

<div class="col-md-4 col-sm-12">
    <div class="input-group input-group-sm input-group-primary">
        <label style="" class="input-group-addon" for="delivery_address">Delivery Address <span class="text-danger">*</span></label>
        {{ Form::textarea(
                    'delivery_address',
                    old('delivery_address')
                        ? old('delivery_address')
                        : (!empty($deliveryOrder->delivery_address)
                            ? $deliveryOrder->delivery_address
                            : null),
                    [
                        'class' => 'form-control',
                        'id' => 'delivery_address',
                        'placeholder' => 'Enter Delevery Address Here',
                        'required',
                    ],
                ) }}
        @error('delivery_address')
        <p class="text-danger">{{ $errors->first('delivery_address') }}</p>
        @enderror
    </div>
</div>

<div class="col-md-4 col-sm-12">
    <div class="input-group input-group-sm input-group-primary">
        <label class="input-group-addon" for="delivery_method">Zone <span class="text-danger">*</span></label>
        {{ Form::select(
                    'zone_id',
                    $zones,
                    old('zone_id')
                        ? old('zone_id')
                        : (!empty($deliveryOrder->zone_id)
                            ? $deliveryOrder->zone_id
                            : null),
                    [
                        'class' => 'form-control select2',
                        'id' => 'zone_id',
                        'placeholder' => 'Select Zone',
                        'required',
                    ],
                ) }}
        @error('zone_id')
        <p class="text-danger">{{ $errors->first('zone_id') }}</p>
        @enderror
    </div>
</div>
</div><!-- end row -->
<hr class="bg-success">

<div class="row">
    <table class="table table-striped table-bordered" id="raw_materials_table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product Unit</th>
                <!-- <th>Product Size</th> -->
                <th>Quantity</th>
                <th>Price(Tk)</th>
                <th>Total Amount</th>
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
                    <select name="raw_materials[0][product_id]" class="form-control product_id select2" id="raw_material_id_0" onchange="LoadUnitByProductId('0');">
                        <option value="">Select Product Name</option>
                    </select>
                </td>

                <td>

                    <input type="text" class="form-control raw_material_unit_text" id="raw_material_unit_text_0" readonly>
                    <!--
                            {{ Form::select(
                                'raw_materials[0][unit_id]',
                                $units,
                                old('customer_id')
                                    ? old('raw_materials[0][unit_id]')
                                    : (!empty($deliveryOrder->unit_id)
                                        ? $deliveryOrder->unit_id
                                        : null),
                                [
                                    'class' => 'form-control select2',
                                    'id' => 'unit_id_0',
                                    'placeholder' => 'Select Unit',
                                    'required',
                                ],
                            ) }} -->
                </td>

                <td>
                    <input type="number" name="raw_materials[0][quantity]" value="0" min="1" class="form-control quantity" id="quantity" onchange="calculcateProductTotal(this)" />
                    <input type="hidden" name="raw_materials[0][remaining_quantity]" value="0" min="1" class="form-control remaining_quantity" id="remaining_quantity" />
                </td>
                <td>
                    <input type="number" name="raw_materials[0][unit_price]" value="0" step="0.01" min="0" class="form-control unit_price" id="unit_price" onchange="calculcateProductTotal(this)" />
                </td>
                <td><input type="text" value="" class="form-control total_amount" id="total_amount" readonly /></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="ti-minus"></i>
                    </button>
                </td>

            </tr>
            @else
            @foreach (old('raw_materials', $deliveryOrder->deliveryOrderDetails ?? []) as $index => $product)
            <tr>
                <td>
                    <select class="select2" name="raw_materials[{{ $index }}][product_id]" onchange="LoadUnitByProductId('{{ $index }}');">
                        <option value="" disabled>Select Product</option>
                        @foreach ($products as $key => $name)
                        <option value="{{ $key }}" @selected($key==$product->product_id)>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control raw_material_unit_text" id="raw_material_unit_text_{{ $index }}" readonly>
                    <!-- <select name="raw_materials[{{ $index }}][unit_id]" class="form-control product_unit_id" id="product_unit_id">
                        <option value="" disabled></option>
                        @foreach ($units as $key => $name)
                        <option value="{{ $key }}" @selected($key==$product->unit_id)>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select> -->
                </td>

                <td>
                    <input type="number" name="raw_materials[{{ $index }}][quantity]" value="{{ $product->quantity }}" class="form-control quantity" id="raw_material_quantity" onchange="calculcateProductTotal(this)" />
                    <input type="hidden" name="raw_materials[0][remaining_quantity]" value="0" min="1" class="form-control remaining_quantity" id="remaining_quantity" />
                </td>
                <td>
                    <input type="number" name="raw_materials[{{ $index }}][unit_price]" value="{{ $product->unit_price }}" class="form-control unit_price" id="raw_material_unit_price" onchange="calculcateProductTotal(this)" min="0" step="0.01" />
                </td>
                <td>
                    <input type="text" value="{{ $product->unit_price * $product->quantity }}" class="form-control total_amount" id="raw_material_total_amount" readonly />
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
        <tfoot class="border-none">
            <tr class="">
                <td colspan="2" class="text-right">Total</td>
                <td colspan="">
                    <input type="text" value="" class="form-control total-qty" readonly />
                </td>
                <td></td>
                <td colspan="2">
                    <input type="text" value="" class="form-control total-price" readonly />
                </td>
            </tr>

            <tr class="border-0 d-none">
                <td colspan="4" class="text-right border-0">Vat</td>


                <td colspan="2" class="border-0">
                    <input type="number" name="vat" value="{{ old('vat') ? old('vat') : (!empty($deliveryOrder->vat) ? $deliveryOrder->vat : 0) }}" onchange="calculateSubTotal()" class="form-control vat" />
                </td>
            </tr>
            <tr class="d-none">
                <td colspan="4" class="text-right">Tax</td>
                <td colspan="2">
                    <input type="number" name="tax" value="{{ old('tax') ? old('tax') : (!empty($deliveryOrder->tax) ? $deliveryOrder->tax : 0) }}" onchange="calculateSubTotal()" value="" class="form-control tax" />
                </td>
            </tr>

            <tr class="d-none">
                <td colspan="4" class="text-right">Vehicle Rent</td>
                <td colspan="2">
                    <input type="number" name="rent" onchange="calculateSubTotal()" value="{{ old('rent') ? old('rent') : (!empty($deliveryOrder->rent) ? $deliveryOrder->rent : 0) }}" class="form-control rent" />
                </td>
            </tr class="d-none">

            <tr class="d-none">
                <td colspan="4" class="text-right">Labor Cost</td>
                <td colspan="2">
                    <input type="number" name="labor_cost" onchange="calculateSubTotal()" value="{{ old('labor_cost') ? old('labor_cost') : (!empty($deliveryOrder->labor_cost) ? $deliveryOrder->labor_cost : 0) }}" class="form-control labor" />
                </td>
            </tr>

            <tr class="d-none">
                <td colspan="4" class="text-right">Discount</td>
                <td colspan="2">
                    <input type="number" name="discount" onchange="calculateSubTotal()" value="{{ old('discount') ? old('discount') : (!empty($deliveryOrder->discount) ? $deliveryOrder->discount : 0) }}" class="form-control discount" />
                </td>
            </tr>

            <tr class="d-none">
                <td colspan="4" class="text-right">Sub Total</td>
                <td colspan="2">
                    <input type="number" onchange="calculateSubTotal()" value="" class="form-control sub_total" readonly />
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Previous Due</td>
                <td colspan="2">
                    <input type="number" onchange="calculateSubTotal()" value="" class="form-control previous_due" readonly />
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Grand Total</td>
                <td colspan="2">
                    <input type="number" name="total" onchange="calculateSubTotal()" value="" class="form-control grand_total" readonly />
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Paid</td>
                <td colspan="2">
                    <input type="number" name="paid" onchange="calculateSubTotal()" value="{{ old('paid') ? old('paid') : (!empty($deliveryOrder->paid) ? $deliveryOrder->paid : 0) }}" class="form-control paid" />
                </td>
            </tr>
            <tr style="border-top: 1px solid #ebdbdb;">
                <td colspan="4" class="text-right">Due</td>
                <td colspan="2">
                    <input type="number" name="due" onchange="calculateSubTotal()" value="" class="form-control due" readonly />
                </td>
            </tr>

        </tfoot>
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
    let deliveryOrder = JSON.parse('<?= isset($deliveryOrder) ? $deliveryOrder : '{}' ?>');
    let rawMaterialCount = (Object.keys(deliveryOrder) != 0 && deliveryOrder.delivery_order_details
        .length > 0) ? deliveryOrder.delivery_order_details.length : 1;

    let pageType = "{{ $formType }}";

    $(document).on('change', '#customer_id', function() {
        let customer_id = $(this).val();
        $.ajax({
            url: "{{ route('getCustomerDetails') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                customer_id: customer_id

            },
            success: function(response) {
                console.log("response", response);
                $('#contact_no').val(response.contact_no);
                $('#address').val(response.address);
                $('#employee_id').val(response.employee.id).change();
                $('#zone_id').val(response.zone_id).change();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);
            }
        });
    });

    $(document).ready(function() {
        if (pageType == 'edit') {
            let totalQty = 0;
            let totalPrice = 0;

            deliveryOrder.delivery_order_details.forEach(function(item, index) {
                totalQty += parseInt(item.quantity);
                totalPrice += parseInt(item.unit_price);
            });
            // console.log("totalQty", totalQty);

            $('.total-qty').val(totalQty);
            $('.total-price').val(totalPrice);

        }
        calculcateProductTotal();
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);
            }
        });
    });

    $(document).on("click", ".add-row", function() {
        var html = "";
        html += `<tr>
                        <td>
                            <select name="raw_materials[${rawMaterialCount}][product_id]" class="form-control product_id select2"
                                id="raw_material_id_${rawMaterialCount}" onchange="LoadUnitByProductId('${rawMaterialCount}');">
                                <option value="">Select Product Name</option>
                            </select>
                        </td>

                        <td>
                            <input type="text" class="form-control raw_material_unit_text" id="raw_material_unit_text_${rawMaterialCount}" readonly>

                        </td>


                        <td>
                            <input type="number" name="raw_materials[${rawMaterialCount}][quantity]" value="0"
                                class="form-control quantity" id="quantity" onchange="calculcateProductTotal(this)" min="1" />
                            <input type="hidden" name="raw_materials[${rawMaterialCount}][remaining_quantity]" value="0"
                                class="form-control remaining_quantity" id="remaining_quantity" />
                        </td>
                        <td>
                            <input type="number" name="raw_materials[${rawMaterialCount}][unit_price]" value="0"
                                class="form-control unit_price" id="unit_price" onchange="calculcateProductTotal(this)" step="0.01" min="0" />
                        </td>
                        <td><input type="text" value="" class="form-control total_amount" id="total_amount"
                                readonly />
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="ti-minus"></i>
                            </button>
                        </td>

                    </tr>`;
        $("tbody").append(html);


        $(`#product_size_id_${rawMaterialCount}`).select2({
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

        $(`#unit_id_${rawMaterialCount}`).select2();

        $(`#raw_material_id_${rawMaterialCount}`).select2({
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

        rawMaterialCount++;
    });

    $(document).on("click", ".remove-row", function() {
        $(this).closest("tr").remove();
    });


    const calculcateProductTotal = element => {
        var row = element != null ? element.parentNode.parentNode : document.querySelector(
            "#raw_materials_table tbody tr");
        var price = parseFloat(row.querySelector(".unit_price").value);
        var quantity = parseFloat(row.querySelector(".quantity").value);

        row.querySelector(".remaining_quantity").value = quantity;
        // $('.remaining_quantity').val(quantity);
        var total = price * quantity;
        row.querySelector(".total_amount").value = total.toFixed(2);
        calculateTotal();
    }

    const calculateTotal = () => {
        var total_qty = 0;
        var total_price = 0;
        var rows = document.querySelectorAll("#raw_materials_table tbody tr");
        rows.forEach(function(row) {
            var qty = parseFloat(row.querySelector(".quantity").value);
            var price = parseFloat(row.querySelector(".unit_price").value);
            total_qty += qty;
            total_price += price * qty;
        });
        document.querySelector(".total-qty").value = total_qty;
        document.querySelector(".total-price").value = total_price.toFixed(2);
        calculateSubTotal();
    }


    const calculateSubTotal = () => {

        var total_qty = 0;
        var total_price = 0;
        var rows = document.querySelectorAll("#raw_materials_table tbody tr");
        rows.forEach(function(row) {
            var qty = parseFloat(row.querySelector(".quantity").value);
            var price = parseFloat(row.querySelector(".unit_price").value);
            total_qty += qty;
            total_price += price * qty;
        });
        document.querySelector(".total-qty").value = total_qty;
        document.querySelector(".total-price").value = total_price.toFixed(2);

        var vat = parseFloat(document.querySelector(".vat").value || 0);
        var tax = parseFloat(document.querySelector(".tax").value || 0);
        var rent = parseFloat(document.querySelector(".rent").value || 0);
        var labor = parseFloat(document.querySelector(".labor").value || 0);
        var discount = parseFloat(document.querySelector(".discount").value || 0);
        var sub_total = total_price + vat + tax + rent + labor - discount;
        document.querySelector(".sub_total").value = sub_total.toFixed(2);

        var previous_due = parseFloat(document.querySelector(".previous_due").value || 0);
        var grand_total = sub_total + previous_due;
        document.querySelector(".grand_total").value = grand_total.toFixed(2);
        var paid = parseFloat(document.querySelector(".paid").value || 0);
        var due = sub_total + previous_due - paid;
        document.querySelector(".due").value = due.toFixed(2);
        // getPreviousDue();
    }

    const getPreviousDue = () => {
        var customer_id = document.querySelector("#customer_id").value;
        $.ajax({
            url: "{{ route('getPreviousDue') }}",
            type: 'GET',
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                customer_id: customer_id
            },
            success: function(response) {
                document.querySelector(".previous_due").value = response;
                calculateSubTotal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);
            }
        });
    }

    function LoadUnitByProductId(rowid) {
        let product_id = $(`#raw_material_id_${rowid}`).val();

        $.ajax({
            url: '{{ route("product.getUnitNameByProductId") }}',
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'product_id': product_id
            },
            success: function(data) {

                $(`#raw_material_unit_text_${rowid}`).val(data);

            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
</script>

@endsection
