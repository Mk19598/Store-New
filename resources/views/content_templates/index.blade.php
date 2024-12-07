@extends('layouts.app')

@section('title', $title)

@section('content')

    <div>

        {{-- Success Message --}}
        @if (session('success'))
            <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="card">
            <div class="card-body">
                <h5> {{ ucwords(__('Template'))}} </h5> <hr>

                <div class="table-responsive">
                    <table class="table table-striped" id="template-list-table">
                        <thead>
                            <tr>
                                <th>{{ ucwords(__('Select')) }}</th>
                                <th>#</th>
                                <th>{{ ucwords(__('Template Type')) }}</th>
                                <th>{{ ucwords(__('Subject')) }}</th>
                                <th>{{ ucwords(__('Role Type')) }}</th>
                                <th>{{ ucwords(__('Actions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templates as $key => $template)
                                <tr>
                                    <td><input class="form-check-input template-checkbox" type="checkbox" value="{{ $template->id }}"></td>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $template->template_type }}</td>
                                    <td>{{ $template->template_subject }}</td>
                                    <td>{{ $template->role_type }}</td>
                                    <td>
                                        {{-- Edit button --}}
                                        <a href="{{ route('template.edit', $template->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i> 
                                        </a>

                                        {{-- Delete button --}}
                                        <form action="{{ route('template.destroy', $template->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="bi bi-trash"></i> 
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#template-list-table').DataTable();
        });
    
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 5000); 
    </script>
@endpush
