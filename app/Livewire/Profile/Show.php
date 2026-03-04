<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Show extends Component
{
    public $name;
    public $email;
    public $phone;
    
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    public function updateProfile()
    {
        $user = Auth::user();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        // Only admins can update email
        if ($user->isAdmin() || $user->hasRole('ict-team')) {
            $rules['email'] = ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)];
        }

        $this->validate($rules);

        $user->name = $this->name;
        $user->phone = $this->phone;
        
        if ($user->isAdmin() || $user->hasRole('ict-team')) {
            $user->email = $this->email;
        }

        $user->save();

        session()->flash('profile-success', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($this->password);
        $user->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);
        
        session()->flash('password-success', 'Password changed successfully.');
    }

    public function render()
    {
        return view('livewire.profile.show');
    }
}
