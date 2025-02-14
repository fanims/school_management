@extends('adminlte::page')

@section('title', 'Edit Fee Record')

@section('content_header')
    <h1>Edit Fee Record</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Fee Record #{{ $fee->id }}</h3>
        </div>
        <form action="{{ route('fees.update', $fee) }}" method="POST">
            @csrf
            @method('PUT')
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
                                {{ old('student_id', $fee->student_id) == $student->id ? 'selected' : '' }}
                                data-admission="{{ $student->admission_no }}"
                                data-class="{{ $student->class_id }}"
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
                            <option value="{{ $class->id }}" {{ old('class_id', $fee->class_id) == $class->id ? 'selected' : '' }}>
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
                        <input type="number" name="amount" id="amount" class="form-control" required readonly value="{{ old('amount', $fee->amount) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="paid_amount">Payable Amount</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control" required 
                            value="{{ old('paid_amount', $fee->paid_amount) }}" 
                            max="{{ $fee->amount }}"
                            oninput="calculatePendingAmount()">
                    </div>
                    <small class="text-muted">Maximum payable amount: ${{ number_format($fee->amount - $fee->paid_amount, 2) }}</small>
                    @error('paid_amount')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pending_amount">Pending Amount</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="number" id="pending_amount" class="form-control" readonly 
                            value="{{ $fee->amount - $fee->paid_amount }}">
                    </div>
                    <small id="pending_amount_text" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="payment_status">Payment Status</label>
                    <select name="payment_status" id="payment_status" class="form-control" required>
                        <option value="paid" {{ old('payment_status', $fee->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ old('payment_status', $fee->payment_status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ old('payment_status', $fee->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment_date">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" class="form-control" required 
                        value="{{ old('payment_date', $fee->payment_date ? date('Y-m-d', strtotime($fee->payment_date)) : date('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control" rows="3">{{ old('remarks', $fee->remarks) }}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update Fee Record</button>
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
            calculatePendingAmount();
        });

        function calculatePendingAmount() {
            const totalAmount = parseFloat($('#amount').val()) || 0;
            const paidAmount = parseFloat($('#paid_amount').val()) || 0;
            const pendingAmount = totalAmount - paidAmount;
            
            // Update pending amount
            $('#pending_amount').val(pendingAmount.toFixed(2));
            
            // Show pending amount message
            const pendingText = $('#pending_amount_text');
            if (pendingAmount > 0) {
                pendingText.text(`Remaining payment needed: $${pendingAmount.toFixed(2)}`);
                pendingText.show();
            } else {
                pendingText.hide();
            }
            
            // Update payment status automatically
            const status = paidAmount === 0 ? 'unpaid' : 
                          paidAmount >= totalAmount ? 'paid' : 'partial';
            $('#payment_status').val(status);

            // Validate payment amount
            const paidAmountInput = $('#paid_amount');
            if (paidAmount > totalAmount) {
                paidAmountInput.val(totalAmount);
                calculatePendingAmount();
            }
        }
    </script>
@stop
