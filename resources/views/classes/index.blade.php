@extends('adminlte::page')

@section('title', 'Classes List')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Classes</h1>
        <a href="{{ route('classes.create') }}" class="btn btn-primary">Add New Class</a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Section</th>
                            <th>Teacher</th>
                            <th>Capacity</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr>
                                <td>{{ $class->id }}</td>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->section }}</td>
                                <td>
                                    @if ($class->teacher)
                                        <a href="{{ route('teachers.show', $class->teacher) }}">
                                            {{ $class->teacher->first_name }} {{ $class->teacher->last_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">No teacher assigned</span>
                                    @endif
                                </td>
                                <td>{{ $class->capacity }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $class->students->count() }} / {{ $class->capacity }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $class->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($class->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('classes.show', $class) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('classes.edit', $class) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('classes.destroy', $class) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this class?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No classes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $classes->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // Auto close alerts after 3 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 3000);
        });
    </script>
@stop
