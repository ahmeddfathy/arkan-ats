@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card slide-up">
                <div class="card-header">
                    <h3 class="mb-0">Edit Position: {{ $job->title }}</h3>
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="{{ route('jobs.update', $job->id) }}"
                          class="needs-validation"
                          novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="category" class="form-label">Job Category</label>
                            <select name="category"
                                    id="category"
                                    class="form-select @error('category') is-invalid @enderror"
                                    required>
                                @foreach(['IT', 'Graphic Design', 'Marketing', 'Software Development', 'HR'] as $category)
                                    <option value="{{ $category }}"
                                            {{ old('category', $job->category) == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="title" class="form-label">Position Title</label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $job->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Job Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      required>{{ old('description', $job->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="requirements" class="form-label">Requirements</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror"
                                      id="requirements"
                                      name="requirements"
                                      rows="4"
                                      required>{{ old('requirements', $job->requirements) }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Position Status</label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="active" {{ old('status', $job->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status', $job->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                                <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>
                                    Closed
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Position
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>
@endsection
