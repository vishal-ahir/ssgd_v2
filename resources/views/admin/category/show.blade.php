@extends('admin.layouts.app')
@section('title', 'View Song')

@section('style')
    <style>
        .display {
            text-align: center;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        {{-- <h1>
            
        </h1> --}}

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><a href="{{ route('admin.categories.index') }}"><i class="fas fa-arrow-left"></i></a>
                &nbsp;&nbsp;Category Detail: {{ $category->{'category_' . app()->getLocale()} }}</h3>
            <a href="{{ route('admin.subCategories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create
                Sub Category</a>
        </div>

        <table id="categorySongTable" class="display text-center" style="width:100%">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>English Title</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const categoryId = "{{ $category->category_code }}";
            // console.log(categoryId);

            $('#categorySongTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.categories.show', ':id') }}'.replace(':id', categoryId),
                columns: [{
                        data: 'sub_category_code',
                        name: 'sub_category_code'
                    },
                    {
                        data: 'sub_category_en',
                        name: 'sub_category_en'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                    <a href="{{ url('admin/subCategories') }}/${row.sub_category_code}" class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    `;
                        }
                    }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf'
                ]
            });
        });
    </script>
@endsection
