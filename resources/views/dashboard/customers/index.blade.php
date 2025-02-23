@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Orders List</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->customer->name }}</td>
                        <td>{{ $order->total }}</td>
                        <td>
                            <a href="{{ route('dashboard.orders.edit', $order) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('dashboard.orders.destroy', $order) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
