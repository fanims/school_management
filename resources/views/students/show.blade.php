@extends('adminlte::page')

@section('title', 'Student Details')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Student Details</h1>
        <div>
            <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($student->profile_photo)
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ asset('storage/' . $student->profile_photo) }}"
                                 alt="Student profile picture">
                        @else
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ asset('images/default-avatar.svg') }}"
                                 alt="Default profile picture">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h3>

                    <p class="text-muted text-center">Student</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Admission No.</b> <a class="float-right">{{ $student->admission_no }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Class</b> 
                            <a href="{{ route('classes.show', $student->class) }}" class="float-right">
                                {{ $student->class->name }} {{ $student->class->section }}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right badge badge-{{ $student->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#details" data-toggle="tab">Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#attendance" data-toggle="tab">Attendance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#fees" data-toggle="tab">Fees</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="details">
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Gender</h5>
                                    <p>{{ ucfirst($student->gender) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Date of Birth</h5>
                                    <p>{{ $student->date_of_birth }}</p>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Class</h5>
                                    <p>{{ $student->class->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Section</h5>
                                    <p>{{ $student->section ?: 'Not Assigned' }}</p>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Religion</h5>
                                    <p>{{ $student->religion ?: 'Not Specified' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Address</h5>
                                    <p>{{ $student->address }}</p>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Phone</h5>
                                    <p>{{ $student->phone }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Email</h5>
                                    <p>{{ $student->email }}</p>
                                </div>
                            </div>

                            <hr>
                            <h5>Parent/Guardian Information</h5>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Parent Name</h5>
                                    <p>{{ $student->parent_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Parent Phone</h5>
                                    <p>{{ $student->parent_phone }}</p>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Parent Occupation</h5>
                                    <p>{{ $student->parent_occupation }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="attendance">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($student->attendances()->latest()->take(10)->get() as $attendance)
                                            <tr>
                                                <td>{{ $attendance->date }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $attendance->status === 'present' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($attendance->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $attendance->remarks }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No attendance records found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane" id="fees">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fee Type</th>
                                            <th>Amount</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($student->fees()->latest()->take(10)->get() as $fee)
                                            <tr>
                                                <td>{{ $fee->fee_type }}</td>
                                                <td>{{ $fee->amount }}</td>
                                                <td>{{ $fee->due_date }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $fee->status === 'paid' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($fee->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($fee->status !== 'paid')
                                                        <a href="{{ route('fees.edit', $fee) }}" 
                                                           class="btn btn-sm btn-primary">
                                                            Pay Now
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No fee records found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Activate first tab
            $('#details').tab('show');
        });
    </script>
@stop
