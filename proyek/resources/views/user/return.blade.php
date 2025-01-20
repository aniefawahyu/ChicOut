@extends('layout.general')
@section('title')
    ChicOut - {{ Auth::user()->display_name }}
@endsection

@section('menu')

<div class="custom-card-history">
    <h3 class="custom-card-title">Return Item</h3>
    <div class="card">
        <div class="card-header">
            <h5>Item Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Item Name:</strong> {{ $dtrans->Item->name }}</p>
            <p><strong>Quantity:</strong> {{ $dtrans->qty }}</p>
            <p><strong>Price:</strong> Rp {{ number_format($dtrans->price, 0, ',', '.') }}</p>
            <p><strong>Subtotal:</strong> Rp {{ number_format($dtrans->subtotal, 0, ',', '.') }}</p>
        </div>
    </div>

    <form action="{{ route('process-return', ['id' => $dtrans->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="reason">Reason for Return</label>
            <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="qty_return">Quantity to Return</label>
            <input type="number" name="qty_return" id="qty_return" class="form-control" max="{{ $dtrans->qty }}" min="1" required>
        </div>
        <button type="submit" class="btn btn-danger">Submit Return</button>
    </form>
</div>
@endsection