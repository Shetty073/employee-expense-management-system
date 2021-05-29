@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Expense Categories</h1>
@stop

@section('content')
    <p>List of all expense categories is visible here.</p>

    <div class="card px-3 py-1">

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Category Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expensecategories as $expensecategory)
                    <tr>
                        <td>{{ $expensecategory->name }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ACTIONS
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item text-primary" href="{{ route('expensecategories.edit', ['id' => $expensecategory->id]) }}">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger deleteBtn" href="{{ route('expensecategories.destroy', ['id' => $expensecategory->id]) }}">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop --}}

@section('js')
    <script src="{{ asset('js/delete.js') }}"></script>
@stop
