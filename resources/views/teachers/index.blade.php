@extends('adminlte::page')

@section('title', 'Teachers List')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Teachers</h1>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary">Add New Teacher</a>
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
                            <th>Photo</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Qualification</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->id }}</td>
                                <td>
                                    @if ($teacher->profile_photo && Storage::disk('public')->exists($teacher->profile_photo))
                                        <img src="{{ Storage::url($teacher->profile_photo) }}" 
                                             alt="{{ $teacher->first_name }}'s Photo" 
                                             class="img-thumbnail"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('images/default-avatar.svg') }}" 
                                             alt="Default Profile" 
                                             class="img-thumbnail"
                                             style="width: 50px; height: 50px; object-fit: cover; background-color: #f8f9fa;">
                                    @endif
                                </td>
                                <td>{{ $teacher->employee_id }}</td>
                                <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                                <td>{{ $teacher->email }}</td>
                                <td>{{ $teacher->phone }}</td>
                                <td>{{ $teacher->qualification }}</td>
                                <td>
                                    <span class="badge badge-{{ $teacher->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($teacher->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teachers.show', $teacher) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('teachers.edit', $teacher) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('teachers.destroy', $teacher) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this teacher?');">
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
                                <td colspan="9" class="text-center">No teachers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $teachers->links() }}
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
