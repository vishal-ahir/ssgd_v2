@extends('admin.layouts.app')
@section('title', __('Playlist'))
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
            <h3 class="mb-0">{{ __('Playlist List') }}</h3>
            @if($createBtnShow =='1')
            <a href="{{ route('admin.playlists.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                {{ __('Create Playlist') }}</a>
                @endif
        </div>

        <!-- DevExtreme DataGrid container -->
        <table id="playlistsTable" class="display text-center" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('Code') }}</th>
                    <th>{{ __('English Playlist') }}</th>
                    <th>{{ __('Gujarati Playlist') }}</th>
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
            $('#playlistsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.playlists.index') }}',
                columns: [{
                        data: 'playlist_code',
                        name: 'playlist_code',
                        orderable: false,
                    },
                    {
                        data: 'playlist_en',
                        name: 'playlist_en',
                        orderable: false,
                    },
                    {
                        data: 'playlist_gu',
                        name: 'playlist_gu',
                        orderable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let actionHtml = `
                            <a href="{{ url('admin/playlists') }}/${row.playlist_code}" class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                        `;

                            // Conditionally add the delete button if deleteBtn is 1
                            if (deleteBtn === '1') {
                                actionHtml += `
                                <form action="{{ route('admin.playlists.destroy', '') }}/${row.playlist_code}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            `;
                            }
                            // <a href="/admin/playlists/${row.playlist_code}/edit" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                            //     <i class="fas fa-edit"></i>
                            // </a>
                            return actionHtml;
                        }
                    }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf'
                ]
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
