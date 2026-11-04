import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

document.documentElement.classList.add('js');

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.data('spatialTilt', (maxDeg = 4) => ({
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
}));

Alpine.start();

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
    document.addEventListener('DOMContentLoaded', initScrollReveals);
} else {
    initScrollReveals();
}
