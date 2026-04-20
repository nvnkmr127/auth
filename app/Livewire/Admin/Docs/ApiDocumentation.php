<?php

namespace App\Livewire\Admin\Docs;

use Livewire\Component;

class ApiDocumentation extends Component
{
    public $activeSection = 'introduction';

    public function setSection($section)
    {
        $this->activeSection = $section;
    }

    public function render()
    {
        return view('livewire.admin.docs.api-documentation')
            ->layout('layouts.guest');
    }
}
