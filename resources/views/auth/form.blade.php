@if ($errors->any())
    <div class="border border-danger text-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<div class="row">
    <div class="form-group col-sm-4">
        <label for="name">Admin Name</label>
        <input type="text" class="form-control" id="name" name="name"
        value="@if(isset($user)){{ $user->name }}@else{{ old('name') }}@endif" required>
    </div>
    <div class="form-group col-sm-4">
        <label for="email">Admin Email</label>
        <input type="email" class="form-control" id="email" name="email"
        value="@if(isset($user)){{ $user->email }}@else{{ old('email') }}@endif">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" @if(!isset($user)) required @endif>
    </div>
    <div class="form-group col-sm-4">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" @if(!isset($user)) required @endif>
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($user)) Update @else Create @endif">
    <input type="button" class="btn btn-danger ml-3" value="Cancel" onclick="window.history.back()">
</div>

