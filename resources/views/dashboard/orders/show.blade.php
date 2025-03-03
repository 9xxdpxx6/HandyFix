@extends('layouts.app')

@section('title', 'View Order #' . $order->id)

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Order Details</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- General Information -->
                <div class="col-md-6">
                    <h5>General Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td>{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>${{ number_format($order->total, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $order->status->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Completed</th>
                            <td>
                                @if($order->completed_at)
                                    {{ \Carbon\Carbon::parse($order->completed_at)->format('d.m.Y H:i') }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <!-- Customer Information -->
                    <div>
                        <h5>Customer Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td>{{ $order->customer->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Loyalty Points</th>
                                <td>{{ $order->customer->loyalty_points ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Loyalty Level</th>
                                <td>{{ $order->customer->loyalty_level_id ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- Vehicle Information -->
                    <div>
                        <h5>Vehicle Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Vehicle</th>
                                    <td>
                                        <span>{{ $order->vehicle->model->brand->name ?? 'N/A' }}</span>
                                        <span>{{ $order->vehicle->model->name ?? 'N/A' }}</span>
                                        <span>{{ $order->vehicle->year ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>VIN Code</th>
                                    <td>{{ $order->vehicle->vin ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>License Plate</th>
                                    <td>{{ $order->vehicle->license_plate ?? 'N/A' }}</td>
                                </tr>
                            </table>
                            <a href="{{ route('dashboard.vehicles.show', $order->vehicle->id) }}" class="btn btn-primary">View Vehicle Details</a>
                    </div>
                </div>
            </div>

            <!-- Purchases Section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Purchases</h5>
                    @if($order->purchases->isEmpty())
                        <p>No purchases found for this order.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($order->purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->product_name }}</td>
                                    <td>${{ number_format($purchase->price, 2) }}</td>
                                    <td>{{ $purchase->quantity }}</td>
                                    <td>${{ number_format($purchase->price * $purchase->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Service Entries Section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Services</h5>
                    @if($order->serviceEntries->isEmpty())
                        <p>No services found for this order.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($order->serviceEntries as $service)
                                <tr>
                                    <td>{{ $service->service_name }}</td>
                                    <td>${{ number_format($service->price, 2) }}</td>
                                    <td>{{ $service->quantity }}</td>
                                    <td>${{ number_format($service->price * $service->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Comment and Note -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Additional Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Comment</th>
                            <td>{{ $order->comment ?? 'No comment provided.' }}</td>
                        </tr>
                        <tr>
                            <th>Note</th>
                            <td>{{ $order->note ?? 'No note provided.' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
        </div>
    </div>
@endsection
