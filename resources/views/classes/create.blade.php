@extends('adminlte::page')

@section('title', 'Create New Class')

@section('content_header')
    <h1>Create New Class</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('classes.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name">Class Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="teacher_id">Teacher</label>
                    @if($teachers->count() > 0)
                        <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @else
                        <div class="alert alert-warning">
                            No unassigned teachers available. <a href="{{ route('teachers.create') }}">Add a new teacher</a> first.
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create Class</button>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
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
