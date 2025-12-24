<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class StatisticsExportService
{
    /**
     * Экспорт данных в PDF
     */
    public function exportToPdf(array $data, string $title, array $headers = [], array $filters = []): \Illuminate\Http\Response
    {
        $html = view('exports.pdf', [
            'title' => $title,
            'headers' => $headers,
            'data' => $data,
            'exportDate' => Carbon::now()->format('d.m.Y H:i:s')
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');

        $filename = $this->sanitizeFilename($title) . '_' . Carbon::now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Экспорт данных в Excel
     */
    public function exportToExcel(array $data, string $title, array $headers = [], array $filters = []): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $exportData = [];

        // Добавляем заголовки
        if (!empty($headers)) {
            $exportData[] = $headers;
        }

        // Добавляем данные
        foreach ($data as $row) {
            if (is_array($row)) {
                $exportData[] = array_values($row);
            } else {
                $exportData[] = [$row];
            }
        }

        $filename = $this->sanitizeFilename($title) . '_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';

        $export = new class($exportData) implements \Maatwebsite\Excel\Concerns\FromArray {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }
        };

        return Excel::download($export, $filename);
    }

    /**
     * Экспорт данных в Word
     */
    public function exportToWord(array $data, string $title, array $headers = [], array $filters = []): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Заголовок
        $section->addText($title, ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(2);

        // Таблица с данными
        if (!empty($data)) {
            $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];
            $firstRowStyle = ['bgColor' => 'CCCCCC', 'bold' => true];

            $table = $section->addTable($tableStyle);

            // Заголовки
            if (!empty($headers)) {
                $table->addRow();
                foreach ($headers as $header) {
                    $table->addCell(2000, $firstRowStyle)->addText($header);
                }
            }

            // Данные
            foreach ($data as $row) {
                $table->addRow();
                $values = is_array($row) ? array_values($row) : [$row];
                
                foreach ($values as $value) {
                    $cellValue = is_object($value) ? (string)$value : $value;
                    $table->addCell(2000)->addText($cellValue ?? '');
                }
            }
        }

        $filename = $this->sanitizeFilename($title) . '_' . Carbon::now()->format('Y-m-d_His') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'export') . '.docx';

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return Response::download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Преобразование данных графика в таблицу
     */
    public function chartToTable(array $chartData, array $labels, string $valueColumn = 'Значение'): array
    {
        $table = [];
        $table[] = ['Название', $valueColumn];

        foreach ($labels as $index => $label) {
            $value = isset($chartData[$index]) ? $chartData[$index] : 0;
            $table[] = [$label, $value];
        }

        return $table;
    }

    /**
     * Очистка имени файла от недопустимых символов
     */
    private function sanitizeFilename(string $filename): string
    {
        $filename = mb_convert_encoding($filename, 'UTF-8', 'UTF-8');
        $filename = preg_replace('/[^\p{L}\p{N}\s\-_\.]/u', '', $filename);
        $filename = preg_replace('/\s+/', '_', $filename);
        return $filename;
    }
}

