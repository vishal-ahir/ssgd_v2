@extends('admin.layouts.app')
@section('title', __('Category'))
@section('style')
    <style>
        .display {
            text-align: center;
            /* Center-aligns text in the table */
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
            /* Padding for pagination buttons */
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">{{ __('Category List') }}</h3>
            @if($createBtnShow =='1')
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                {{ __('Create Category') }}</a>
                @endif
        </div>

        <!-- DevExtreme DataGrid container -->
        <table id="userTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('Code') }}</th>
                    <th>{{ __('English Category') }}</th>
                    <th>{{ __('Gujarati Category') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
        </table>
    </div>

@endsection
@section('script')
    <script>
        const deleteBtn = @json($deleteBtn);

        $(document).ready(function() {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.categories.index') }}',
                columns: [{
                        data: 'category_code',
                        name: 'category_code'
                    },
                    {
                        data: 'category_en',
                        name: 'category_en',
                        orderable: false,
                    },
                    {
                        data: 'category_gu',
                        name: 'category_gu',
                        orderable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let actionHtml = `
                            <a href="/admin/categories/${row.category_code}" class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/admin/categories/${row.category_code}/edit" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        `;

                            // Check if deleteBtn is 1, then add delete button
                            if (deleteBtn === '1') {
                                actionHtml += `
                                <form action="{{ route('admin.categories.destroy', '') }}/${row.category_code}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            `;
                            }

                            return actionHtml;
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
