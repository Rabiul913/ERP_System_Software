@extends('layouts.backend-layout')
@section('title', 'Delivery Order')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/Datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb-title')
List of Delivery Orders
@endsection

@section('style')
<style>
</style>
@endsection
@section('breadcrumb-button')
<a href="{{ route('delivery-orders.create') }}" class="btn btn-out-dashed btn-sm btn-warning"><i class="fas fa-plus"></i></a>
@endsection
@section('sub-title')
Total: {{ count($deliveryOrders) }}
@endsection


@section('content')
<div class="dt-responsive table-responsive">
    <table id="dataTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#SL</th>
                <th>DO No</th>
                <th>Date</th>
                <th>Customer Name</th>
                <th>Referance Employee</th>
                <!-- <th>Product Details</th> -->
                <th>Delivery Address</th>
                <!-- <th>Vat</th>
                    <th>Tax</th>
                    <th>Labor Cost</th>
                    <th>Rent</th>
                    <th>Discount</th> -->
                <th>Total</th>
                <th>Paid </th>
                <th>Due</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#SL</th>
                <th>DO No</th>
                <th>Date</th>
                <th>Customer Name</th>
                <th>Referance Employee</th>
                <!-- <th>Product Details</th> -->
                <th>Delivery Address</th>
                <!-- <th>Vat</th>
                    <th>Tax</th>
                    <th>Labor Cost</th>
                    <th>Rent</th>
                    <th>Discount</th> -->
                <th>Total</th>
                <th>Paid </th>
                <th>Due</th>
                <th>Action</th>
            </tr>
        </tfoot>
        <tbody>

            @foreach ($deliveryOrders as $key => $data)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td> DO-{{ $data->id }}</td>
                <td>{{ $data->date }}</td>
                <td>{{ $data->customer->name }}</td>
                <td>
                    @if ($data->reference_employee_id)
                    {{ $data->employee->name }}
                    @endif
                </td>
                <!-- <td>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>

                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th>Rem. Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->deliveryOrderDetails as $item)
                            <tr>
                                <td>{{ $item->product?->name }}</td>

                                <td>{{ $item->unit_price }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->remaining_quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td> -->
                <td>{{ $data->delivery_address }}</td>
                <!-- <td>{{ $data->vat }}</td>
                        <td>{{ $data->tax }}</td>
                        <td>{{ $data->labor_cost }}</td>
                        <td>{{ $data->rent }}</td>
                        <td>{{ $data->discount }}</td> -->
                <td>{{ $data->total }}</td>
                <td>{{ $data->paid }}</td>
                <td>{{ $data->due }}</td>
                <td>
                    <div class="icon-btn">
                        <nobr>
                            <a target="_blank" href="{{ route('deliveryOrder.print', $data->id) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="print with value">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <a target="_blank" href="{{ route('deliveryOrder.print-without-value', $data->id) }}" class="btn btn-outline-dark" data-toggle="tooltip" title="print without value">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            @if(count($data->deliveryChallans)==0)
                            <a href="{{ route('delivery-orders.edit', $data->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-outline-warning"><i class="fas fa-pen"></i></a>

                            <form action="{{ url("sales/delivery-orders/$data->id") }}" method="POST" data-toggle="tooltip" title="Delete" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm delete"><i class="fas fa-trash"></i></button>
                            </form>
                            @else
                            @if ($data->status == 0)
                            <a href='{{ url("sales/delivery-orders/complete/$data->id") }}' class="btn btn-outline-warning" data-toggle="tooltip" title="Complete the Delivery Order">Complete</a>
                            @else
                            <span class="label label-warning" data-toggle="tooltip" title="{{ ($data->completed_by)? 'Completed By '.$data->completedby->name : 'Completed' }}">Completed</span>
                            @endif
                            @endif


                        </nobr>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('script')

<script></script>
@endsection
