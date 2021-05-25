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
        <label for="name">Job Name</label>
        <input type="text" class="form-control" id="name" name="name"
        value="@if(isset($job)){{ $job->name }}@else{{ old('name') }}@endif" required>
    </div>
    <div class="form-group col-sm-4">
        <label for="number">Job Number</label>
        <input type="text" class="form-control" id="number" name="number"
        value="@if(isset($job)){{ $job->number }}@else{{ old('number') }}@endif" required>
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($job)) Update @else Create @endif">
    <input type="button" class="btn btn-danger ml-3" value="Cancel" onclick="window.history.back()">
</div>
