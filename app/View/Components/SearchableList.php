<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchableList extends Component
{
    public $items = [];
    public $totalPrice = 0;
    public $entityType; // Тип сущности (например, 'product' или 'service')
    public $searchUrl;  // URL для поиска
    public $displayFields; // Поля для отображения (например, ['name', 'sku', 'price'])

    public function __construct($entityType, $searchUrl, $displayFields = [])
    {
        $this->entityType = $entityType;
        $this->searchUrl = $searchUrl;
        $this->displayFields = $displayFields;
    }

    public function render()
    {
        return view('components.searchable-list');
    }

    public function addItem($item)
    {
        $this->items[] = [
            'id' => $item['id'],
            'name' => $item['name'] ?? '',
            'sku' => $item['sku'] ?? '',
            'price' => $item['price'] ?? 0,
            'quantity' => 1,
        ];

        $this->updateTotalPrice();
    }

    public function updateQuantity($index, $quantity)
    {
        if (isset($this->items[$index])) {
            $this->items[$index]['quantity'] = max(1, intval($quantity));
            $this->updateTotalPrice();
        }
    }

    public function removeItem($index)
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->updateTotalPrice();
        }
    }

    private function updateTotalPrice()
    {
        $this->totalPrice = array_reduce($this->items, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);
    }
}
