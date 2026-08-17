import gsap from 'gsap';

/**
 * The auth screen runs a live queue board behind the sign-in panel.
 * Everything here is decoration — if it never boots, the form still works.
 */

const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* Sample board traffic. Real numbers arrive once the staff member is signed in. */
const COUNTERS = [
    { counter: 'Counter 1', service: 'Business Permit' },
    { counter: 'Counter 2', service: 'Application Tracking' },
    { counter: 'Counter 3', service: 'Real Property Tax' },
    { counter: 'Counter 1', service: 'Civil Registry' },
];

const DIGITS_PER_REEL = 20; // 0-9 twice, so a call can roll a full cycle before landing
const STEP = 100 / DIGITS_PER_REEL; // yPercent per digit

function buildReels(board) {
    board.querySelectorAll('[data-flap]').forEach((flap) => {
        const reel = document.createElement('span');
        reel.className = 'q-flap-reel';
        for (let pass = 0; pass < 2; pass++) {
            for (let n = 0; n <= 9; n++) {
                const cell = document.createElement('span');
                cell.className = 'q-flap-cell';
                cell.textContent = n;
                reel.appendChild(cell);
            }
        }
        flap.textContent = '';
        flap.appendChild(reel);
        flap._reel = reel;
        flap._digit = 0;
    });
}

function rollTo(board, value) {
    const flaps = [...board.querySelectorAll('[data-flap]')];
    const target = String(value).padStart(flaps.length, '0').split('');

    flaps.forEach((flap, i) => {
        const digit = Number(target[i]);
        const reel = flap._reel;

        if (REDUCED) {
            gsap.set(reel, { yPercent: -digit * STEP });
            flap._digit = digit;
            return;
        }

        // Land on the second pass so the reel visibly cycles, then snap back to
        // the identical first-pass position — same glyph, so the jump is invisible.
        gsap.fromTo(
            reel,
            { yPercent: -flap._digit * STEP },
            {
                yPercent: -(10 + digit) * STEP,
                duration: 1.15 + i * 0.18,
                ease: 'power4.out',
                onComplete: () => gsap.set(reel, { yPercent: -digit * STEP }),
            }
        );

        flap._digit = digit;
    });
}

function initBoard(board) {
    buildReels(board);

    const plate = board.querySelector('[data-plate]');
    const counterEl = board.querySelector('[data-counter]');
    const serviceEl = board.querySelector('[data-service]');
    const sweep = board.querySelector('[data-sweep]');
    const waitingEls = [...board.querySelectorAll('[data-waiting]')];

    let ticket = 18 + Math.floor(Math.random() * 40);
    let seat = 0;

    const call = () => {
        ticket += 1 + Math.floor(Math.random() * 3);
        seat = (seat + 1) % COUNTERS.length;

        rollTo(board, ticket);

        if (counterEl) counterEl.textContent = COUNTERS[seat].counter;
        if (serviceEl) serviceEl.textContent = COUNTERS[seat].service;

        // Waiting chips are always the tickets queued behind the one being called.
        waitingEls.forEach((el, i) => {
            el.textContent = String(ticket + i + 1).padStart(3, '0');
        });

        if (REDUCED) return;

        gsap.fromTo(plate, { boxShadow: '0 0 0 0 rgba(30,175,255,0)' }, {
            boxShadow: '0 0 42px 4px rgba(30,175,255,0.45)',
            duration: 0.4,
            yoyo: true,
            repeat: 1,
            ease: 'power2.out',
        });

        gsap.fromTo(counterEl?.parentElement ?? plate, { opacity: 0.25, y: 6 }, {
            opacity: 1, y: 0, duration: 0.5, ease: 'power3.out',
        });

        if (sweep) {
            gsap.fromTo(sweep, { xPercent: -120, opacity: 0.9 }, {
                xPercent: 120, opacity: 0, duration: 1.4, ease: 'power2.inOut',
            });
        }

        gsap.fromTo(waitingEls, { opacity: 0, x: 10 }, {
            opacity: 1, x: 0, duration: 0.45, stagger: 0.06, ease: 'power3.out',
        });
    };

    call();
    if (!REDUCED) {
        gsap.delayedCall(2.4, function repeat() {
            call();
            gsap.delayedCall(7, repeat);
        });
    }
}

function initEntrance() {
    if (REDUCED) return;

    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    // Each step is optional — the auth shell is shared by every auth screen,
    // and only login renders the full board.
    const step = (selector, vars, position) => {
        const targets = document.querySelectorAll(selector);
        if (targets.length) tl.from(targets, vars, position);
    };

    step('[data-anim="bezel"]', { opacity: 0, duration: 0.5 });
    step('[data-anim="board-label"]', { opacity: 0, y: 18, duration: 0.6 }, '-=0.2');
    step('[data-anim="plate"]', { opacity: 0, y: 24, scale: 0.97, duration: 0.7 }, '-=0.35');
    step('[data-anim="waiting"]', { opacity: 0, y: 14, duration: 0.5 }, '-=0.4');
    step('[data-anim="panel"] > *', { opacity: 0, y: 16, duration: 0.5, stagger: 0.07 }, '-=0.6');
}

function initClock() {
    const el = document.querySelector('[data-clock]');
    if (!el) return;

    const tick = () => {
        el.textContent = new Date().toLocaleTimeString('en-US', {
            hour: '2-digit', minute: '2-digit', hour12: true, timeZone: 'Asia/Manila',
        });
    };
    tick();
    setInterval(tick, 1000);
}

function boot() {
    const board = document.querySelector('[data-board]');
    if (board) initBoard(board);
    initClock();
    initEntrance();
}

document.addEventListener('DOMContentLoaded', boot);
