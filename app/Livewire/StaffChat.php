<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StaffMessage;
use Illuminate\Support\Facades\Auth;

class StaffChat extends Component
{
    public $message = '';
    
    protected $listeners = ['refreshStaffChat' => '$refresh'];

    public function sendMessage()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'agente', 'gestor'])) {
            return;
        }

        $this->validate([
            'message' => 'required|string|max:1000',
        ]);

        StaffMessage::create([
            'user_id' => $user->id,
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->dispatch('refreshStaffChat');
        $this->dispatch('scroll-bottom-staff');
    }

    public function render()
    {
        $messages = [];
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'agente', 'gestor'])) {
            $messages = StaffMessage::with('user')->orderBy('created_at', 'asc')->get();
        }

        return view('livewire.staff-chat', [
            'messages' => $messages,
        ]);
    }
}
