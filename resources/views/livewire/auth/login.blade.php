<div class="flex flex-col gap-7" data-anim="panel">

    {{-- Brand lockup --}}
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/queue_logo.png') }}" alt="" aria-hidden="true" class="h-11 w-11 shrink-0">
        <div class="leading-tight">
            <p class="q-board-face text-2xl font-bold uppercase tracking-[0.08em] text-white">QUEWIE</p>
            <p class="q-data text-[10px] uppercase tracking-[0.22em] text-white/40">Queue management</p>
        </div>
    </div>

    <div>
        <p class="q-data mb-2 text-[10px] uppercase tracking-[0.3em] text-[color:var(--q-led)]">Staff terminal</p>
        <h1 class="q-board-face text-3xl font-semibold leading-tight text-white">Sign in to your counter</h1>
        <p class="mt-2 text-sm text-white/50">Use the account your branch admin issued you.</p>
    </div>

    <x-auth-session-status class="text-sm text-green-400" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-5">

        {{-- Email --}}
        <div>
            <label for="email" class="q-data mb-2 block text-[10px] uppercase tracking-[0.2em] text-white/50">
                {{ __('Email address') }}
            </label>
            <input
                wire:model="email"
                type="email"
                id="email"
                name="email"
                required
                autofocus
                autocomplete="email"
                placeholder="you@kiosqueeing.local"
                class="q-field @error('email') q-field--error @enderror"
            />
            @error('email')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <div class="mb-2 flex items-baseline justify-between gap-3">
                <label for="password" class="q-data text-[10px] uppercase tracking-[0.2em] text-white/50">
                    {{ __('Password') }}
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="q-link q-data text-[10px] uppercase tracking-[0.14em]">
                        {{ __('Forgot?') }}
                    </a>
                @endif
            </div>

            <div class="relative">
                <input
                    wire:model="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="q-field pr-11 @error('password') q-field--error @enderror"
                    :type="show ? 'text' : 'password'"
                    type="password"
                />
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-white/40 transition-colors hover:text-white"
                    :aria-label="show ? 'Hide password' : 'Show password'"
                >
                    <svg class="h-5 w-5" x-show="!show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                    <svg class="h-5 w-5" x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                    </svg>
                </button>
            </div>

            @error('password')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember --}}
        <label for="remember" class="flex cursor-pointer items-center gap-3 text-sm text-white/60">
            <input
                wire:model="remember"
                id="remember"
                name="remember"
                type="checkbox"
                class="h-4 w-4 rounded border-white/25 bg-white/5 text-[color:var(--q-led)] focus:ring-2 focus:ring-[color:var(--q-led)] focus:ring-offset-0"
            />
            {{ __('Keep me signed in') }}
        </label>

        <button
            type="submit"
            class="q-submit q-data mt-1 flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3.5 text-sm font-medium uppercase tracking-[0.16em] text-white"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove wire:target="login">{{ __('Sign in') }}</span>
            <span wire:loading wire:target="login">{{ __('Signing in…') }}</span>
            <svg class="h-4 w-4" wire:loading.remove wire:target="login" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </form>

    @if (Route::has('register'))
        <p class="border-t border-white/10 pt-5 text-center text-sm text-white/40">
            {{ __('No account yet?') }}
            <a href="{{ route('register') }}" class="q-link font-medium">{{ __('Create one') }}</a>
        </p>
    @endif
</div>
