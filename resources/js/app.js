import Chart from 'chart.js/auto';

document.documentElement.classList.add('js');

window.Chart = Chart;

window.copyToClipboard = async function (text) {
    const value = String(text ?? '').trim();
    if (!value) return false;

    if (navigator.clipboard && window.isSecureContext) {
        try {
            await navigator.clipboard.writeText(value);
            return true;
        } catch (_) {
            // Fallback below
        }
    }

    const textarea = document.createElement('textarea');
    textarea.value = value;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        return true;
    } finally {
        document.body.removeChild(textarea);
    }
};

function formatRupiahDigits(raw) {
    const digits = String(raw ?? '').replace(/\D+/g, '');
    if (!digits) return '';
    return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function initRupiahInputs(root = document) {
    const selector = [
        'input[data-rupiah]',
        'input[name="total_budget"]',
        'input[name="estimated_cost"]',
        'input[name="actual_cost"]',
        'input[name="paid_amount"]',
        'input[name="contract_amount"]',
        'input[name="amount"]',
    ].join(', ');

    root.querySelectorAll(selector).forEach((input) => {
        if (input.dataset.rupiahInitialized === '1' || input.type === 'hidden') {
            return;
        }
        input.dataset.rupiahInitialized = '1';

        const fieldName = input.getAttribute('name');
        if (!fieldName) return;

        const initialDigits = String(input.value ?? '').replace(/\D+/g, '');

        // Create hidden input that holds raw integer value for backend validation
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = fieldName;
        hiddenInput.value = initialDigits;
        input.parentNode.insertBefore(hiddenInput, input.nextSibling);

        // Convert visible input into thousand-formatted text input
        input.removeAttribute('name');
        input.removeAttribute('step');
        input.removeAttribute('min');
        input.setAttribute('type', 'text');
        input.setAttribute('inputmode', 'numeric');
        input.setAttribute('autocomplete', 'off');
        input.dataset.rupiahField = fieldName;
        input.value = formatRupiahDigits(initialDigits);

        const syncValue = () => {
            const rawDigits = String(input.value ?? '').replace(/\D+/g, '');
            hiddenInput.value = rawDigits;
            const formatted = formatRupiahDigits(rawDigits);
            if (input.value !== formatted) {
                input.value = formatted;
            }
        };

        input.addEventListener('input', syncValue);
        input.addEventListener('blur', syncValue);
    });
}

const spatialTilt = (maxDeg = 4) => ({
    transformStyle: 'perspective(1100px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)',
    prefersReduced: typeof window !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    handleMove(event) {
        if (this.prefersReduced) return;
        const rect = event.currentTarget.getBoundingClientRect();
        const x = (event.clientX - rect.left) / rect.width - 0.5;
        const y = (event.clientY - rect.top) / rect.height - 0.5;
        const rotateY = (x * maxDeg * 2).toFixed(2);
        const rotateX = (-y * maxDeg * 2).toFixed(2);
        this.transformStyle = `perspective(1100px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.01, 1.01, 1.01)`;
    },
    handleLeave() {
        this.transformStyle = 'perspective(1100px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
    }
});

// Livewire 4 injects its own Alpine bundle; register on init so only one instance runs.
document.addEventListener('alpine:init', () => {
    window.Alpine.data('spatialTilt', spatialTilt);
});

// Scroll-triggered entrance choreography (fires once per element, respects reduced-motion)
function initScrollReveals() {
    if (typeof window === 'undefined' || !('IntersectionObserver' in window)) {
        document.querySelectorAll('[data-reveal]').forEach((el) => el.classList.add('is-in'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-in');
                    observer.unobserve(entry.target);
                }
            }
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
    );

    document.querySelectorAll('[data-reveal]').forEach((el) => observer.observe(el));
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initScrollReveals();
        initRupiahInputs();
    });
} else {
    initScrollReveals();
    initRupiahInputs();
}
