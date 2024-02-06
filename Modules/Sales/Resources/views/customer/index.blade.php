@extends('layouts.backend-layout')
@section('title', 'Customers')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb-title')
    List of Customers
@endsection

@section('style')
    <style>
    </style>
@endsection
@section('breadcrumb-button')
    <a href="{{ route('customers.create') }}" class="btn btn-out-dashed btn-sm btn-warning"><i class="fas fa-plus"></i></a>
@endsection
@section('sub-title')
    Total: {{ count($customers) }}
@endsection


@section('content')
    <div class="dt-responsive table-responsive">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#SL</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Business Type</th>
                    <th>Bin No</th>
                    <th>Tin No</th>
                    <th>Trade License</th>
                    <th>Limit</th>
                    <th>Country</th>
                    <th>Contact Person Name</th>
                    <th>NID no</th>
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#SL</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Business Type</th>
                    <th>Bin No</th>
                    <th>Tin No</th>
                    <th>Trade License</th>
                    <th>Limit</th>
                    <th>Country</th>
                    <th>Contact Person Name</th>
                    <th>NID no</th>
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </tfoot>
            <tbody>
                @foreach ($customers as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td class="text-left">{{ $data->name }}</td>
                        <td class="text-left">{{ $data->address }}</td>
                        <td class="text-left">{{ $data->business_type }}</td>
                        <td class="text-left">{{ $data->bin_no }}</td>
                        <td class="text-left">{{ $data->tin_no }}</td>
                        <td class="text-left">{{ $data->trade_license }}</td>
                        <td class="text-left">{{ $data->limit }}</td>
                        <td class="text-left">{{ $data->country }}</td>
                        <td class="text-left">{{ $data->contact_person_name }}</td>
                        <td class="text-left">{{ $data->nid_no }}</td>
                        <td class="text-left">{{ $data->contact_no }}</td>
                        <td class="text-left">{{ $data->email }}</td>
                        <td class="text-left">{{ $data->status }}</td>
                        <td>
                            <div class="icon-btn">
                                <nobr>
                                    <a href="{{ route('customers.edit', $data->id) }}" data-toggle="tooltip" title="Edit"
                                        class="btn btn-outline-warning"><i class="fas fa-pen"></i></a>
                                    <form action="{{ url("sales/customers/$data->id") }}" method="POST"
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
