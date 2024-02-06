@extends('layouts.backend-layout')
@section('title', 'Processed Bonus')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb-title')
    List of Processed Bonus
@endsection


@section('breadcrumb-button')
    <a href="{{ route('bonus-process.create') }}" class="btn btn-out-dashed btn-sm btn-warning"><i
            class="fas fa-plus"></i></a>
@endsection
@section('sub-title')
    Total: {{ count($processed_bonuses) }}
@endsection


@section('content')
    <div class="dt-responsive table-responsive">
        <table id="dataTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#SL</th>
                    <th>Date</th>
                    <th>Department</th>
                    <th>Bonus</th>
                    <th>Bonus Amount</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#SL</th>
                    <th>Date</th>
                    <th>Department</th>
                    <th>Bonus Name</th>
                    <th>Action</th>
                </tr>
            </tfoot>
            <tbody class="text-center">
                @foreach ($processed_bonuses as $key => $processed_bonus)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $processed_bonus->date }}</td>
                        <td>{{ $processed_bonus->processedBonusDetails?->first()?->employee?->department?->name }}</td>
                        <td>{{ $processed_bonus->bonus?->name }}</td>


                        <td>
                            <div class="icon-btn">
                                <nobr>

                                    <a href="{{ url("hr/bonus-process/$processed_bonus->id") }}"
                                        data-toggle="tooltip" title="Details" class="btn btn-outline-primary"><i
                                            class="fas fa-info"></i></a>
                                    <a href="{{ route('bonus-process.edit', $processed_bonus->id) }}"
                                                data-toggle="tooltip" title="Edit" class="btn btn-outline-warning"><i
                                                    class="fas fa-pen"></i></a>
                                    <form action="{{ url("hr/bonus-process/$processed_bonus->id") }}"
                                        method="POST" data-toggle="tooltip" title="Delete" class="d-inline">
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
    <style scoped>
        .btn-group-sm>.btn {
            font-size: .575rem !important;
        }
    </style>
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
