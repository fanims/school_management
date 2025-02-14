@extends('adminlte::page')

@section('title', 'Edit Student')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Edit Student</h1>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="admission_no">Admission No</label>
                            <input type="text" name="admission_no" id="admission_no" class="form-control @error('admission_no') is-invalid @enderror" value="{{ old('admission_no', $student->admission_no) }}" required>
                            @error('admission_no')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="class_id">Class</label>
                            <select name="class_id" id="class_id" class="form-control @error('class_id') is-invalid @enderror" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="section">Section</label>
                            <input type="text" name="section" id="section" class="form-control @error('section') is-invalid @enderror" value="{{ old('section', $student->section) }}">
                            @error('section')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="admission_fee">Admission Fee</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" name="admission_fee" id="admission_fee" class="form-control fee-input @error('admission_fee') is-invalid @enderror" 
                                    value="{{ old('admission_fee', $student->admission_fee ?? '0.00') }}" step="0.01" min="0">
                            </div>
                            @error('admission_fee')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tuition_fee">Tuition Fee</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" name="tuition_fee" id="tuition_fee" class="form-control fee-input @error('tuition_fee') is-invalid @enderror" 
                                    value="{{ old('tuition_fee', $student->tuition_fee ?? '0.00') }}" step="0.01" min="0">
                            </div>
                            @error('tuition_fee')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_fee">Total Fee</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" name="total_fee" id="total_fee" class="form-control @error('total_fee') is-invalid @enderror" 
                                    value="{{ old('total_fee', $student->total_fee ?? '0.00') }}" step="0.01" min="0" readonly>
                            </div>
                            @error('total_fee')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $student->first_name) }}" required>
                            @error('first_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $student->last_name) }}" required>
                            @error('last_name')
                                <span class="invalid-feedback">{{ $message }}</span>
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
                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $student->date_of_birth) }}" required>
                            @error('date_of_birth')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $student->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $student->phone) }}" required>
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $student->address) }}</textarea>
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="parent_name">Parent Name</label>
                            <input type="text" name="parent_name" id="parent_name" class="form-control @error('parent_name') is-invalid @enderror" value="{{ old('parent_name', $student->parent_name) }}" required>
                            @error('parent_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="parent_phone">Parent Phone</label>
                            <input type="text" name="parent_phone" id="parent_phone" class="form-control @error('parent_phone') is-invalid @enderror" value="{{ old('parent_phone', $student->parent_phone) }}" required>
                            @error('parent_phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profile_photo">Profile Photo</label>
                            <input type="file" name="profile_photo" id="profile_photo" class="form-control-file @error('profile_photo') is-invalid @enderror">
                            @error('profile_photo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($student->profile_photo)
                            <div class="form-group">
                                <label>Current Photo</label>
                                <div>
                                    <img src="{{ asset('storage/' . $student->profile_photo) }}" alt="Current profile photo" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="{{ route('students.show', $student) }}" class="btn btn-default">Cancel</a>
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
        // Preview uploaded image
        document.getElementById('profile_photo').onchange = function(evt) {
            const [file] = this.files;
            if (file) {
                const preview = document.createElement('img');
                preview.src = URL.createObjectURL(file);
                preview.className = 'img-thumbnail mt-2';
                preview.style.maxHeight = '100px';
                
                const container = this.parentElement;
                const existingPreview = container.querySelector('img');
                if (existingPreview) {
                    container.removeChild(existingPreview);
                }
                container.appendChild(preview);
            }
        };

        $(document).ready(function() {
            // Function to calculate total fee
            function calculateTotalFee() {
                var admissionFee = parseFloat($('#admission_fee').val()) || 0;
                var tuitionFee = parseFloat($('#tuition_fee').val()) || 0;
                var totalFee = (admissionFee + tuitionFee).toFixed(2);
                $('#total_fee').val(totalFee);
            }

            // Calculate total fee when admission or tuition fee changes
            $('.fee-input').on('input', calculateTotalFee);

            // Initial calculation
            calculateTotalFee();
        });
    </script>
@stop
