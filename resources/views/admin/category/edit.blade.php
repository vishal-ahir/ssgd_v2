@extends('admin.layouts.app')
@section('title', __('Edit') . ' - ' . $category->{'category_' . app()->getLocale()})

@section('content')
    <div class="container-fluid">
        <h3 class="mt-4 mb-4">{{ __('Edit Category') }}</h3>
        <form action="{{ route('admin.categories.update', $category->category_code) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <!-- Title (English) -->
                <div class="col-md-6 col-12 mb-3">
                    <label for="category_en" class="col-form-label">{{ __('English Category') }}</label>
                    <input type="text" class="form-control @error('category_en') is-invalid @enderror" id="category_en"
                        name="category_en" value="{{ old('category_en', $category->category_en) }}" required>
                    @error('category_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Title (Gujarati) -->
                <div class="col-md-6 col-12 mb-3">
                    <label for="category_gu" class="col-form-label">{{ __('English Category') }}</label>
                    <input type="text" class="form-control" id="category_gu" name="category_gu"
                        value="{{ old('category_gu', $category->category_gu) }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 col-12">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary ml-2">{{ __('Cancel') }}</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
