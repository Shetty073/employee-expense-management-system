@extends('adminlte::page')

@section('title', 'Edit User - Abacus N Brain')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')

<div class="card px-3 py-1">
    <div class="card px-3 py-1">
        <form method="POST" action="{{ route('auth.update', ['id' => $user->id]) }}">
            @include('auth.form')
        </form>

    </div>
</div>

@stop


@section('css')

@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/forms.js') }}"></script>
    <script type="text/javascript">
        $("#per_session_amount_div").hide();
    </script>
@stop
