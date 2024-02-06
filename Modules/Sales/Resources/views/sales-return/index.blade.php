@extends('layouts.backend-layout')
@section('title', 'Sales Return')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/Datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb-title')
List of Sales Return
@endsection

@section('style')
<style>
</style>
@endsection
@section('breadcrumb-button')
<a href="{{ route('sales-returns.create') }}" class="btn btn-out-dashed btn-sm btn-warning"><i class="fas fa-plus"></i></a>
@endsection
@section('sub-title')
Total: {{ count($sales_return) }}
@endsection


@section('content')
<div class="dt-responsive table-responsive">
    <table id="dataTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#SL</th>
                <th>Delivery Challan No</th>
                <th>Sales Return no</th>
                <th>Product Details</th>
                <th>Return Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#SL</th>
                <th>Delivery Challan No</th>
                <th>Sales Return no</th>
                <th>Product Details</th>
                <th>Return Date</th>

                <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($sales_return as $key => $data)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>DCN-{{ $data->delivery_challan_id??'' }}</td>
                <td>SRN-{{ $data->id }}</td>
                <td>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>

                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Return Rate</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->sales_return_details as $item)
                            <tr>
                                <td>{{ $item->product->name??'' }}</td>
                                <td>{{ $item->measuringUnit->name??'' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->return_price }}</td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td>{{ $data->return_date }}</td>

                <td>
                    <div class="icon-btn">
                        <nobr>

                            <!-- <a href="{{ route('sales-returns.edit', $data->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-outline-warning"><i class="fas fa-pen"></i></a> -->

                            <form action="{{ url("sales/sales-returns/$data->id") }}" method="POST" data-toggle="tooltip" title="Delete" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm delete"><i class="fas fa-trash"></i></button>
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
