@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список заказов</h3>
            <a href="{{ route('dashboard.orders.create') }}" class="btn btn-primary btn-sm">Создать заказ</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Клиент</th>
                    <th class="text-center">Статус</th>
                    <th class="text-center">Добавлен</th>
                    <th class="text-center">Завершён</th>
                    <th class="text-end">Итого</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            {{ $order->customer->user->name ?? 'N/A' }}
                        </td>
                        <td class="text-center">
                            <span class="badge" style="background-color: {{ $order->status->color ?? '#ccc' }};">
                                {{ $order->status->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="text-center">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td class="text-center">
                            @if($order->completed_at)
                                {{ \Carbon\Carbon::parse($order->completed_at)->format('d.m.Y H:i') }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="text-end">{{ $order->total }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.orders.show', $order) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20"/>
                            </a>
                            <a href="{{ route('dashboard.orders.edit', $order) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20"/>
                            </a>
                            <form action="{{ route('dashboard.orders.destroy', $order) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                    <x-icon icon="trash-can" class="icon-20"/>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ColorModes.init({ el: document.documentElement });

            document.documentElement.setAttribute('data-color-mode', 'dark');
        });
    </script>
@endpush
