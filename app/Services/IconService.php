<?php

namespace App\Services;

use App\Events\IconsUpdated;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class IconService
{
    protected $filePath;

    public function __construct()
    {
        $this->filePath = storage_path('app/icons/icons.json');
    }

    /**
     * Получить все иконки.
     *
     * @return array
     */
    public function getAllIcons(): array
    {
        if (!File::exists($this->filePath)) {
            return [];
        }

        $content = File::get($this->filePath);
        return json_decode($content, true) ?? [];
    }

    /**
     * Получить иконку по имени.
     *
     * @param string $name
     * @return array|null
     */
    public function getIconByName(string $name): ?array
    {
        $icons = $this->getAllIcons();
        return $icons[$name] ?? null;
    }

    /**
     * Добавить или обновить иконку.
     *
     * @param string $name
     * @param string $svg
     * @param string $keywords
     * @return void
     */
    public function saveIcon(string $name, string $svg, string $keywords): void
    {
        $allIcons = $this->getAllIcons();
        foreach ($allIcons as $icon) {
            if ($icon['name'] === $name)
                return;
        }
        $icons = $this->getAllIcons();
        $icons[$name] = [
            'name' => $name,
            'keywords' => $keywords,
            'svg' => $svg,
        ];

        File::put($this->filePath, json_encode($icons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        event(new IconsUpdated());
    }

    /**
     * Обновить существующую иконку.
     *
     * @param string $name
     * @param string $newSvg
     * @param string $newKeywords
     * @return void
     */
    public function updateIcon(string $name, string $newSvg, string $newKeywords): void
    {
        $icons = $this->getAllIcons();

        if (isset($icons[$name])) {
            $icons[$name]['svg'] = $newSvg;
            $icons[$name]['keywords'] = $newKeywords;

            File::put($this->filePath, json_encode($icons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // Уведомляем систему об изменении
            event(new IconsUpdated());
        }
    }

    /**
     * Удалить иконку по имени.
     *
     * @param string $name
     * @return void
     */
    public function deleteIcon(string $name): void
    {
        $icons = $this->getAllIcons();
        unset($icons[$name]);

        File::put($this->filePath, json_encode($icons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        event(new IconsUpdated());
    }

    public function generateCss(): string
    {
        $icons = $this->getAllIcons();
        $css = ".hf-icon {\n";
        $css .= "    display: inline-block;\n";
        $css .= "    width: 1em;\n";
        $css .= "    height: 1em;\n";
        $css .= "    background-size: contain;\n";
        $css .= "    background-repeat: no-repeat;\n";
        $css .= "    background-position: center;\n";
        $css .= "}\n\n";

        foreach ($icons as $name => $icon) {
            $svg = str_replace('"', "'", $icon['svg']);

            $css .= ".hf-$name {\n";
            $css .= "    background-image: url(\"data:image/svg+xml,$svg\");\n";
            $css .= "}\n\n";
        }

        $minifiedCss = preg_replace('/\s+/', ' ', $css); // удаляем лишние пробелы и переходы
        $minifiedCss = str_replace(["\n", "\r", "\t"], '', $minifiedCss); // убираем переносы строк

        return $minifiedCss;
    }
}
