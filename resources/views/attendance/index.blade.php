@extends('adminlte::page')

@section('title', 'Attendance')

@section('content_header')
    <h1>Attendance</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Select Class for Attendance</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($classes as $class)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $class->name }}</h5>
                                <p class="card-text">
                                    <strong>Teacher:</strong> {{ $class->teacher->full_name ?? 'Not Assigned' }}<br>
                                    <strong>Students:</strong> {{ $class->students->count() }}
                                </p>
                                <form action="{{ route('attendance.byClass', $class) }}" method="GET" class="mt-3">
                                    <div class="form-group">
                                        <label for="date_{{ $class->id }}">Select Date</label>
                                        <input type="date" 
                                               id="date_{{ $class->id }}" 
                                               name="date" 
                                               class="form-control" 
                                               value="{{ date('Y-m-d') }}" 
                                               max="{{ date('Y-m-d') }}"
                                               required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-clipboard-check"></i> Take Attendance
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($classes->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No active classes found. Please create a class first.
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
        // Initialize any JavaScript functionality here
    </script>
@stop
