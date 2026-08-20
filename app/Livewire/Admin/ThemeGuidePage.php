<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ThemeGuidePage extends Component
{
    public function render()
    {
        return view('livewire.admin.theme-guide-page')
            ->layout('layouts.app');
    }
}
