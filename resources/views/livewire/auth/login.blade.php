<div class="flex flex-col gap-6">
    <!-- System Logo/Name -->
    <div class="text-center mb-6">
        <div class="flex flex-col items-center justify-center">
            <img src="{{ asset('images/queue_logo.png') }}" alt="KiosQueuing Logo" class="h-20 mb-2">
            <span class="text-2xl font-bold text-kiosqueeing-primary">KiosQueuing</span>
            <p class="text-sm text-gray-500">Queue Management System</p>
        </div>
    </div>


    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-900">{{ __('Email address') }}</label>
            <div class="mt-2">
                <input
                    wire:model="email"
                    type="email"
                    id="email"
                    name="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-kiosqueeing-primary sm:text-sm/6"
                />
            </div>
        </div>

        <!-- Password -->
        <div>
            <div class="relative">
                <label for="password" class="block text-sm font-medium text-gray-900">{{ __('Password') }}</label>
                <div class="mt-2 relative">
                    <input
                        wire:model="password"
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="{{ __('Password') }}"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-kiosqueeing-primary sm:text-sm/6"
                        x-data="{ show: false }"
                        :type="show ? 'text' : 'password'"
                    />
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 flex items-center pr-3"
                        @click="show = !show"
                        x-cloak
                    >
                        <svg
                            class="h-5 w-5 text-gray-400"
                            x-show="!show"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        <svg
                            class="h-5 w-5 text-gray-400"
                            x-show="show"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                            <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                        </svg>
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-kiosqueeing-primary hover:text-kiosqueeing-primary/80 absolute right-0 -top-5">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-3">
            <div class="flex h-6 shrink-0 items-center">
                <div class="group grid size-4 grid-cols-1">
                    <input
                        wire:model="remember"
                        id="remember"
                        name="remember"
                        type="checkbox"
                        class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-kiosqueeing-primary checked:bg-kiosqueeing-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-kiosqueeing-primary"
                    />
                    <svg class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white" viewBox="0 0 14 14" fill="none">
                        <path class="opacity-0 group-has-[:checked]:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
            <label for="remember" class="text-sm font-medium text-gray-900">{{ __('Remember me') }}</label>
        </div>

        <div class="flex items-center justify-end">
            <button
                type="submit"
                class="w-full rounded-md bg-kiosqueeing-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-kiosqueeing-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-kiosqueeing-primary"
            >
                {{ __('Log in') }}
            </button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Don\'t have an account?') }}
            <a href="{{ route('register') }}" wire:navigate class="text-kiosqueeing-primary hover:text-kiosqueeing-primary/80">
                {{ __('Sign up') }}
            </a>
        </div>
    @endif


</div>
