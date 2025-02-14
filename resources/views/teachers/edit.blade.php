@extends('adminlte::page')

@section('title', 'Edit Teacher')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Edit Teacher</h1>
        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="employee_id">Employee ID</label>
                            <input type="text" name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $teacher->employee_id) }}" required>
                            @error('employee_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profile_photo">Profile Photo</label>
                            <input type="file" name="profile_photo" id="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
                            @error('profile_photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if ($teacher->profile_photo)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($teacher->profile_photo) }}" 
                                         alt="Current Profile Photo" 
                                         class="img-thumbnail"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <p class="text-muted mt-1">Current photo</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $teacher->first_name) }}" required>
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $teacher->last_name) }}" required>
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $teacher->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $teacher->date_of_birth) }}" required>
                            @error('date_of_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $teacher->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $teacher->phone) }}" required>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="qualification">Qualification</label>
                            <input type="text" name="qualification" id="qualification" class="form-control @error('qualification') is-invalid @enderror" value="{{ old('qualification', $teacher->qualification) }}" required>
                            @error('qualification')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="salary">Salary</label>
                            <input type="number" step="0.01" name="salary" id="salary" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary', $teacher->salary) }}" required>
                            @error('salary')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="joining_date">Joining Date</label>
                    <input type="date" name="joining_date" id="joining_date" class="form-control @error('joining_date') is-invalid @enderror" value="{{ old('joining_date', $teacher->joining_date) }}" required>
                    @error('joining_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $teacher->address) }}</textarea>
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', $teacher->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $teacher->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Teacher</button>
                    <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // Preview image before upload
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'mt-2';
                    preview.innerHTML = `
                        <img src="${e.target.result}" 
                             alt="Profile Preview" 
                             class="img-thumbnail"
                             style="width: 100px; height: 100px; object-fit: cover;">
                        <p class="text-muted mt-1">Preview of new photo</p>
                    `;
                    
                    // Remove existing preview if any
                    const existingPreview = document.querySelector('#profile_photo').nextElementSibling.nextElementSibling;
                    if (existingPreview && existingPreview.classList.contains('mt-2')) {
                        existingPreview.replaceWith(preview);
                    } else {
                        document.querySelector('#profile_photo').parentNode.appendChild(preview);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@stop
