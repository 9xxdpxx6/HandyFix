<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\Status;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->customer->orders()
            ->with(['vehicle.model.brand', 'status', 'serviceEntries.service'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('client.orders.index', compact('orders'));
    }
    
    public function create()
    {
        $vehicles = Auth::user()->customer->vehicles()->with('model.brand')->get();
        
        // Если у пользователя нет автомобилей, перенаправляем его на страницу добавления
        if ($vehicles->isEmpty()) {
            return redirect()->route('vehicles.create')
                ->with('warning', 'Для создания заказа необходимо добавить автомобиль');
        }
        
        $services = Service::with('serviceType')->get();
        
        return view('client.orders.create', compact('vehicles', 'services'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'description' => 'nullable|string',
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
        ]);
        
        // Проверяем, принадлежит ли автомобиль текущему пользователю
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($vehicle->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        // Находим статус "Новый" для заказа
        $newStatus = Status::where('name', 'Новый')->first();
        
        // Создаем заказ
        $order = Order::create([
            'customer_id' => Auth::user()->customer->id,
            'vehicle_id' => $validated['vehicle_id'],
            'status_id' => $newStatus ? $newStatus->id : null,
            'description' => $validated['description'] ?? null,
        ]);
        
        // Добавляем услуги к заказу
        foreach ($validated['service_ids'] as $serviceId) {
            $service = Service::findOrFail($serviceId);
            $order->serviceEntries()->create([
                'service_id' => $serviceId,
                'price' => $service->price,
                'quantity' => 1,
                'mechanic_id' => 1,
                'service_name' => $service->name,
            ]);
        }
        
        return redirect()->route('order.show', $order)
            ->with('success', 'Заказ успешно создан');
    }
    
    public function show(Order $order)
    {
        // Проверяем, принадлежит ли заказ текущему пользователю
        if ($order->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        $order->load(['vehicle.model.brand', 'status', 'serviceEntries.service']);
        
        return view('client.orders.show', compact('order'));
    }
    
    public function payment(Order $order)
    {
        // Проверяем, принадлежит ли заказ текущему пользователю
        if ($order->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        $order->load(['vehicle.model.brand', 'serviceEntries.service']);
        
        // Создаем уникальный идентификатор платежа (в реальной системе)
        $paymentId = Str::uuid()->toString();
        
        // Предполагаем, что у вас есть метод для генерации QR кода для СБП
        // В данном примере мы просто симулируем этот функционал
        $totalAmount = $order->serviceEntries->sum(function ($entry) {
            return $entry->price * $entry->quantity;
        });
        
        // Имитация формирования СБП QR кода
        $qrCodeUrl = "https://api.handyfix.ru/payments/qr/$paymentId?amount=$totalAmount";
        
        return view('client.orders.payment', compact('order', 'totalAmount', 'qrCodeUrl', 'paymentId'));
    }
    
    public function pay(Request $request, Order $order)
    {
        // Проверяем, принадлежит ли заказ текущему пользователю
        if ($order->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        // Здесь должна быть логика взаимодействия с платежным шлюзом СБП
        
        // В реальном приложении, здесь бы происходила интеграция с API СБП
        // и отправка запроса на создание платежа
        
        // Для демонстрации просто отметим заказ как оплаченный
        $paidStatus = Status::where('name', 'Оплачен')->first();
        if ($paidStatus) {
            $order->status_id = $paidStatus->id;
            $order->save();
        }
        
        return redirect()->route('order.show', $order)
            ->with('success', 'Заказ успешно оплачен');
    }
    
    public function paymentCallback(Request $request, Order $order)
    {
        // Этот метод будет вызываться платежным шлюзом после оплаты
        // В данном примере мы просто симулируем успешную оплату
        
        $paidStatus = Status::where('name', 'Оплачен')->first();
        if ($paidStatus) {
            $order->status_id = $paidStatus->id;
            $order->save();
        }
        
        return response()->json(['success' => true]);
    }
} 