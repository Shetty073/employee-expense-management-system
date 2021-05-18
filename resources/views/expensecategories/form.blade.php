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
        <label for="name">Category Name</label>
        <input type="text" class="form-control" id="name" name="name"
        value="@if(isset($expensecategory)){{ $expensecategory->name }}@else{{ old('name') }}@endif" required>
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($expensecategory)) Update @else Create @endif">
    <input type="button" class="btn btn-danger ml-3" value="Cancel" onclick="window.history.back()">
</div>
