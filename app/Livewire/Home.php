<?php

namespace App\Livewire;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.home');
    }

    public function goToChatDisplay()
    {
        return redirect()->to('/admin/chat-display');
    }
}
