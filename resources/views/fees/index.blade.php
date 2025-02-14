@extends('adminlte::page')

@section('title', 'Fees Management')

@section('content_header')
    <h1>Fees Management</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Fee Records</h3>
            <div class="card-tools">
                <a href="{{ route('fees.create') }}" class="btn btn-primary">
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

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Fee Type</th>
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
                            <td>{{ $fee->student->full_name }}</td>
                            <td>{{ $fee->class->name }}</td>
                            <td>{{ $fee->fee_type }}</td>
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
                                                    <label>Total Fee Amount</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <input type="text" class="form-control" readonly value="{{ number_format($fee->amount, 2) }}">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Previously Paid</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <input type="text" class="form-control" readonly value="{{ number_format($fee->paid_amount, 2) }}">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="paid_amount{{ $fee->id }}">Payment Amount</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <input type="number" step="0.01" name="paid_amount" id="paid_amount{{ $fee->id }}" 
                                                            class="form-control" required 
                                                            max="{{ $fee->amount - $fee->paid_amount }}"
                                                            oninput="calculatePending({{ $fee->id }})">
                                                    </div>
                                                    <small class="text-muted">Maximum payable amount: ${{ number_format($fee->amount - $fee->paid_amount, 2) }}</small>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pending Amount</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <input type="text" id="pending_amount{{ $fee->id }}" class="form-control" readonly>
                                                    </div>
                                                    <small id="pending_text{{ $fee->id }}" class="text-danger"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="payment_method{{ $fee->id }}">Payment Method</label>
                                                    <select name="payment_method" id="payment_method{{ $fee->id }}" class="form-control" required>
                                                        <option value="">Select Payment Method</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="bank_transfer">Bank Transfer</option>
                                                        <option value="check">Check</option>
                                                        <option value="online">Online Payment</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="payment_status{{ $fee->id }}">Payment Status</label>
                                                    <select name="payment_status" id="payment_status{{ $fee->id }}" class="form-control" required>
                                                        <option value="paid">Paid</option>
                                                        <option value="unpaid">Unpaid</option>
                                                        <option value="partial">Partial</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="transaction_id{{ $fee->id }}">Transaction ID</label>
                                                    <input type="text" name="transaction_id" id="transaction_id{{ $fee->id }}" class="form-control" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="remarks{{ $fee->id }}">Remarks</label>
                                                    <textarea name="remarks" id="remarks{{ $fee->id }}" class="form-control" rows="3"></textarea>
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
        $(document).ready(function() {
            // Function to generate transaction ID
            function generateTransactionId(feeId) {
                const date = new Date();
                const year = date.getFullYear().toString().substr(-2);
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');
                const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
                return `TRX${year}${month}${day}-${feeId}-${random}`;
            }

            // Set transaction ID when payment modal is opened
            $('.modal').on('show.bs.modal', function (e) {
                const modalId = $(this).attr('id');
                const feeId = modalId.replace('paymentModal', '');
                const transactionId = generateTransactionId(feeId);
                $(`#transaction_id${feeId}`).val(transactionId);
                calculatePending(feeId);
            });
        });

        function calculatePending(feeId) {
            const totalAmount = parseFloat($(`#paymentModal${feeId} input[readonly]`).first().val().replace(/,/g, '')) || 0;
            const previouslyPaid = parseFloat($(`#paymentModal${feeId} input[readonly]`).eq(1).val().replace(/,/g, '')) || 0;
            const currentPayment = parseFloat($(`#paid_amount${feeId}`).val()) || 0;
            const remainingAmount = totalAmount - previouslyPaid - currentPayment;
            
            // Update pending amount
            $(`#pending_amount${feeId}`).val(remainingAmount.toFixed(2));
            
            // Show pending amount message
            const pendingText = $(`#pending_text${feeId}`);
            if (remainingAmount > 0) {
                pendingText.text(`Remaining payment needed: $${remainingAmount.toFixed(2)}`);
                pendingText.show();
            } else {
                pendingText.hide();
            }
            
            // Update payment status automatically
            const status = currentPayment === 0 ? 'unpaid' : 
                          remainingAmount <= 0 ? 'paid' : 'partial';
            $(`#payment_status${feeId}`).val(status);

            // Validate payment amount
            const maxPayable = totalAmount - previouslyPaid;
            if (currentPayment > maxPayable) {
                $(`#paid_amount${feeId}`).val(maxPayable);
                calculatePending(feeId);
            }
        }
    </script>
@stop
