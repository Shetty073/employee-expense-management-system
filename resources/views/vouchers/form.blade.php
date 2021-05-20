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
        <label for="job">Select Job</label>
        <select class="custom-select" id="job" name="job[]" multiple>
            @foreach ($jobs as $job)
                <option value="{{ $job->id }}">{{ $job->number }} - {{ $job->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-4">
        <label for="voucherdate">Voucher Date</label>
        <input type="date" class="form-control" id="voucherdate" name="voucherdate" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
        value="@if(isset($voucher)){{ $voucher->voucherdate }}@else{{ old('voucherdate') }}@endif" required>
    </div>
</div>

<div class="form-group mt-3">
    <input type="submit" class="btn btn-success" value="Create">
    <input type="button" class="btn btn-danger ml-3" value="Cancel" onclick="window.history.back()">
</div>
