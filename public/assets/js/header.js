/**
 * Infobulles du header : affichées au survol (souris) et au focus clavier
 * pour tout élément portant un attribut data-tooltip.
 * Désactivées sur les appareils sans vrai survol (tactile).
 */
(() => {
    'use strict';

    const header = document.querySelector('.header');
    if (!header) return;

    const OFFSET = 8;   // distance entre le bouton et l'infobulle (px)
    const MARGIN = 8;   // marge minimale avec le bord de l'écran (px)
    const hoverMedia = window.matchMedia('(hover: hover)');

    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.id = 'header-tooltip';
    tooltip.setAttribute('role', 'tooltip');
    document.body.appendChild(tooltip);

    let current = null;

    const hide = () => {
        tooltip.classList.remove('is-visible');
        current = null;
    };

    const show = (target) => {
        if (!hoverMedia.matches || target === current) return;

        current = target;
        tooltip.textContent = target.dataset.tooltip;

        // Mesure avant affichage, puis centrage sous le bouton sans sortir de l'écran
        const rect = target.getBoundingClientRect();
        const width = tooltip.offsetWidth;
        const maxLeft = document.documentElement.clientWidth - width - MARGIN;
        const left = Math.min(Math.max(rect.left + rect.width / 2 - width / 2, MARGIN), Math.max(maxLeft, MARGIN));

        tooltip.style.setProperty('--tooltip-x', `${Math.round(left)}px`);
        tooltip.style.setProperty('--tooltip-y', `${Math.round(rect.bottom + OFFSET)}px`);
        tooltip.classList.add('is-visible');
    };

    const targetOf = (event) => event.target instanceof Element ? event.target.closest('[data-tooltip]') : null;

    // Délégation : mouseover/mouseout remontent, contrairement à mouseenter/mouseleave
    header.addEventListener('mouseover', (event) => {
        const target = targetOf(event);
        if (target && header.contains(target)) show(target);
    });

    header.addEventListener('mouseout', (event) => {
        const target = targetOf(event);
        if (target && !target.contains(event.relatedTarget)) hide();
    });

    // Clavier uniquement : un clic souris donne aussi le focus, sans :focus-visible
    header.addEventListener('focusin', (event) => {
        const target = targetOf(event);
        if (target && target.matches(':focus-visible')) show(target);
    });

    header.addEventListener('focusout', hide);

    window.addEventListener('scroll', hide, { passive: true, capture: true });
    window.addEventListener('resize', hide);

    // WCAG 1.4.13 : l'infobulle doit pouvoir être fermée sans bouger le pointeur
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') hide();
    });

    // Placeholder de recherche raccourci sur tablette / mobile (<= 1024px)
    const searchInput = header.querySelector('input[data-placeholder-desktop]');
    const compactMedia = window.matchMedia('(max-width: 1024px)');

    const updatePlaceholder = () => {
        if (!searchInput) return;
        searchInput.placeholder = compactMedia.matches
            ? searchInput.dataset.placeholderMobile
            : searchInput.dataset.placeholderDesktop;
    };

    updatePlaceholder();
    window.addEventListener('resize', updatePlaceholder);
})();
