<?php

namespace App\View\Components\cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class transactionCard extends Component
{
    public function __construct(
        public bool $isIncome = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.cards.transaction-card');
    }
}
