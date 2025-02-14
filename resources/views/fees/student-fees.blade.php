@extends('adminlte::page')

@section('title', 'Student Fees')

@section('content_header')
    <h1>Student Fees</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Fee Records for {{ $student->full_name }}</h3>
            <div class="card-tools">
                <a href="{{ route('fees.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Fee
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Student Name</th>
                            <td>{{ $student->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Registration Number</th>
                            <td>{{ $student->registration_number }}</td>
                        </tr>
                        <tr>
                            <th>Current Class</th>
                            <td>{{ $student->class->name ?? 'Not Assigned' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Total Fees</th>
                            <td>{{ number_format($fees->sum('amount'), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total Paid</th>
                            <td>{{ number_format($fees->sum('paid_amount'), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total Due</th>
                            <td>{{ number_format($fees->sum('amount') - $fees->sum('paid_amount'), 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fee Type</th>
                        <th>Class</th>
                        <th>Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fees as $fee)
                        <tr>
                            <td>{{ $fee->id }}</td>
                            <td>{{ ucfirst($fee->fee_type) }}</td>
                            <td>{{ $fee->class->name }}</td>
                            <td>{{ number_format($fee->amount, 2) }}</td>
                            <td>{{ number_format($fee->paid_amount, 2) }}</td>
                            <td>{{ $fee->due_date }}</td>
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
                            <td>
                                <a href="{{ route('fees.show', $fee) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($fee->status !== 'paid')
                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#paymentModal{{ $fee->id }}">
                                        <i class="fas fa-money-bill"></i>
                                    </button>
                                @endif
                                <a href="{{ route('fees.edit', $fee) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('fees.destroy', $fee) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Payment Modal -->
                        @if($fee->status !== 'paid')
                            <div class="modal fade" id="paymentModal{{ $fee->id }}" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel{{ $fee->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('fees.pay', $fee) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="paymentModalLabel{{ $fee->id }}">Record Payment</h5>
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
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $fees->links() }}
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
