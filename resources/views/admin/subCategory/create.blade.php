@extends('admin.layouts.app')
@section('title', 'Create Sub Category')
@section('content')
    <h1 class="mt-4 mb-4">Add New Sub Category</h1>
    <form action="{{ route('admin.subCategories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="category_code">Category</label>
            <select class="form-control select2" id="category_code" name="category_code[]" multiple="multiple"
                data-placeholder="Select Categories">
                @foreach ($categories as $category)
                    <option value="{{ $category->category_code }}">
                        {{ $category->category_en }} ({{ $category->category_gu }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="category_en">Sub Category (English)</label>
            <input type="text" class="form-control id="sub_category_en" name="sub_category_en"
                placeholder="Enter English Sub Category" required>
        </div>

        <div class="form-group">
            <label for="category_gu">Sub Category (Gujarati)</label>
            <input type="text" class="form-control" id="sub_category_gu" placeholder="Enter Gujarati Sub Category"
                name="sub_category_gu">
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.subCategories.index') }}" class="btn btn-secondary ml-2">Cancel</a>
    </form>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select Categories',
                allowClear: true
            });
        });
    </script>
@endsection
