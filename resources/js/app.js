

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Scroll-driven page background: interpolates between a yellow-orange gradient
// at the top of the page and a red-plum gradient at the bottom, based on how
// far the user has scrolled through the whole document (not just one section).
(function () {
    const bg = document.getElementById('scroll-gradient-bg');
    if (!bg) return;

    // Colors are admin-configurable (Setting model) and passed in via data attributes,
    // with the original design's values as fallback.
    const TOP = [bg.dataset.topStart || '#fbbf24', bg.dataset.topEnd || '#f97316'];
    const BOTTOM = [bg.dataset.bottomStart || '#dc2626', bg.dataset.bottomEnd || '#6b1d3f'];

    const lerp = (a, b, t) => Math.round(a + (b - a) * t);

    const lerpColor = (hexA, hexB, t) => {
        const a = [1, 3, 5].map((i) => parseInt(hexA.slice(i, i + 2), 16));
        const b = [1, 3, 5].map((i) => parseInt(hexB.slice(i, i + 2), 16));
        const [r, g, bl] = a.map((v, i) => lerp(v, b[i], t));
        return `rgb(${r}, ${g}, ${bl})`;
    };

    let ticking = false;

    function applyGradient() {
        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        const progress = scrollable > 0 ? Math.min(1, Math.max(0, window.scrollY / scrollable)) : 0;

        const from = lerpColor(TOP[0], BOTTOM[0], progress);
        const to = lerpColor(TOP[1], BOTTOM[1], progress);

        bg.style.backgroundImage = `linear-gradient(135deg, ${from}, ${to})`;
        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(applyGradient);
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    applyGradient();
})();

// Row title animations (admin "მოძრავი ტექსტი" setting, Row::TEXT_ANIMATIONS). Each
// [data-text-anim] element is armed by CSS (resources/css/app.css) and only actually animates
// once scrolled into view, played once.
(function () {
    const targets = document.querySelectorAll('[data-text-anim]');
    if (!targets.length) return;

    function runStagger(el) {
        const chars = Array.from(el.textContent);
        el.textContent = '';
        chars.forEach((ch, i) => {
            if (ch === ' ') {
                el.append(' ');
                return;
            }
            const span = document.createElement('span');
            span.className = 'text-anim-char';
            span.style.transitionDelay = `${i * 30}ms`;
            span.textContent = ch;
            el.append(span);
        });
        requestAnimationFrame(() => el.classList.add('text-anim-play'));
    }

    function runTypewriter(el) {
        const text = el.textContent;
        el.textContent = '';
        let i = 0;
        const interval = setInterval(() => {
            i += 1;
            el.textContent = text.slice(0, i);
            if (i >= text.length) clearInterval(interval);
        }, 60);
    }

    // Cycles through several phrases forever: type one in, hold, then clear and type the next —
    // looping back to the first once the last one finishes. No erase animation (it read as the
    // text "shrinking") and the text stays put — width is reserved for the longest phrase up
    // front so the heading never resizes/shifts between phrases.
    function runRotate(el) {
        let phrases;
        try {
            phrases = JSON.parse(el.dataset.textRotate || '[]');
        } catch (e) {
            phrases = [];
        }
        if (phrases.length < 2) return;

        // Measure inside el's own parent (not document.body) so the probe inherits the
        // heading's actual font (family/size/italic) — measuring in the body's default font
        // produced a maxWidth that didn't match any phrase's true rendered width, leaving the
        // fixed-width box too narrow and the centered text overflowing it asymmetrically.
        const probe = document.createElement('span');
        probe.style.position = 'absolute';
        probe.style.visibility = 'hidden';
        probe.style.whiteSpace = 'nowrap';
        probe.style.display = 'inline-block';
        el.parentElement.append(probe);
        let maxWidth = 0;
        phrases.forEach((text) => {
            probe.textContent = text;
            maxWidth = Math.max(maxWidth, probe.offsetWidth);
        });
        probe.remove();
        el.style.width = `${maxWidth}px`;

        let index = 0;

        function typeText(text, done) {
            let i = 0;
            const interval = setInterval(() => {
                i += 1;
                el.textContent = text.slice(0, i);
                if (i >= text.length) {
                    clearInterval(interval);
                    done();
                }
            }, 60);
        }

        function cycle() {
            typeText(phrases[index], () => {
                setTimeout(() => {
                    el.textContent = '';
                    index = (index + 1) % phrases.length;
                    cycle();
                }, 1800);
            });
        }

        cycle();
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const type = el.dataset.textAnim;
            if (type === 'stagger') runStagger(el);
            else if (type === 'typewriter') runTypewriter(el);
            else if (type === 'rotate') runRotate(el);
            else el.classList.add('text-anim-play');
            obs.unobserve(el);
        });
    }, { threshold: 0.3 });

    targets.forEach((el) => observer.observe(el));
})();
