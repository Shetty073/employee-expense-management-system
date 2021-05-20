@extends('adminlte::page')

@section('title', 'Vouchers')

@section('content_header')
    <h1>Vouchers</h1>
@stop

@section('content')
    <p class="float-left">List of all of your vouchers is visible here.</p>
    <div class="float-right">
        <a href="{{ route('vouchers.create') }}" class="btn btn-primary">+ Create New Voucher</a>
    </div>

    <br><br><br>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Code</th>
                    <th scope="col">Email</th>
                    <th scope="col">Number</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Wallet Balance</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($vouchers as $voucher)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->code }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->number }}</td>
                    <td>
                        <img width="50" height="60" src="{{ asset('storage/employee/' . $employee->photo) }}" class="img-thumbnail" alt="Employee photo"/>
                    </td>
                    <td>{{ $employee->wallet_balance }}</td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ACTIONS
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item text-primary" href="{{ route('employees.edit', ['id' => $employee->id]) }}">Edit</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{ route('employees.destroy', ['id' => $employee->id]) }}">Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach --}}
            </tbody>
        </table>
    </div>
@stop

{{-- @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop --}}
