<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=barlow-condensed:500,600,700|dm-mono:400,500&display=swap" rel="stylesheet" />

    @vite(['resources/js/auth.js'])

    <style>
        :root {
            --q-void:    #04070f;
            --q-navy:    #001a71;
            --q-navy-lo: #000d3d;
            --q-ice:     #cee1ff;
            --q-led:     #1eafff;
            --q-blue:    #085fc5;
            --q-amber:   #f7b23b;
        }

        /* The decorative orbs are position:fixed with negative offsets. Body
           overflow does not clip fixed descendants, so the root has to. */
        html { overflow-x: hidden; }

        .q-auth {
            min-height: 100svh;
            background: var(--q-void);
            color: var(--q-ice);
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            overflow-x: hidden;
        }

        .q-board-face { font-family: 'Barlow Condensed', 'Instrument Sans', sans-serif; }
        .q-data       { font-family: 'DM Mono', ui-monospace, monospace; }

        /* ---- Board substrate: LED dot matrix over a navy wash ---- */
        .q-dots {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image: radial-gradient(circle, rgba(206, 225, 255, 0.14) 1px, transparent 1px);
            background-size: 22px 22px;
            mask-image: radial-gradient(ellipse 90% 70% at 30% 40%, #000 35%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 90% 70% at 30% 40%, #000 35%, transparent 100%);
        }
        .q-wash {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 70% 60% at 22% 30%, rgba(0, 26, 113, 0.85), transparent 70%),
                radial-gradient(ellipse 60% 50% at 85% 80%, rgba(8, 95, 197, 0.30), transparent 70%);
        }
        .q-orb {
            position: fixed; border-radius: 9999px; pointer-events: none; z-index: 0;
            filter: blur(90px);
            animation: q-drift 24s ease-in-out infinite;
        }
        .q-orb--a { top: -8rem; left: -6rem;  width: 32rem; height: 32rem; background: rgba(30, 175, 255, 0.16); }
        .q-orb--b { bottom: -10rem; right: -8rem; width: 34rem; height: 34rem; background: rgba(8, 95, 197, 0.20); animation-delay: -9s; }

        @keyframes q-drift {
            0%, 100% { transform: translate(0, 0); }
            33%      { transform: translate(40px, -30px); }
            66%      { transform: translate(-30px, 35px); }
        }

        /* ---- Split-flap reel ----
           Window height and cell height must be the exact same length, or the
           window shows two glyphs at once. Both are driven off --flap-h. */
        .q-flap {
            --flap-h: 1em;
            display: block;
            width: 0.6em;
            height: var(--flap-h);
            overflow: hidden;
            position: relative;
            border-radius: 0.06em;
            background: linear-gradient(180deg, #00061c 0%, #000f37 48%, #00061c 52%, #000d2e 100%);
            box-shadow: inset 0 0 0 1px rgba(206, 225, 255, 0.14),
                        inset 0 -14px 24px rgba(0, 0, 0, 0.55);
        }
        /* the hairline where a real flap hinges */
        .q-flap::after {
            content: ''; position: absolute; left: 0; right: 0; top: 50%;
            height: 1px; background: rgba(0, 0, 0, 0.75); z-index: 2;
        }
        .q-flap-reel { display: block; }
        .q-flap-cell {
            display: block;
            height: var(--flap-h);
            line-height: var(--flap-h);
            text-align: center;
            font-variant-numeric: tabular-nums;
            color: var(--q-ice);
            text-shadow: 0 0 18px rgba(30, 175, 255, 0.55);
        }

        .q-plate {
            background: linear-gradient(160deg, rgba(0, 13, 61, 0.92), rgba(0, 6, 28, 0.92));
            border: 1px solid rgba(206, 225, 255, 0.16);
            border-radius: 1.25rem;
        }

        /* light bar that crosses the board when a number is called */
        .q-sweep {
            position: absolute; inset: 0; pointer-events: none; opacity: 0;
            background: linear-gradient(100deg, transparent 35%, rgba(30, 175, 255, 0.20) 50%, transparent 65%);
        }

        .q-live::before {
            content: ''; position: absolute; inset: 0; border-radius: 9999px;
            background: #22c55e; animation: q-ping 1.6s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        @keyframes q-ping { 75%, 100% { transform: scale(2.4); opacity: 0; } }

        /* ---- Counter terminal card ---- */
        .q-card {
            background: rgba(255, 255, 255, 0.035);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 1.5rem;
            box-shadow: 0 40px 80px -30px rgba(0, 0, 0, 0.9);
        }

        .q-field {
            width: 100%;
            background: rgba(0, 8, 28, 0.6);
            border: 1px solid rgba(206, 225, 255, 0.16);
            border-radius: 0.75rem;
            padding: 0.75rem 0.9rem;
            color: #fff;
            font-size: 0.95rem;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
        }
        .q-field::placeholder { color: rgba(206, 225, 255, 0.35); }
        .q-field:focus {
            outline: none;
            background: rgba(0, 8, 28, 0.85);
            border-color: rgba(30, 175, 255, 0.75);
            box-shadow: 0 0 0 3px rgba(30, 175, 255, 0.18);
        }
        .q-field--error { border-color: rgba(239, 68, 68, 0.8); }

        .q-submit {
            position: relative; overflow: hidden;
            background: linear-gradient(90deg, var(--q-blue), var(--q-led));
            box-shadow: 0 14px 30px -10px rgba(30, 175, 255, 0.55);
            transition: transform 0.15s ease, box-shadow 0.25s ease, filter 0.25s ease;
        }
        .q-submit:hover  { box-shadow: 0 18px 40px -10px rgba(30, 175, 255, 0.75); filter: brightness(1.06); }
        .q-submit:active { transform: scale(0.985); }
        .q-submit::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 45%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.38), transparent);
            transform: skewX(-22deg); transition: left 0.65s ease;
        }
        .q-submit:hover::before { left: 160%; }

        .q-link { color: var(--q-led); transition: color 0.15s ease; }
        .q-link:hover { color: #fff; }

        [x-cloak] { display: none !important; }

        :where(a, button, input, [tabindex]):focus-visible {
            outline: 2px solid var(--q-led);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            .q-orb, .q-live::before { animation: none; }
            .q-submit::before { transition: none; }
        }
    </style>
</head>

<body class="q-auth antialiased">
    <div class="q-wash" aria-hidden="true"></div>
    <div class="q-dots" aria-hidden="true"></div>
    <div class="q-orb q-orb--a" aria-hidden="true"></div>
    <div class="q-orb q-orb--b" aria-hidden="true"></div>

    <div class="relative z-10 grid min-h-svh w-full max-w-full grid-cols-1 overflow-x-hidden lg:grid-cols-[1.05fr_minmax(400px,0.85fr)]">

        {{-- ============ THE BOARD ============ --}}
        {{-- min-w-0: without it the chips row sets a min-content width that forces
             the whole grid column wider than a phone viewport. --}}
        <section class="relative flex min-w-0 flex-col overflow-hidden p-6 sm:p-10 lg:p-14" data-board>
            <div class="q-sweep" data-sweep aria-hidden="true"></div>

            {{-- bezel strip --}}
            <div class="q-data relative flex items-center justify-between gap-4 border-b border-white/10 pb-4 text-[11px] uppercase tracking-[0.18em] text-white/55"
                 data-anim="bezel">
                <div class="flex items-center gap-2.5">
                    <span class="q-live relative inline-flex h-2 w-2 rounded-full bg-green-500"></span>
                    <span class="text-green-400">Live</span>
                    <span class="hidden text-white/25 sm:inline">/</span>
                    <span class="hidden sm:inline">Main City Hall · HQ01</span>
                </div>
                <span data-clock class="tabular-nums">—</span>
            </div>

            {{-- now serving --}}
            <div class="relative flex flex-1 flex-col justify-center py-10">
                <p class="q-data mb-5 text-[11px] uppercase tracking-[0.4em] text-[color:var(--q-led)]" data-anim="board-label">
                    Now serving
                </p>

                <div class="q-plate inline-flex w-fit items-center gap-2.5 p-5 sm:gap-3.5 sm:p-6" data-plate data-anim="plate">
                    <span class="q-board-face flex gap-1.5 font-bold leading-none sm:gap-2"
                          style="font-size: clamp(4.5rem, 12vw, 9.5rem);">
                        <span class="q-flap" data-flap>0</span>
                        <span class="q-flap" data-flap>0</span>
                        <span class="q-flap" data-flap>0</span>
                    </span>
                </div>

                <div class="q-data mt-5 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs sm:text-sm">
                    <span class="font-medium uppercase tracking-[0.12em] text-[color:var(--q-amber)] sm:tracking-[0.16em]" data-counter>Counter 1</span>
                    <span class="text-white/25">·</span>
                    <span class="uppercase tracking-[0.12em] text-white/60 sm:tracking-[0.16em]" data-service>Business Permit</span>
                </div>

                {{-- waiting sits with the call, not stranded at the far edge of the board --}}
                <div class="q-data mt-8 flex flex-wrap items-center gap-2 border-t border-white/10 pt-5 text-[11px] sm:mt-10 sm:gap-2.5 sm:text-xs" data-anim="waiting">
                    <span class="mr-1 uppercase tracking-[0.2em] text-white/40 sm:tracking-[0.28em]">Waiting</span>
                    @for ($i = 0; $i < 5; $i++)
                        <span class="rounded-md border border-white/10 bg-white/[0.04] px-2 py-1 tabular-nums text-white/75 sm:px-2.5"
                              @class(['hidden sm:inline' => $i >= 3]) data-waiting>—</span>
                    @endfor
                    <span class="text-white/35">+14 more</span>
                </div>
            </div>
        </section>

        {{-- ============ COUNTER TERMINAL ============ --}}
        <section class="relative flex min-w-0 items-center justify-center p-6 sm:p-10 lg:border-l lg:border-white/[0.07] lg:bg-black/25">
            <div class="w-full max-w-[380px]">
                <div class="q-card p-7 sm:p-8">
                    {{ $slot }}
                </div>

                <p class="q-data mt-6 text-center text-[11px] uppercase tracking-[0.18em] text-white/30">
                    Kiosks and display screens run without an account
                </p>
            </div>
        </section>
    </div>

    @fluxScripts
</body>
</html>
