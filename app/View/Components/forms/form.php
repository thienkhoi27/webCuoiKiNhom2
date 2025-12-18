<?php

namespace App\View\Components\forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class form extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type = '',
        public string $value = '',
        public string $size = 'md:text-md lg:text-lg',
        public string $bg = 'bg-[#EEEEEE]',
        public string $padding = 'px-4 py-2',
        public string $required = 'required'
    ) {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.form');
    }
}
