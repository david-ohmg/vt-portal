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
            'password' => ['nullable', 'string', 'min:8'],
            'password_confirm' => ['nullable', 'string', 'same:password']
        ]);

        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password) && $this->password === $this->password_confirm) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->password = null;
        $this->password_confirm = null;

        session()->flash('success', 'Profile updated successfully!');
    }

    public function render()
    {
        return view('pages.portal.⚡profile');
    }
};
?>

<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-center mb-2">My Profile</h1>
        <p class="text-center text-gray-600 dark:text-gray-400">Update your account information</p>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Profile Information Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Profile Information
                </h2>
            </div>

            <div class="p-6 space-y-4">
                <!-- Name Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="name">
                        Full Name
                    </label>
                    <input
                        class="w-full border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-gray-900 dark:text-gray-100 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        type="text"
                        id="name"
                        wire:model="name"
                        placeholder="Enter your full name">
                    @error('name')
                    <span class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="email">
                        Email Address
                    </label>
                    <input
                        class="w-full border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-gray-900 dark:text-gray-100 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        type="email"
                        id="email"
                        wire:model="email"
                        placeholder="your.email@example.com">
                    @error('email')
                    <span class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Password Change Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Change Password
                </h2>
                <p class="text-purple-100 text-sm mt-1">Leave blank to keep your current password</p>
            </div>

            <div class="p-6 space-y-4">
                <!-- Password Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="password">
                        New Password
                    </label>
                    <input
                        class="w-full border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-gray-900 dark:text-gray-100 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        type="password"
                        id="password"
                        wire:model="password"
                        placeholder="Enter new password (min. 8 characters)">
                    @error('password')
                    <span class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="password_confirm">
                        Confirm New Password
                    </label>
                    <input
                        class="w-full border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-gray-900 dark:text-gray-100 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        type="password"
                        id="password_confirm"
                        wire:model="password_confirm"
                        placeholder="Confirm new password">
                    @error('password_confirm')
                    <span class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('portal.batches') }}"
               class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg transition">
                Cancel
            </a>
            <button
                type="submit"
                class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Save Changes
            </button>
        </div>
    </form>

    <!-- Loading Overlay -->
    <div wire:loading wire:target="save" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-xl">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
            <p class="mt-4 text-center font-semibold">Updating profile...</p>
        </div>
    </div>
</div>
