<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Create a new component instance.
     */
    public $label;
    public $name;
    public $type;
    public $placeholder;
    public $value;
    public $required;
    public $hint;
    public $class;
    public $id;

    public function __construct($class,$label, $name, $type = 'text', $placeholder = '', $value = '', $required = false, $hint = null, $id = '')
    {
        $this->class = $class;
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->required = $required;
        $this->hint = $hint;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.formInput');
    }
}
