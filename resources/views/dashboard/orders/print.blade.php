<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказ №{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            font-size: 12pt;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .page-container {
            max-width: 210mm;
            margin: 20px auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            position: relative;
            padding: 20px;
            border-radius: 2px;
        }
        h1, h2, h3, h4 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-top: 30px;
            text-align: center;
        }
        .no-print {
            text-align: right;
            margin-bottom: 20px;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .no-print button {
            padding: 6px 12px;
            margin-left: 5px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .no-print button:hover {
            background-color: #0056b3;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
                background-color: white;
            }
            .page-container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Печать</button>
        <button onclick="window.close()">Закрыть</button>
    </div>

    <div class="page-container">
        <div class="header">
            <div class="company-info">
                <h2>Автосервис "ХэндиФикс"</h2>
                <p>Адрес: ул. Примерная, 123, г. Городской</p>
                <p>Телефон: +7 (123) 456-78-90</p>
                <p>Email: info@handyfix.ru</p>
            </div>
            <h1>Заказ №{{ $order->id }}</h1>
            <p>Дата создания: {{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</p>
            @if($order->status->is_closing && $order->completed_at)
                <p>Дата выполнения: {{ \Carbon\Carbon::parse($order->completed_at)->format('d.m.Y H:i') }}</p>
            @endif
            <p>Статус: {{ $order->status->name ?? 'Н/Д' }}</p>
        </div>

        <div class="section">
            <h3>Информация о клиенте</h3>
            <table>
                <tr>
                    <th>Имя клиента</th>
                    <td>{{ $order->customer->user->name ?? 'Н/Д' }}</td>
                </tr>
                <tr>
                    <th>Бонусная программа</th>
                    <td>{{ $order->customer->loyaltyLevel->name ?? 'Н/Д' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Информация об автомобиле</h3>
            <table>
                <tr>
                    <th>Автомобиль</th>
                    <td>
                        {{ $order->vehicle->model->brand->name ?? 'Н/Д' }}
                        {{ $order->vehicle->model->name ?? 'Н/Д' }}
                        {{ $order->vehicle->year ?? 'Н/Д' }}
                    </td>
                </tr>
                <tr>
                    <th>VIN-код</th>
                    <td>{{ $order->vehicle->vin ?? 'Н/Д' }}</td>
                </tr>
                <tr>
                    <th>Номерной знак</th>
                    <td>{{ $order->vehicle->license_plate ?? 'Н/Д' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Выполненные работы</h3>
            @if($order->serviceEntries->isEmpty())
                <p>Работы не выполнялись</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Наименование работы</th>
                            <th>Кол-во</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalServices = 0; @endphp
                        @foreach($order->serviceEntries as $index => $service)
                            @php 
                                $serviceTotal = $service->price * $service->quantity;
                                $totalServices += $serviceTotal;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $service->service_name }}</td>
                                <td>{{ $service->quantity }}</td>
                                <td>{{ number_format($service->price, 2, ',', ' ') }} ₽</td>
                                <td>{{ number_format($serviceTotal, 2, ',', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="4" style="text-align: right;">Итого за работы:</td>
                            <td>{{ number_format($totalServices, 2, ',', ' ') }} ₽</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>

        <div class="section">
            <h3>Использованные запчасти и материалы</h3>
            @if($order->purchases->isEmpty())
                <p>Запчасти и материалы не использовались</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Наименование</th>
                            <th>Кол-во</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalParts = 0; @endphp
                        @foreach($order->purchases as $index => $purchase)
                            @php 
                                $purchaseTotal = $purchase->price * $purchase->quantity;
                                $totalParts += $purchaseTotal;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $purchase->product_name }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>{{ number_format($purchase->price, 2, ',', ' ') }} ₽</td>
                                <td>{{ number_format($purchaseTotal, 2, ',', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="4" style="text-align: right;">Итого за запчасти:</td>
                            <td>{{ number_format($totalParts, 2, ',', ' ') }} ₽</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>

        <div class="section">
            <h3>Итоговая стоимость</h3>
            <table>
                <tr>
                    <th>Работы</th>
                    <td>{{ number_format($order->serviceEntries->sum(function($item) { return $item->price * $item->quantity; }), 2, ',', ' ') }} ₽</td>
                </tr>
                <tr>
                    <th>Запчасти и материалы</th>
                    <td>{{ number_format($order->purchases->sum(function($item) { return $item->price * $item->quantity; }), 2, ',', ' ') }} ₽</td>
                </tr>
                <tr class="total-row">
                    <th>ИТОГО К ОПЛАТЕ</th>
                    <td>{{ number_format(
                        $order->serviceEntries->sum(function($item) { return $item->price * $item->quantity; }) +
                        $order->purchases->sum(function($item) { return $item->price * $item->quantity; }), 
                        2, ',', ' ') }} ₽
                        @if($order->completed_at)
                            <span style="color: green; margin-left: 15px; font-weight: bold;">ОПЛАЧЕНО</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        @if($order->comment || $order->note)
            <div class="section">
                <h3>Дополнительная информация</h3>
                @if($order->comment)
                    <p><strong>Комментарий клиента:</strong> {{ $order->comment }}</p>
                @endif
                @if($order->note)
                    <p><strong>Примечание мастера:</strong> {{ $order->note }}</p>
                @endif
            </div>
        @endif

        <div class="signature-section">
            <div>
                <p>Мастер: ________________________</p>
                <div class="signature-line">подпись</div>
            </div>
            <div>
                <p>Клиент: ________________________</p>
                <div class="signature-line">подпись</div>
            </div>
        </div>

        <div style="margin-top: 30px; font-size: 10pt; text-align: center;">
            <p>Автосервис "ХэндиФикс" — Ваш автомобиль в надежных руках!</p>
        </div>
    </div>
</body>
</html> 