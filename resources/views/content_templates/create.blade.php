@extends('layouts.app')

@section('title', 'Create Content Template')

@section('content')
    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">Add New Content Template</h4>
                <form action="{{ route('template.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="template_type" class="form-label">{{ __('Template Type') }}</label>
                        <input type="text" class="form-control" id="template_type" name="template_type" value="{{ old('template_type') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="template_subject" class="form-label">{{ __('Template Subject') }}</label>
                        <input type="text" class="form-control" id="template_subject" name="template_subject" value="{{ old('template_subject') }}" >
                    </div>

                    <div class="mb-3">
                        <label for="template_content" class="form-label">{{ __('Template Content') }}</label>
                        <textarea class="form-control" id="template_content" name="template_content" rows="5" required>{{ old('template_content') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="role_type" class="form-label">{{ __('Role Type') }}</label>
                        <input type="text" class="form-control" id="role_type" name="role_type" value="General Content Triggers" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ __('Create Template') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const templateContentElement = document.querySelector('#template_content');
        let editor;

        if (templateContentElement) {
            // Initialize CKEditor
            ClassicEditor.create(templateContentElement)
                .then(ckEditor => {
                    editor = ckEditor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // Add event listener to the form's submit event
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (event) {
                // Before submitting the form, ensure the CKEditor content is written back to the textarea
                if (editor) {
                    // Set the content of the textarea to CKEditor content
                    templateContentElement.value = editor.getData();
                }

                // Optionally check if the content is empty (if you have validation in place)
                if (!templateContentElement.value.trim()) {
                    event.preventDefault();
                    alert('Template Content cannot be empty!');
                }
            });
        }
    });
</script>
@endpush

