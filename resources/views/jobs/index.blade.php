@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Jobs</h1>
@stop

@section('content')
<p>List of all jobs is visible here.</p>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Job Name</th>
                <th scope="col">Job Number</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobs as $job)
            <tr>
                <td>{{ $job->name }}</td>
                <td>{{ $job->number }}</td>
                <td>
                    <div class="dropdown">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ACTIONS
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item text-primary"
                                href="{{ route('jobs.edit', ['id' => $job->id]) }}">Edit</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger"
                                href="{{ route('jobs.destroy', ['id' => $job->id]) }}">Delete</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@stop

{{-- @section('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@stop --}}

{{-- @section('js')
    <script> console.log('Hi!'); </script>
@stop --}}
