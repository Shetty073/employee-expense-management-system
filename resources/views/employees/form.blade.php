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
        <label for="name">Employee Name</label>
        <input type="text" class="form-control" id="name" name="name"
        value="@if(isset($employee)){{ $employee->name }}@else{{ old('name') }}@endif" required>
    </div>
    <div class="form-group col-sm-4">
        <label for="number">Employee Number</label>
        <input type="text" class="form-control" id="number" name="number"
        value="@if(isset($employee)){{ $employee->number }}@else{{ old('number') }}@endif" required>
    </div>
    <div class="form-group col-sm-4">
        <label for="email">Employee Email</label>
        <input type="email" class="form-control" id="email" name="email"
        value="@if(isset($employee)){{ $employee->email }}@else{{ old('email') }}@endif" required>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4">
        <label for="address">Employee Address</label>
        <input type="text" class="form-control" id="address" name="address"
        value="@if(isset($employee)){{ $employee->address }}@else{{ old('address') }}@endif" required>
    </div>
    <div class="form-group col-sm-4">
        <label for="bank_details">Employee Bank Details</label>
        <input type="text" class="form-control" id="bank_details" name="bank_details"
        value="@if(isset($employee)){{ $employee->bank_details }}@else{{ old('bank_details') }}@endif" required>
    </div>
    <div class="form-group col-sm-4">
        <label for="aadhar_card">Employee Aadhar Card</label>
        <input type="text" class="form-control" id="aadhar_card" name="aadhar_card"
        value="@if(isset($employee)){{ $employee->aadhar_card }}@else{{ old('aadhar_card') }}@endif" required>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4">
        <label for="pan_card">Employee Pan Card</label>
        <input type="text" class="form-control" id="pan_card" name="pan_card"
        value="@if(isset($employee)){{ $employee->pan_card }}@else{{ old('pan_card') }}@endif" required>
    </div>
    <div class="form-group col-sm-4">
        <label for="password">Employee Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="form-group col-sm-4">
        <label for="password_confirmation ">Employee Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation " name="password_confirmation " required>
    </div>

</div>

<div class="row">
    <div class="form-group col-sm-4">
        <label for="wallet_balance">Employee Wallet Balance</label>
        <input type="number" class="form-control" id="wallet_balance" name="wallet_balance"
        value="@if(isset($employee)){{ $employee->wallet_balance }}@else{{ old('wallet_balance') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label for="photo">Employee's Photo</label>
        <input type="file" class="form-control" id="photo" name="photo">
    </div>
    @if (isset($employee))
        @if ($employee->photo)
            <div class="form-group col-sm-1">
                <span>
                    <img width="60" height="60" src="/storage/student/{{ $employee->photo }}" class="img-thumbnail" alt="Student photo">
                </span>
            </div>
        @endif
    @endif
    <div class="form-group col-sm-3">
        <label for="resume">Employee's Resume</label>
        <input type="file" class="form-control" id="resume" name="resume">
    </div>
    @if (isset($employee))
        @if ($employee->resume)
            <div class="form-group col-sm-1">
                <span>
                    {{ $employee->resume }}
                </span>
            </div>
        @endif
    @endif
</div>



<div class="form-group mt-3">
    <input type="submit" class="btn btn-success" value="@if(isset($employee)) Update @else Save Partial Data @endif">
    <input type="button" class="btn btn-danger ml-3" value="Cancel" onclick="window.history.back()">
</div>
