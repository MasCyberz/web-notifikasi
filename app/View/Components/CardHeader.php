<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardHeader extends Component
{
    /**
     * Create a new component instance.
     */
    public $titleHeader;
    public function __construct($titleHeader)
    {
        $this->titleHeader = $titleHeader;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card-header');
    }
}
