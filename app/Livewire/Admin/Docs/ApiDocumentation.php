<?php

namespace App\Livewire\Admin\Docs;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ApiDocumentation extends Component
{
    public function render()
    {
        $path = base_path('API_DOCUMENTATION.md');
        $content = File::get($path);
        
        return view('livewire.admin.docs.api-documentation', [
            'content' => Str::markdown($content)
        ])->layout('layouts.app');
    }
}
