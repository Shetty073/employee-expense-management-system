@extends('adminlte::page')

@section('title', 'Delete Bill Files')

@section('content_header')
    <h1>Showing bills for expense #{{ $expense->id }} in voucher no. {{ $expense->voucher->number }}</h1>
@stop

@section('content')

    <div class="card px-3 py-1">

        @foreach ($expense->bills as $bill)

        @endforeach

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Filename</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expense->bills as $bill)
                        <tr>
                            <td class="data">{{ $bill->file_name }}</td>
                            <td class="data">
                                <form action="{{ route('vouchers.deleteExpenseBills', ['id' => $bill->id]) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop


