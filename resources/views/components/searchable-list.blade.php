<div class="card">
    <div class="card-header">
        Поиск {{ $entityTypeLabel }}
    </div>
    <div class="card-body">
        <!-- Поле поиска -->
        <div class="mb-3">
            <label for="search-{{ $entityType }}" class="form-label">Поиск {{ ucfirst(trans('labels.' . $entityType)) }}</label>
            <input type="text" id="search-{{ $entityType }}" class="form-control" placeholder="Введите название..." autocomplete="off">
            <div id="search-results-{{ $entityType }}" class="dropdown mt-2" style="display: none;">
                <ul class="list-group"></ul>
            </div>
        </div>

        <!-- Список выбранных элементов -->
        <div id="selected-items-{{ $entityType }}" class="mt-3" data-items='[]'>
            <p>Нет выбранных элементов.</p>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const entityType = '{{ $entityType }}';
            const searchInput = document.getElementById(`search-${entityType}`);
            const searchResults = document.getElementById(`search-results-${entityType}`);
            const selectedItemsContainer = document.getElementById(`selected-items-${entityType}`);

            if (!searchInput || !searchResults || !selectedItemsContainer) {
                console.error('Элементы не найдены:', { searchInput, searchResults, selectedItemsContainer });
                return;
            }

            // Retrieve initial items from the data attribute
            const initialItemsElement = document.getElementById(`initial-items-${entityType}`);
            let initialItems = [];
            if (initialItemsElement) {
                initialItems = JSON.parse(initialItemsElement.getAttribute('data-items'));
            }
            console.log('Initial Items:', initialItems); // Debugging statement

            // Create the component object
            const component = {
                entityType,
                selectedItemsContainer,
                addItem(item) {
                    let items = JSON.parse(this.selectedItemsContainer.getAttribute('data-items')) || [];
                    items.push({ ...item, quantity: 1 });
                    this.selectedItemsContainer.setAttribute('data-items', JSON.stringify(items));
                    this.renderItems();
                    this.updateTotalPrice();
                },
                renderItems() {
                    const items = JSON.parse(this.selectedItemsContainer.getAttribute('data-items')) || [];
                    let html = '';

                    if (items.length > 0) {
                        html = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Артикул</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Удалить</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                        items.forEach((item) => {
                            html += `
                        <tr>
                            <td>${item.name}</td>
                            <td>${item.sku ?? item.code}</td>
                            <td>${item.price} ₽</td>
                            <td>
                                <input type="number" class="form-control quantity-input"
                                       data-entity-type="${this.entityType}"
                                       data-id="${item.id}"
                                       value="${item.quantity}" min="1">
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-item"
                                        data-entity-type="${this.entityType}"
                                        data-id="${item.id}">
                                    Удалить
                                </button>
                            </td>
                        </tr>
                    `;
                        });

                        html += '</tbody></table>';
                    } else {
                        html = '<p>Нет выбранных {{ $entityTypeLabel }}.</p>';
                    }

                    html += `
                <div class="d-flex justify-content-end mt-3">
                    <strong id="total-price-${this.entityType}">Итого: ${this.calculateTotalPrice()} ₽</strong>
                </div>
            `;

                    this.selectedItemsContainer.innerHTML = html;
                },
                calculateTotalPrice() {
                    const items = JSON.parse(this.selectedItemsContainer.getAttribute('data-items')) || [];
                    return items.reduce((total, item) => total + (parseFloat(item.price) * parseInt(item.quantity, 10)), 0);
                },
                updateTotalPrice() {
                    const totalPrice = this.calculateTotalPrice();
                    const totalPriceElement = document.getElementById(`total-price-${this.entityType}`);
                    if (totalPriceElement) {
                        totalPriceElement.textContent = `Итого: ${totalPrice} ₽`;
                    } else {
                        console.error(`Элемент итоговой цены не найден: total-price-${this.entityType}`);
                    }
                },
                updateQuantity(id, quantity) {
                    let items = JSON.parse(this.selectedItemsContainer.getAttribute('data-items')) || [];
                    const item = items.find(i => i.id === id);
                    if (item) {
                        item.quantity = parseInt(quantity, 10) || 1;
                        this.selectedItemsContainer.setAttribute('data-items', JSON.stringify(items));
                        this.renderItems();
                        this.updateTotalPrice();
                    } else {
                        console.error('Элемент не найден:', id);
                    }
                },
                removeItem(id) {
                    let items = JSON.parse(this.selectedItemsContainer.getAttribute('data-items')) || [];
                    const index = items.findIndex(i => i.id === id);
                    if (index !== -1) {
                        items.splice(index, 1);
                        this.selectedItemsContainer.setAttribute('data-items', JSON.stringify(items));
                        this.renderItems();
                        this.updateTotalPrice();
                    } else {
                        console.error('Элемент не найден:', id);
                    }
                }
            };

            // Initialize the component with existing items
            if (initialItems.length > 0) {
                component.selectedItemsContainer.setAttribute('data-items', JSON.stringify(initialItems));
                component.renderItems();
                component.updateTotalPrice();
            }

            // Register the component
            if (!window.componentInstances) {
                window.componentInstances = {};
            }
            window.componentInstances[entityType] = component;

            // Search input event listener
            searchInput.addEventListener('input', function () {
                const query = this.value.trim();

                if (query.length < 2) {
                    searchResults.style.display = 'none';
                    return;
                }

                axios.get('{{ $searchUrl }}', { params: { query } })
                    .then(response => {
                        const results = response.data.slice(0, 7);
                        let html = '';

                        if (results.length > 0) {
                            results.forEach(item => {
                                html += `
                            <li class="list-group-item item" data-id="${item.id}" style="cursor: pointer;">
                                ${item.name} (${item.sku ?? item.code}) - ${item.price} ₽
                            </li>
                        `;
                            });
                        } else {
                            html = '<li class="list-group-item">{{ $entityTypeLabel }} не найдены</li>';
                        }

                        searchResults.querySelector('ul').innerHTML = html;
                        searchResults.style.display = 'block';

                        // Add click event listener to items
                        document.querySelectorAll('.item').forEach(item => {
                            item.addEventListener('click', function () {
                                const selectedItem = results.find(i => i.id == this.dataset.id);
                                if (selectedItem) {
                                    component.addItem(selectedItem);
                                    searchResults.style.display = 'none';
                                }
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Ошибка при поиске:', error);
                    });
            });

            // Event delegation for dynamic elements
            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('quantity-input')) {
                    const entityType = e.target.dataset.entityType;
                    const id = parseInt(e.target.dataset.id, 10); // Ensure id is parsed as an integer
                    const quantity = e.target.value;
                    const componentInstance = window.componentInstances?.[entityType];
                    if (componentInstance) {
                        componentInstance.updateQuantity(id, quantity);
                    }
                }
            });

            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-item')) {
                    const entityType = e.target.dataset.entityType;
                    const id = parseInt(e.target.dataset.id, 10); // Ensure id is parsed as an integer
                    console.log('Removing item with id:', id); // Debugging statement
                    const componentInstance = window.componentInstances?.[entityType];
                    if (componentInstance) {
                        componentInstance.removeItem(id);
                    }
                }
            });
        });

    </script>
@endpush
