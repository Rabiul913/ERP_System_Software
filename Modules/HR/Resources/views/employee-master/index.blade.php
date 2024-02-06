@extends('layouts.backend-layout')
@section('title', 'Employees')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset('css/Datatables/dataTables.bootstrap4.min.css')}}">
@endsection

@section('breadcrumb-title')
   List of Employees
@endsection

@section('style')
    <style>
    </style>
@endsection
@section('breadcrumb-button')
    <a href="{{ route('employee-masters.create')}}" class="btn btn-out-dashed btn-sm btn-warning"><i class="fas fa-plus"></i></a>
@endsection
@section('sub-title')
    Total: {{ count($employees) }}
@endsection


@section('content')
    <div class="dt-responsive table-responsive">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>#SL</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Department</th>
                <th>Section</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#SL</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Department</th>
                <th>Section</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </tfoot>
            <tbody>
            @foreach($employees as $key => $data)
                <tr>
                    <td>{{$key  + 1}}</td>
                    <td class="text-left">{{$data->emp_name}}-{{ $data->emp_code }}</td>
                    <td class="text-left">{{$data->designation->name}}</td>
                    <td class="text-left">{{$data->department->name}}</td>
                    <td class="text-left">{{$data->section->name}}</td>
                    <td class="text-left">{{$data->status}}</td>
                    <td>
                        <div class="icon-btn">
                            <nobr>
                                <a href="{{ route('employee-masters.edit', $data->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-outline-warning"><i class="fas fa-pen"></i></a>
                                <form action="{{ url("hr/employee-masters/$data->id") }}" method="POST" data-toggle="tooltip" title="Delete" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm delete" onclick="return confirm('Are you sure to delete this employee all information?');"><i class="fas fa-trash"></i></button>
                                </form>
                                <!-- <button onclick="confirmDelete({{ $data->id }})" class="btn btn-outline-danger btn-sm delete"><i class="fas fa-trash"></i></button> -->
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
    <script src="{{asset('js/Datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/Datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(window).scroll(function () {
            //set scroll position in session storage
            sessionStorage.scrollPos = $(window).scrollTop();
        });
        var init = function () {
            //get scroll position in session storage
            $(window).scrollTop(sessionStorage.scrollPos || 0)
        };
        window.onload = init;

        $(document).ready(function () {
            $('#dataTable').DataTable({
                stateSave: true
            });
        });
        
    </script>
        <script>
        function confirmDelete(id) {
            // alert(id);
            const firstConfirmation = confirm("Are you sure you want to delete this data?");

            if (firstConfirmation) {
                const secondConfirmation = confirm("This action for delete this employee all data. Are you absolutely sure you want to proceed with the deletion?");

                if (secondConfirmation) {
                    // If the user confirms the second confirmation, proceed with the delete request
                    const deleteForm = document.createElement("form");
                    deleteForm.method = "POST";
                    deleteForm.action = `employee-masters/${id}`;
                    deleteForm.style.display = "none";

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    const csrfInput = document.createElement("input");
                    csrfInput.type = "hidden";
                    csrfInput.name = "_token";
                    csrfInput.value = csrfToken;

                    const methodInput = document.createElement("input");
                    methodInput.type = "hidden";
                    methodInput.name = "_method";
                    methodInput.value = "DELETE";

                    deleteForm.appendChild(csrfInput);
                    deleteForm.appendChild(methodInput);
                    document.body.appendChild(deleteForm);

                    deleteForm.submit();
                } else {
                    // Handle the case when the user cancels the second confirmation
                    // For example, show a message or perform any other action as needed.
                }
            } else {
                // Handle the case when the user cancels the first confirmation
                // For example, show a message or perform any other action as needed.
            }
        }
    </script>
@endsection
