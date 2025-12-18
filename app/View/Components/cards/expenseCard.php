<?php

namespace App\View\Components\cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class expenseCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $componentWidth = '',
        public string $h1Class = '',
        public string $spanClass = '',
        public string $title = 'Bạn đã chi',
        public string $subtitle = 'Tháng này',
        public string $date = '',
        public int $total = 0,
    ) {
        
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.expense-card');
    }
}
