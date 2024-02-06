@extends('layouts.backend-layout')
@section('title', 'Sales Person Target')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/Datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb-title')
List of Sales Person Target
@endsection

@section('style')
<style>
</style>
@endsection
@section('breadcrumb-button')
<a href="{{ route('sales-person-targets.create') }}" class="btn btn-out-dashed btn-sm btn-warning"><i class="fas fa-plus"></i></a>
@endsection
@section('sub-title')
Total: {{ count($sales_person_target) }}
@endsection


@section('content')
<div class="dt-responsive table-responsive">
    <table id="dataTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#SL</th>
                <th>Month</th>
                <th>Details</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#SL</th>
                <th>Month</th>
                <th>Details</th>
                <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($sales_person_target as $key => $data)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $data->month }}</td>
                <td>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Employee Name</th>

                                <th>Target</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->target_order_details as $item)
                            <tr>
                                <td>{{ $item->employee->emp_name??'' }}</td>
                                <td>{{ $item->target }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>

                <td>
                    <div class="icon-btn">
                        <nobr>
                            <a href="{{ route('sales-person-targets.edit', $data->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-outline-warning"><i class="fas fa-pen"></i></a>
                            <form action="{{ url("sales/sales-person-targets/$data->id") }}" method="POST" data-toggle="tooltip" title="Delete" class="d-inline">
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

<script></script>
@endsection
