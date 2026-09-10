/**
 * Boneyard Skeleton Loading Engine for WMSU REO
 * Powered by boneyard-js (https://github.com/0xGF/boneyard)
 * 
 * Features:
 * - Pixel-perfect, zero-layout-shift (CLS = 0) skeletons
 * - Adaptive Slow-Network & Perceptible-Delay Detection:
 *     * Fast loads (<450ms): ZERO skeleton flicker (content shows immediately)
 *     * Slow loads (>450ms or slow-2g/2g/save-data): Smooth pulse shimmer with min-duration hold
 * - Dynamic DOM snapshotting to sessionStorage
 * - Overlay positioning to prevent content jumps or layout shifts
 */

import { renderBones, snapshotBones } from 'boneyard-js';
import { BoneyardTemplates } from './boneyard-templates.js';

class BoneyardEngine {
    constructor() {
        this.templates = BoneyardTemplates;
        this.activeSkeletons = new Map();
        this.defaultDelay = 450; // 450ms perceptible threshold to ensure fast loads never flash
        this.minHoldTime = 400; // Minimum time to display skeleton if shown, avoiding micro-flickers
        this.color = '#e2e8f0'; // slate-200 tinted pulse
    }

    /**
     * Checks if current client connection is slow or bandwidth-constrained
     * @returns {boolean}
     */
    detectSlowNetwork() {
        if (typeof navigator !== 'undefined' && 'connection' in navigator) {
            const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (conn) {
                // Only consider genuinely constrained networks
                if (conn.saveData) return true;
                if (conn.effectiveType === 'slow-2g' || conn.effectiveType === '2g') {
                    return true;
                }
                if (typeof conn.rtt === 'number' && conn.rtt > 450) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Retrieve skeleton bones for a given key, checking sessionStorage first
     * @param {string} key 
     * @returns {object|null}
     */
    getSkeletonData(key) {
        try {
            const cached = sessionStorage.getItem(`boneyard_bones_${key}`);
            if (cached) {
                const parsed = JSON.parse(cached);
                if (parsed && Array.isArray(parsed.bones) && parsed.bones.length > 0) {
                    return parsed;
                }
            }
        } catch (e) {
            // sessionStorage unavailable or quota exceeded
        }

        return this.templates[key] || null;
    }

    /**
     * Render HTML string for a skeleton template
     * @param {string} key 
     * @param {string} [color]
     * @returns {string}
     */
    renderTemplateHtml(key, color = this.color) {
        const data = this.getSkeletonData(key);
        if (!data) return '';
        return renderBones(data, color, true);
    }

    /**
     * Snapshot an element's real DOM geometry using boneyard-js
     * and store in sessionStorage for next load
     * @param {HTMLElement} element 
     * @param {string} key 
     */
    snapshot(element, key) {
        if (!element || typeof window === 'undefined') return;
        try {
            const bonesData = snapshotBones(element, key);
            if (bonesData && bonesData.bones && bonesData.bones.length > 0) {
                sessionStorage.setItem(`boneyard_bones_${key}`, JSON.stringify(bonesData));
            }
        } catch (err) {
            console.debug('[Boneyard] Snapshot note:', err);
        }
    }

    /**
     * Attach Boneyard skeleton lifecycle to a page container
     * @param {object} options
     * @param {HTMLElement|string} options.skeleton - Skeleton container element or selector
     * @param {HTMLElement|string} options.content - Real content container element or selector
     * @param {string} options.templateKey - Template key ('landing', 'researcher', 'admin', 'reviewer')
     * @param {number} [options.delay=450] - Perceptible delay threshold in ms
     * @param {number} [options.timeout=6000] - Safety fallback timeout
     */
    attach(options) {
        const skeletonEl = typeof options.skeleton === 'string'
            ? document.querySelector(options.skeleton)
            : options.skeleton;

        const contentEl = typeof options.content === 'string'
            ? document.querySelector(options.content)
            : options.content;

        if (!skeletonEl) return;

        const templateKey = options.templateKey || 'landing';
        const delay = options.delay ?? this.defaultDelay;
        const isSlow = this.detectSlowNetwork();
        let isShown = false;
        let isFinished = false;
        let shownTime = 0;
        let timer = null;

        // Populate skeleton HTML ONLY if completely empty
        if (!skeletonEl.innerHTML.trim() && skeletonEl.children.length === 0) {
            const renderedHtml = this.renderTemplateHtml(templateKey);
            if (renderedHtml) {
                skeletonEl.innerHTML = renderedHtml;
            }
        }

        const showSkeleton = () => {
            if (isFinished || isShown) return;
            isShown = true;
            shownTime = Date.now();
            skeletonEl.style.display = 'block';
            requestAnimationFrame(() => {
                skeletonEl.style.opacity = '1';
            });
            skeletonEl.setAttribute('aria-busy', 'true');
        };

        const hideSkeleton = () => {
            if (isFinished) return;
            isFinished = true;

            if (timer) {
                clearTimeout(timer);
                timer = null;
            }

            if (isShown) {
                // Enforce minHoldTime to prevent micro-flickers
                const elapsed = Date.now() - shownTime;
                const remaining = Math.max(0, this.minHoldTime - elapsed);

                setTimeout(() => {
                    skeletonEl.style.transition = 'opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1)';
                    skeletonEl.style.opacity = '0';
                    skeletonEl.setAttribute('aria-busy', 'false');

                    setTimeout(() => {
                        skeletonEl.style.display = 'none';
                        if (contentEl) {
                            contentEl.style.removeProperty('display');
                            contentEl.style.removeProperty('opacity');
                        }
                    }, 350);
                }, remaining);
            } else {
                // On fast connections: skeleton was never shown, keep hidden cleanly without touching content
                skeletonEl.style.display = 'none';
                skeletonEl.setAttribute('aria-busy', 'false');
            }

            // Snapshot real layout for future slow requests after a calm delay
            if (contentEl) {
                setTimeout(() => {
                    if (!sessionStorage.getItem(`boneyard_bones_${templateKey}`)) {
                        this.snapshot(contentEl, templateKey);
                    }
                }, 1200);
            }
        };

        // Fast connection handling:
        // If DOM is already interactive or DOMContentLoaded fires quickly, cancel timer and NEVER show skeleton!
        if (document.readyState === 'interactive' || document.readyState === 'complete') {
            hideSkeleton();
        } else {
            document.addEventListener('DOMContentLoaded', hideSkeleton, { once: true });
            window.addEventListener('load', hideSkeleton, { once: true });

            // If network is known to be slow (2G/slow-2g/save-data), show skeleton immediately
            if (isSlow) {
                showSkeleton();
            } else {
                // Fast network: wait 450ms. If page resolves in <450ms, timer is cancelled!
                timer = setTimeout(() => {
                    if (!isFinished) {
                        showSkeleton();
                    }
                }, delay);

                this.activeSkeletons.set(skeletonEl, timer);
            }
        }

        // Safety fallback timeout
        setTimeout(hideSkeleton, options.timeout || 6000);

        return {
            show: showSkeleton,
            hide: hideSkeleton
        };
    }

    /**
     * Auto-mount all elements matching data-boneyard on the page
     */
    init() {
        if (typeof document === 'undefined') return;

        document.querySelectorAll('[data-boneyard]').forEach(el => {
            if (el.dataset.boneyardAttached === 'true') return;
            el.dataset.boneyardAttached = 'true';

            const templateKey = el.getAttribute('data-boneyard') || 'landing';
            const contentSelector = el.getAttribute('data-boneyard-content') || '#page-content, #content, main';
            const delay = parseInt(el.getAttribute('data-boneyard-delay') || '450', 10);

            this.attach({
                skeleton: el,
                content: contentSelector,
                templateKey,
                delay
            });
        });
    }
}

export const Boneyard = new BoneyardEngine();

// Attach globally to window for Blade / Alpine access
if (typeof window !== 'undefined') {
    window.Boneyard = Boneyard;
    
    // Auto-init on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Boneyard.init());
    } else {
        Boneyard.init();
    }
}

export default Boneyard;
