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
        <select class="form-control js-example-basic-multiple" id="job" name="job[]" multiple="multiple">
            @foreach ($jobs as $job)
                <option value="{{ $job->id }}" @if(isset($voucher)) @if(in_array($job->id, $voucherids)) selected @endif @endif>
                    {{ $job->number }} - {{ $job->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group mt-3">
    <input type="submit" class="btn btn-success" value="Create">
    <input type="button" class="btn btn-danger ml-3" value="Cancel" onclick="window.history.back()">
</div>
