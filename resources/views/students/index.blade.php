@extends('adminlte::page')

@section('title', 'Students List')

@section('content_header')
    <h1>Students</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Students List</h3>
            <div class="card-tools">
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Student
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Admission No</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->admission_no }}</td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->class->name ?? 'N/A' }}</td>
                            <td>{{ $student->section ?: 'N/A' }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone }}</td>
                            <td>
                                <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $students->links() }}
            </div>
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
