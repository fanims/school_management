@extends('adminlte::page')

@section('title', 'Fee Record Details')

@section('content_header')
    <h1>Fee Record Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Fee Record #{{ $fee->id }}</h3>
            <div class="card-tools">
                <a href="{{ route('fees.edit', $fee) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('fees.index') }}" class="btn btn-default">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <h5>Student Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Student Name</th>
                            <td>{{ $fee->student->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Registration Number</th>
                            <td>{{ $fee->student->registration_number }}</td>
                        </tr>
                        <tr>
                            <th>Class</th>
                            <td>{{ $fee->class->name }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Fee Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Fee Type</th>
                            <td>{{ ucfirst($fee->fee_type) }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td>{{ number_format($fee->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Paid Amount</th>
                            <td>{{ number_format($fee->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Remaining Amount</th>
                            <td>{{ number_format($fee->amount - $fee->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            <td>{{ $fee->due_date }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'paid' => 'success',
                                        'partial' => 'warning',
                                        'unpaid' => 'danger'
                                    ][$fee->status];
                                @endphp
                                <span class="badge badge-{{ $statusClass }}">
                                    {{ ucfirst($fee->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($fee->remarks)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Remarks</h5>
                        <div class="card">
                            <div class="card-body">
                                {{ $fee->remarks }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($fee->status !== 'paid')
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#paymentModal">
                            <i class="fas fa-money-bill"></i> Record Payment
                        </button>
                    </div>
                </div>

                <!-- Payment Modal -->
                <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('fees.pay', $fee) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Remaining Amount</label>
                                        <input type="text" class="form-control" readonly value="{{ number_format($fee->amount - $fee->paid_amount, 2) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="paid_amount">Payment Amount</label>
                                        <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control" required max="{{ $fee->amount - $fee->paid_amount }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="check">Check</option>
                                            <option value="online">Online Payment</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="transaction_id">Transaction ID</label>
                                        <input type="text" name="transaction_id" id="transaction_id" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Record Payment</button>
                                </div>
                            </form>
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
