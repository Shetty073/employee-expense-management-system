@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Employees</h1>
@stop

@section('content')
<p>List of all your employees is visible here.</p>

<div class="card px-3 py-1">
    <div class="my-3">
        <a class="btn btn-success float-right" href="{{ route('employees.create') }}">+ Create New Employee</a>
    </div>
    <br>
    <input type="text" id="searchBox" placeholder="🔍 Search the table below">
    <br>

    <div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Code</th>
                    <th scope="col">Email</th>
                    <th scope="col">Number</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Aadhar Card</th>
                    <th scope="col">Wallet Balance</th>
                    <th scope="col">Password</th>
                    <th scope="col">Account</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->code }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->number }}</td>
                    <td>
                        <span>
                            <a href="{{ asset('storage/employee/' . $employee->photo) }}">
                                <img width="50" height="60" src="{{ asset('storage/employee/' . $employee->photo) }}"
                                    class="img-thumbnail" alt="Employee photo" />
                            </a>
                        </span>
                    </td>
                    <td>
                        <span>
                            <a href="{{ asset('storage/employee/' . $employee->aadhar_photo) }}">
                                <img width="50" height="60"
                                    src="{{ asset('storage/employee/' . $employee->aadhar_photo) }}"
                                    class="img-thumbnail" alt="Employee aadhar photo" />
                            </a>
                        </span>
                    </td>
                    <td>
                        <span
                            class="badge @if($employee->wallet_balance > 0) badge-primary @else badge-warning @endif px-2 py-2"
                            style="font-size: 1.2rem;">
                            ₹ {{ $employee->wallet_balance }}
                        </span>
                    </td>
                    <td>
                        {{ $employee->password }}
                    </td>
                    <td>
                        @if ($employee->user->active)
                            <span
                                class="badge badge-success px-2 py-2"
                                style="font-size: 1rem;">
                                Active
                            </span>
                        @else
                            <span
                                class="badge badge-danger px-2 py-2"
                                style="font-size: 1rem;">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ACTIONS
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item text-primary"
                                    href="{{ route('employees.addbalance', ['id' => $employee->id]) }}">Add Balance</a>
                                <a class="dropdown-item text-primary"
                                    href="{{ route('employees.edit', ['id' => $employee->id]) }}">Edit</a>
                                <div class="dropdown-divider"></div>
                                @if($employee->user->active)
                                    <a class="dropdown-item text-danger deactivateBtn"
                                        href="{{ route('employees.destroy', ['id' => $employee->id]) }}">
                                        Deactivate
                                    </a>
                                @else
                                    <a class="dropdown-item text-success activateBtn"
                                        href="{{ route('employees.activate', ['id' => $employee->id]) }}">
                                        Activate
                                    </a>
                                @endif
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

@section('js')
    <script src="{{ asset('js/tableFilter.js') }}"></script>
    <script src="{{ asset('js/empactdeact.js') }}"></script>
@stop
