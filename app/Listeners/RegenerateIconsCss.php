<?php

namespace App\Listeners;

use App\Services\IconService;
use Illuminate\Support\Facades\File;

class RegenerateIconsCss
{
    protected $iconService;

    public function __construct(IconService $iconService)
    {
        $this->iconService = $iconService;
    }

    /**
     * Handle the event.
     */
    public function handle(): void
    {
        $css = $this->iconService->generateCss();
        $filePath = public_path('icons/css/icons.css');

        File::put($filePath, $css);
    }
}
