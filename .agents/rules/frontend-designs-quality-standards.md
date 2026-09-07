---
trigger: always_on
---

# Frontend Design & Quality Standards

When implementing, modifying, or refactoring UI components and pages, adhere strictly to the following 4-stage development pipeline:

## Stage 1: Design Tokens & System Alignment
- Reference project tokens established via `ui-ux-pro-max` (colors, typography pairings, spacing scale).
- Never introduce hardcoded hex colors, arbitrary border radii, or unapproved fonts. Ensure semantic variables and dark-mode tokens are respected.

## Stage 2: Composition & Creative Direction
- Apply `taste-skill` principles to prevent generic AI UI patterns.
- Prioritize clear visual hierarchy, deliberate spacing, varied layout structures, and restrained motion.
- Avoid nesting cards inside cards and cookie-cutter centered hero sections.

## Stage 3: Automated Quality & Anti-Pattern Audit
- Run `impeccable` audits on created or modified UI files.
- Ensure the code complies with the 59 deterministic detector rules (proper contrast ratios, tinted neutrals, responsive text wrapping, accessible interactive targets).
- If violations are found, resolve them before proceeding.

## Stage 4: Real Browser & Responsive Verification
- For new surfaces or significant layout refactors, invoke `playwright-skill`.
- Capture screenshots across mobile (375px), tablet (768px), and desktop (1280px+) viewports.
- Confirm there is no horizontal clipping, overlapping text, or broken layout behavior before declaring the task complete.