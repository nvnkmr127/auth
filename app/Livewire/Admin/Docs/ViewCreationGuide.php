<?php

namespace App\Livewire\Admin\Docs;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ViewCreationGuide extends Component
{
    public function render()
    {
        $path = base_path('VIEW_CREATION_GUIDE.md');
        $content = File::get($path);
        
        return view('livewire.admin.docs.view-creation-guide', [
            'content' => Str::markdown($content)
        ])->layout('layouts.app');
    }
}
