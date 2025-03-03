<?php

namespace App\View\Components;

use App\Services\IconService;
use Illuminate\View\Component;

class Icon extends Component
{
    public $icon;
    public $class;

    protected $iconService;

    /**
     * Create a new component instance.
     *
     * @param string $icon
     * @param string $class
     * @param IconService $iconService
     */
    public function __construct($icon, $class, IconService $iconService)
    {
        $this->icon = $icon;
        $this->class = $class;
        $this->iconService = $iconService;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        $iconData = $this->iconService->getIconByName($this->icon);

        return view('components.icon', [
            'iconData' => $iconData,
            'class' => $this->class,
        ]);
    }
}
