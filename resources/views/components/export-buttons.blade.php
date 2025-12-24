@props(['page', 'report', 'filters' => []])

<div class="btn-group">
    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Экспорт
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        @foreach(['pdf', 'excel', 'word'] as $format)
            <a href="#" class="dropdown-item export-statistics-btn" 
               data-url="{{ route('dashboard.statistics.export', ['page' => $page, 'report' => $report]) }}"
               data-format="{{ $format }}"
               data-start-date="{{ $filters['start_date'] ?? '' }}"
               data-end-date="{{ $filters['end_date'] ?? '' }}">
                {{ strtoupper($format) }}
            </a>
        @endforeach
    </div>
</div>

