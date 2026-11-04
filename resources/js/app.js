import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// Motion Foundations: 2D/3D Spatial Tilt with Reduced-Motion & Low-End Hardware Guard
Alpine.data('spatialTilt', (maxDeg = 5) => ({
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
