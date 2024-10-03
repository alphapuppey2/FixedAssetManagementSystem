<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchInput extends Component
{
    public $placeholder;

    public function __construct($placeholder = 'Search...')
    {
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.search-input');
    }
}