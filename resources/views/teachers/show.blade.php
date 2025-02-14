@extends('adminlte::page')

@section('title', 'Teacher Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Teacher Details</h1>
        <div>
            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if ($teacher->profile_photo && Storage::disk('public')->exists($teacher->profile_photo))
                        <img src="{{ Storage::url($teacher->profile_photo) }}" 
                             alt="{{ $teacher->first_name }}'s Photo" 
                             class="img-fluid rounded-circle mb-3"
                             style="width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-avatar.svg') }}" 
                             alt="Default Profile" 
                             class="img-fluid rounded-circle mb-3"
                             style="width: 200px; height: 200px; object-fit: cover; background-color: #f8f9fa;">
                    @endif
                    <h3>{{ $teacher->first_name }} {{ $teacher->last_name }}</h3>
                    <p class="text-muted">{{ $teacher->qualification }}</p>
                    <span class="badge badge-{{ $teacher->status === 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($teacher->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Personal Information</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Employee ID</th>
                            <td>{{ $teacher->employee_id }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ ucfirst($teacher->gender) }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ \Carbon\Carbon::parse($teacher->date_of_birth)->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Joining Date</th>
                            <td>{{ \Carbon\Carbon::parse($teacher->joining_date)->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $teacher->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $teacher->phone }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $teacher->address }}</td>
                        </tr>
                        <tr>
                            <th>Salary</th>
                            <td>{{ number_format($teacher->salary, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($teacher->classes->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Assigned Classes</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Class Name</th>
                                        <th>Section</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->classes as $class)
                                        <tr>
                                            <td>{{ $class->name }}</td>
                                            <td>{{ $class->section }}</td>
                                            <td>
                                                <span class="badge badge-{{ $class->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($class->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('classes.show', $class) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if($teacher->subjects && $teacher->subjects->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Teaching Subjects</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject Name</th>
                                        <th>Code</th>
                                        <th>Class</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->subjects as $subject)
                                        <tr>
                                            <td>{{ $subject->name }}</td>
                                            <td>{{ $subject->code }}</td>
                                            <td>{{ $subject->class->name ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('subjects.show', $subject) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
