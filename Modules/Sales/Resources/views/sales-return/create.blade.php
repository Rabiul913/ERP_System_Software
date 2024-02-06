@extends('layouts.backend-layout')
@section('title', 'Sales Return')

@section('breadcrumb-title')
    @if ($formType == 'edit')
        Edit
    @else
        Create
    @endif
    Sales Return
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
    <a class="btn btn-out-dashed btn-sm btn-warning" href="{{ route('sales-returns.index') }}"><i
            class="fas fa-database"></i></a>
@endsection

@section('sub-title')
    <span class="text-danger">*</span> Marked are required.
@endsection

@section('content-grid', 'col-md-12 col-lg-12 col-sm-12 my-3')

@section('content')
    @if ($formType == 'create')
        {!! Form::open([
            'url' => 'sales/sales-returns',
            'method' => 'POST',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @else
        {!! Form::open([
            'url' => "sales/sales-returns/$sales_return->id",
            'method' => 'PUT',
            'class' => 'custom-form',
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
    @endif

    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="so_no">Delivery Challan <span
                        class="text-danger">*</span></label>
                <?php
                $select2Class = 'form-control select2';
                if ($formType === 'edit') {
                    $select2Class .= ' select2-readonly';
                }
                ?>
                {{ Form::select(
                    'delivery_challan_id',
                    $deliveryChallans,
                    old('delivery_challan_id')
                        ? old('delivery_challan_id')
                        : (!empty($sales_return->delivery_challan_id)
                            ? $sales_return->delivery_challan_id
                            : null),
                    [
                        'class' => $select2Class,
                        'id' => 'delivery_challan_id',
                        'placeholder' => 'Select DCN No Here',
                        'required',
                    ],
                ) }}
                @error('delivery_challan_id')
                    <p class="text-danger">{{ $errors->first('delivery_challan_id') }}</p>
                @enderror
            </div>
        </div>


        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="date">Return Date <span
                        class="text-danger">*</span></label>
                {{ Form::date(
                    'return_date',
                    old('return_date') ? old('return_date') : (!empty($sales_return->return_date) ? $sales_return->return_date : null),
                    [
                        'class' => 'form-control',
                        'id' => 'return_date',
                        'placeholder' => 'Enter return date Here',
                        'required',
                    ],
                ) }}
                @error('return_date')
                    <p class="text-danger">{{ $errors->first('return_date') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="customer_name">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" disabled>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="date">Delievery Date</label>
                <input type="text" class="form-control" id="delivery_date" disabled>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="so_no">Reciveing Warehouse <span
                        class="text-danger">*</span></label>
                {{ Form::select(
                    'warehouse_id',
                    $warehouses,
                    old('warehouse_id')
                        ? old('warehouse_id')
                        : (!empty($sales_return->warehouse_id)
                            ? $sales_return->warehouse_id
                            : null),
                    [
                        'class' => $select2Class,
                        'id' => 'warehouse_id',
                        'placeholder' => 'Select Warehouse Here',
                        'required',
                    ],
                ) }}
                @error('warehouse_id')
                    <p class="text-danger">{{ $errors->first('warehouse_id') }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="input-group input-group-sm input-group-primary">
                <label style="" class="input-group-addon" for="reason">Reason</label>
                {{ Form::text(
                    'reason',
                    old('reason') ? old('reason') : (!empty($sales_return->reason) ? $sales_return->reason : null),
                    [
                        'class' => 'form-control',
                        'id' => 'reason',
                        'placeholder' => 'Enter reason Here',
                    ],
                ) }}
                @error('reason')
                    <p class="text-danger">{{ $errors->first('reason') }}</p>
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
                    <th>Quantity</th>
                    <th>Return Price</th>

                    <th>
                        <button type="button" class="btn btn-sm add-row" disabled>
                            <i class="ti-plus"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
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
        let pageType = "{{ $formType }}";

        // let deliveryChallan = JSON.parse('<?= isset($deliveryChallan) ? $deliveryChallan : '{}' ?>');
        //let rawMaterialCount = (Object.keys(deliveryChallan) != 0 && deliveryChallan.delivery_challan_details.length > 0) ? deliveryChallan.delivery_challan_details.length : 1;

        $(document).ready(function() {
            if (pageType != 'create') {

            }
        });

        $(document).on("change", "#delivery_challan_id", function() {
            let DeliveryOrderId = $(this).val();

            $.ajax({
                url: `/sales/get-delivery-challan-details/${DeliveryOrderId}`,
                type: 'GET',
                dataType: 'json',
                params: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log("response", response);
                    $('#customer_name').val(response.customer.name);
                    $('#delivery_date').val(response.delivery_date);

                    let detailsCount = response.delivery_challan_details.length;
                    $('#products_table tbody').empty();
                    // show product details from response data
                    $.each(response.delivery_challan_details, function(index, value) {
                        let html = "";

                        html += `
                    <tr>
                        <td>
                            <input type="text" class="form-control" id="product_name" value="${value.product.name}" disabled>
                            <input type="hidden" class="form-control" id="product_id" name="sales_return_details[${index}][product_id]" value="${value.product_id}">
                        </td>

                        <td>
                            <input type="text" class="form-control" id="measuring_unit_name" value="${value.measuring_unit.name}" disabled>
                            <input type="hidden" class="form-control" id="measuring_unit_id" name="sales_return_details[${index}][measuring_unit_id]" value="${value.measuring_unit_id}">
                        </td>
                       
                        <td>
                            <input type="number" name="sales_return_details[${index}][quantity]" class="form-control quantity" id="quantity" value="${value.quantity}" min="1" max="${value.quantity}"/>
                        </td>
                        <td>
                            <input type="number" name="sales_return_details[${index}][return_price]" step="0.01" class="form-control unit_price" id="return_price" value="${value.unit_price}" min="0" max="${value.unit_price}"/>
                        </td>


                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="ti-minus"></i>
                            </button>
                        </td>

                    </tr>
                    `;

                        $("tbody").append(html);
                    });
                },
            });
        });


        $(document).on("click", ".remove-row", function() {
            $(this).closest("tr").remove();
        });
    </script>

@endsection
