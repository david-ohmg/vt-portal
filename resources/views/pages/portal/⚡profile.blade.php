<?php

use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('My Profile')]
class extends Component {

    public string $name = '';
    public string $email = '';
    public ?string $password = null;
    public ?string $password_confirm = null;

    public function mount()
    {
        $user = auth()->user();
        abort_unless($user, 403);
        $this->name = (string)$user->name;
        $this->email = (string)$user->email;
    }

    public function save()
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string'],
            'password_confirm' => ['nullable', 'string']
        ]);

        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password) && $this->password === $this->password_confirm) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->password = null;
        $this->password_confirm = null;

        session()->flash('success', 'Profile updated');
    }

    public function render()
    {
        return view('pages.portal.⚡profile');
    }
};
?>

<div>
    <h1 class="text-2xl mt-4 mb-4 font-bold text-center">My Profile</h1>
    <form wire:submit.prevent="save">
        <div class="flex-col px-8 py-4 bg-zinc-100 mx-8 rounded-md border border-gray-200 dark:bg-zinc-800 dark:border-zinc-700">
            <label class="block text-xs" for="name">Name</label>
            <input class="border border-gray-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-700 rounded p-2 w-full" type="text" id="name" wire:model="name">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
            <label class="block text-xs" for="email">Email</label>
            <input class="border border-gray-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-700 rounded p-2 w-full" type="email" id="email" wire:model="email">
            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
            <label class="block text-xs" for="password">Password</label>
            <input class="border border-gray-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-700 rounded p-2 w-full" type="password" id="password"
                   wire:model="password">
            @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
            <label class="block text-xs" for="password_confirm">Confirm Password</label>
            <input class="border border-gray-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-700 rounded p-2 w-full" type="password" id="password_confirm"
                   wire:model="password_confirm">
            <button class="bg-blue-500 hover:bg-blue-700 text-white rounded p-2 mt-4">Update Profile</button>
        </div>
    </form>
</div>
