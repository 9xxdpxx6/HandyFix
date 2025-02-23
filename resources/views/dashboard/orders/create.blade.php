@extends('layouts.app')

@section('title', 'Create New Order')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New Order</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.orders.store') }}" method="POST" class="row g-3">
                @csrf

                <!-- Customer Selection -->
                <div class="col-md-6">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                        <option value="" selected disabled>Select a customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->user->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Manager Selection -->
                <div class="col-md-6">
                    <label for="manager_id" class="form-label">Manager</label>
                    <select name="manager_id" id="manager_id" class="form-select @error('manager_id') is-invalid @enderror">
                        <option value="" selected disabled>Select a manager</option>
                        @foreach ($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->user->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Vehicle Selection -->
                <div class="col-md-6">
                    <label for="vehicle_id" class="form-label">Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror">
                        <option value="" selected disabled>Select a vehicle</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total Amount -->
                <div class="col-md-6">
                    <label for="total" class="form-label">Total Amount</label>
                    <input type="number" step="0.01" name="total" id="total" class="form-control @error('total') is-invalid @enderror" value="{{ old('total') }}">
                    @error('total')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Selection -->
                <div class="col-md-6">
                    <label for="status_id" class="form-label">Status</label>
                    <select name="status_id" id="status_id" class="form-select @error('status_id') is-invalid @enderror">
                        <option value="" selected disabled>Select a status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Note -->
                <div class="col-md-12">
                    <label for="note" class="form-label">Note</label>
                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="3">{{ old('note') }}</textarea>
                    @error('note')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Create Order</button>
                </div>
            </form>
        </div>
    </div>
@endsection
