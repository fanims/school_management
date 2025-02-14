@extends('adminlte::page')

@section('title', 'Take Attendance')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Take Attendance - {{ $class->name }}</h1>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Back to Classes</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Attendance for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($students->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No students found in this class.
                </div>
            @else
                <form action="{{ route('attendance.bulkStore') }}" method="POST">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $class->id }}">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px">No.</th>
                                    <th>Student Name</th>
                                    <th>Admission No</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td>{{ $student->admission_no }}</td>
                                        <td>
                                            <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-outline-success {{ $student->attendances->first()?->status === 'present' ? 'active' : '' }}">
                                                    <input type="radio" 
                                                           name="attendances[{{ $index }}][status]" 
                                                           value="present" 
                                                           {{ $student->attendances->first()?->status === 'present' ? 'checked' : '' }}
                                                           required> Present
                                                </label>
                                                <label class="btn btn-outline-danger {{ $student->attendances->first()?->status === 'absent' ? 'active' : '' }}">
                                                    <input type="radio" 
                                                           name="attendances[{{ $index }}][status]" 
                                                           value="absent" 
                                                           {{ $student->attendances->first()?->status === 'absent' ? 'checked' : '' }}> Absent
                                                </label>
                                                <label class="btn btn-outline-warning {{ $student->attendances->first()?->status === 'late' ? 'active' : '' }}">
                                                    <input type="radio" 
                                                           name="attendances[{{ $index }}][status]" 
                                                           value="late" 
                                                           {{ $student->attendances->first()?->status === 'late' ? 'checked' : '' }}> Late
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="attendances[{{ $index }}][remarks]" 
                                                   class="form-control"
                                                   value="{{ $student->attendances->first()?->remarks }}"
                                                   placeholder="Optional remarks">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Attendance
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-group-toggle .btn {
            min-width: 80px;
        }
        .btn-group-toggle .btn.active {
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-submit on radio button change (optional)
            // $('input[type="radio"]').change(function() {
            //     $(this).closest('form').submit();
            // });
        });
    </script>
@stop
