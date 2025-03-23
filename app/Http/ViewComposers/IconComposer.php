<?php

namespace App\Http\ViewComposers;

use App\Services\IconService;
use Illuminate\View\View;

class IconComposer
{
    protected $iconService;

    public function __construct(IconService $iconService)
    {
        $this->iconService = $iconService;
    }

    public function compose(View $view)
    {
        $icons = $this->iconService->getAllIcons();
        $view->with('icons', $icons);
    }
}
