@extends('layouts.app')

@section('title', 'Edit Content Template')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-body">

                <h5> {{ ucwords(__('Edit Content Template'))}} </h5> <hr>

                <form action="{{ route('template.update', $template->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="template_type" class="form-label">{{ __('Template Type') }}</label>
                        <input type="text" class="form-control" id="template_type" name="template_type" value="{{ old('template_type', $template->template_type) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="template_subject" class="form-label">{{ __('Template Subject') }}</label>
                        <input type="text" class="form-control" id="template_subject" name="template_subject" value="{{ old('template_subject', $template->template_subject) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="template_content" class="form-label">{{ __('Template Content') }}</label>
                        <textarea class="form-control" id="template_content" name="template_content" rows="5" required>{{ old('template_content', $template->template_content) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="role_type" class="form-label">{{ __('Role Type') }}</label>
                        <input type="text" class="form-control" id="role_type" name="role_type" value="{{ old('role_type', $template->role_type) }}" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const templateContentElement = document.querySelector('#template_content');
            if (templateContentElement) {
                ClassicEditor.create(templateContentElement)
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    </script>
@endpush