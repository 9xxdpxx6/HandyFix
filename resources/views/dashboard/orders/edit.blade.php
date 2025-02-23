@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            Edit Order
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="customer_id">Customer ID</label>
                    <input type="text" name="customer_id" class="form-control" value="{{ $order->customer_id }}" required>
                </div>
                <div class="form-group">
                    <label for="manager_id">Manager ID</label>
                    <input type="text" name="manager_id" class="form-control" value="{{ $order->manager_id }}" required>
                </div>
                <div class="form-group">
                    <label for="total">Total</label>
                    <input type="text" name="total" class="form-control" value="{{ $order->total }}" required>
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea name="comment" class="form-control">{{ $order->comment }}</textarea>
                </div>
                <div class="form-group">
                    <label for="note">Note</label>
                    <textarea name="note" class="form-control">{{ $order->note }}</textarea>
                </div>
                <div class="form-group">
                    <label for="status_id">Status</label>
                    <input type="text" name="status_id" class="form-control" value="{{ $order->status_id }}" required>
                </div>
                <div class="form-group">
                    <label for="completed_at">Completed At</label>
                    <input type="datetime-local" name="completed_at" class="form-control" value="{{ $order->completed_at }}">
                </div>
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
@endsection
