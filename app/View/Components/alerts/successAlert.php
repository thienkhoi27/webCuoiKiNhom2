<?php

namespace App\View\Components\alerts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class successAlert extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $status = 'Success',
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alerts.success-alert');
    }
}
