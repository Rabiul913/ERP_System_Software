@extends('layouts.backend-layout')
@section('title', 'User')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb-title')
    List of sales orders
@endsection

@section('style')
    <style>
    </style>
@endsection
@section('breadcrumb-button')
    <a href="{{ route('sales-orders.create') }}" class="btn btn-out-dashed btn-sm btn-warning"><i class="fas fa-plus"></i></a>
@endsection
@section('sub-title')
    Total: {{ count($salesOrders) }}
@endsection


@section('content')
    <div class="dt-responsive table-responsive">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#SL</th>
                    <th>SO No</th>
                    <th>Date</th>
                    <th>Product Details</th>
                    <th>Customer Name</th>
                    <th>Referance Employee</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#SL</th>
                    <th>SO No</th>
                    <th>Date</th>
                    <th>Product Details</th>
                    <th>Customer Name</th>
                    <th>Referance Employee</th>
                    <th>Action</th>
                </tr>
            </tfoot>
            <tbody>
                @foreach ($salesOrders as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td> SO-{{ $data->id }}</td>
                        <td>{{ $data->date }}</td>
                        <td>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Size</th>
                                        <th>Ratio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->salesOrderDetails as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->product->productType->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->size->name }}</td>
                                            <td>{{ $item->unit_price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td>{{ $data->customer->name }}</td>
                        <td>{{ $data->reference_employee_id }}</td>

                        <td>
                            <div class="icon-btn">
                                <nobr>
                                    <a href="{{ route('salesOrder.print', $data->id) }}" class="btn btn-outline-primary"
                                        data-toggle="tooltip" title="Download Pdf">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('sales-orders.edit', $data->id) }}" data-toggle="tooltip"
                                        title="Edit" class="btn btn-outline-warning"><i class="fas fa-pen"></i></a>
                                    <form action="{{ url("sales/sales-orders/$data->id") }}" method="POST"
                                        data-toggle="tooltip" title="Delete" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm delete"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
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
    <script src="{{ asset('js/Datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/Datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(window).scroll(function() {
            //set scroll position in session storage
            sessionStorage.scrollPos = $(window).scrollTop();
        });
        var init = function() {
            //get scroll position in session storage
            $(window).scrollTop(sessionStorage.scrollPos || 0)
        };
        window.onload = init;

        $(document).ready(function() {
            $('#dataTable').DataTable({
                stateSave: true
            });
        });
    </script>
@endsection
