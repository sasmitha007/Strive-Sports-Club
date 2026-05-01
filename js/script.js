// Password toggle function (used in register.php & login.php)
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

// Password strength meter (register)
function checkStrength(password) {
    const fill = document.getElementById('strength-fill');
    const text = document.getElementById('strength-text');

    let strength = 0;
    if (password.length >= 6) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[\W_]/.test(password)) strength++;

    switch (strength) {
        case 0:
        case 1:
            fill.style.width = '20%';
            fill.style.backgroundColor = '#dc2626';
            text.textContent = 'Very weak';
            text.style.color = '#dc2626';
            break;
        case 2:
            fill.style.width = '40%';
            fill.style.backgroundColor = '#f97316';
            text.textContent = 'Weak';
            text.style.color = '#f97316';
            break;
        case 3:
            fill.style.width = '60%';
            fill.style.backgroundColor = '#eab308';
            text.textContent = 'Moderate';
            text.style.color = '#eab308';
            break;
        case 4:
            fill.style.width = '80%';
            fill.style.backgroundColor = '#10b981';
            text.textContent = 'Strong';
            text.style.color = '#10b981';
            break;
        case 5:
            fill.style.width = '100%';
            fill.style.backgroundColor = '#059669';
            text.textContent = 'Very strong';
            text.style.color = '#059669';
            break;
    }

    checkMatch(); // Recheck in case user updates original password
}

// Confirm password match
function checkMatch() {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    const matchText = document.getElementById('match-text');

    if (!confirm) {
        matchText.innerHTML = '';
        return;
    }

    if (password === confirm) {
        matchText.innerHTML = `<span class="text-green-400">✔ Passwords match</span>`;
    } else {
        matchText.innerHTML = `<span class="text-red-500">✖ Passwords do not match</span>`;
    }
}

// Back to Top Button
document.addEventListener('DOMContentLoaded', () => {
    const backToTopBtn = document.getElementById("backToTopBtn");

    if (backToTopBtn) {
        window.addEventListener("scroll", () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.remove("opacity-0");
            } else {
                backToTopBtn.classList.add("opacity-0");
            }
        });

        backToTopBtn.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
});

// rating (modern 5-star) + target_id spinner rules
(function () {
    function initStars() {
        const wrap = document.getElementById('ratingStars');
        if (!wrap) return; // quietly exit on pages without the widget

        const text   = document.getElementById('ratingValue');
        const radios = Array.from(wrap.querySelectorAll('input[name="rating"]'));
        const labels = Array.from(wrap.querySelectorAll('label[data-value]'));

        function paint(n) {
        labels.forEach(lbl => {
            const svg = lbl.querySelector('svg');
            if (!svg) return;
            svg.style.opacity = Number(lbl.dataset.value) <= Number(n) ? '1' : '0.35';
        });
        if (text) text.textContent = n ? (n + '/5') : 'No rating';
        }

        function current() {
        const c = radios.find(r => r.checked);
        return c ? Number(c.value) : 0;
        }

        // initial render (handles back/forward cache too)
        paint(current());

        // hover preview (event delegation so clicks on SVG still work)
        wrap.addEventListener('mousemove', (e) => {
        const lbl = e.target.closest('label[data-value]');
        if (lbl && wrap.contains(lbl)) paint(lbl.dataset.value);
        });

        // restore on leave
        wrap.addEventListener('mouseleave', () => paint(current()));

        // click to select
        wrap.addEventListener('click', (e) => {
        const lbl = e.target.closest('label[data-value]');
        if (!lbl || !wrap.contains(lbl)) return;
        const val   = Number(lbl.dataset.value);
        const radio = document.getElementById('star' + val);
        if (radio) {
            radio.checked = true;
            // fire change so any listeners run
            radio.dispatchEvent(new Event('change', { bubbles: true }));
        }
        paint(val);
        });

        // keyboard support on radios (arrows to change)
        radios.forEach(r => {
        r.addEventListener('change', () => paint(r.value));
        r.addEventListener('keydown', (e) => {
            const v = current();
            if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
            const next = Math.min(5, v + 1);
            const n = document.getElementById('star' + next);
            if (n) { n.checked = true; n.focus(); paint(next); e.preventDefault(); }
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
            const prev = Math.max(1, v - 1);
            const p = document.getElementById('star' + prev);
            if (p) { p.checked = true; p.focus(); paint(prev); e.preventDefault(); }
            }
        });
        });
    }

    // Target ID rules:
    // - Empty + ArrowUp => 1
    // - Empty + ArrowDown => stays empty
    // - >1 + ArrowDown => decrement, but never below 1
    function initTargetId() {
        const input = document.getElementById('target_id');
        if (!input) return;

        input.setAttribute('min', '1');
        input.setAttribute('step', '1');
        input.setAttribute('inputmode', 'numeric');

        input.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowUp') {
            if (input.value === '' || isNaN(input.value)) {
            input.value = '1';
            e.preventDefault();
            }
        } else if (e.key === 'ArrowDown') {
            if (input.value === '' || isNaN(input.value)) {
            // keep empty
            e.preventDefault();
            } else {
            const v = parseInt(input.value, 10);
            if (!isNaN(v) && v <= 1) {
                e.preventDefault(); // don’t go below 1
            }
            }
        }
        });

        // Optional: normalize on blur (keep empty allowed)
        input.addEventListener('blur', function () {
        if (input.value !== '') {
            const v = parseInt(input.value, 10);
            if (isNaN(v) || v < 1) input.value = '1';
        }
        });

        // Optional: control mouse wheel when focused
        input.addEventListener('wheel', function (e) {
        if (document.activeElement !== input) return;
        if (input.value === '' || isNaN(input.value)) {
            if (e.deltaY < 0) { // scroll up
            input.value = '1';
            } // scroll down: keep empty
            e.preventDefault();
        } else {
            const v = parseInt(input.value, 10);
            if (v <= 1 && e.deltaY > 0) {
            e.preventDefault(); // don’t go below 1
            }
        }
        }, { passive: false });
    }

    function initAll() {
        initStars();
        initTargetId();
    }

    // run after DOM is ready (and also works if DOM is already ready)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();