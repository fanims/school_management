@extends('adminlte::page')

@section('title', 'Create Fee Record')

@section('content_header')
    <h1>Create Fee Record</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add New Fee Record</h3>
        </div>
        <form action="{{ route('fees.store') }}" method="POST">
            @csrf
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select name="student_id" id="student_id" class="form-control select2" required>
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" 
                                {{ old('student_id') == $student->id ? 'selected' : '' }}
                                data-admission="{{ $student->admission_no }}"
                                data-class="{{ $student->class->id }}"
                                data-section="{{ $student->section }}"
                                data-tuition="{{ $student->tuition_fee }}">
                                {{ $student->admission_no }} - {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="admission_no">Admission Number</label>
                    <input type="text" name="admission_no" id="admission_no" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="class_id">Class</label>
                    <select name="class_id" id="class_id" class="form-control select2" required readonly>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="section">Section</label>
                    <input type="text" name="section" id="section" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="amount">Tuition Fee</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="number" name="amount" id="amount" class="form-control" required readonly>
                        <input type="hidden" name="fee_type" value="tuition">
                    </div>
                    @error('amount')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="due_date">Current Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control" required value="{{ old('due_date', date('Y-m-d')) }}" readonly>
                    @error('due_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Payment Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">Select Status</option>
                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                <div id="partial_payment_section" style="display: none;">
                    <div class="form-group">
                        <label for="paid_amount">Paid Amount</label>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control" value="{{ old('paid_amount') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Create Fee Record</button>
                <a href="{{ route('fees.index') }}" class="btn btn-default float-right">Cancel</a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Set current date if due_date is empty
            if (!$('#due_date').val()) {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0');
                var yyyy = today.getFullYear();
                today = yyyy + '-' + mm + '-' + dd;
                $('#due_date').val(today);
            }

            // Update student-related fields when student is selected
            $('#student_id').change(function() {
                var selectedOption = $(this).find('option:selected');
                
                // Set admission number
                var admissionNo = selectedOption.data('admission');
                $('#admission_no').val(admissionNo);
                
                // Set class
                var classId = selectedOption.data('class');
                $('#class_id').val(classId).trigger('change');
                
                // Set section
                var section = selectedOption.data('section');
                $('#section').val(section || 'Not Assigned');
                
                // Set tuition fee
                var tuitionFee = selectedOption.data('tuition');
                $('#amount').val(tuitionFee || 0);
            });

            // Set initial values if a student is already selected
            if ($('#student_id').val()) {
                $('#student_id').trigger('change');
            }

            // Show/hide partial payment section based on status
            $('#status').change(function() {
                if ($(this).val() === 'partial') {
                    $('#partial_payment_section').show();
                } else {
                    $('#partial_payment_section').hide();
                }
            });

            // Trigger initial status change
            $('#status').trigger('change');
        });
    </script>
@stop
