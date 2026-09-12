---
name: REO Research Ethics Portal
description: High-density institutional research ethics oversight ledger and administrative portal for Western Mindanao State University
colors:
  primary: "#8B0000"
  primary-hover: "#6d0000"
  secondary: "#B22222"
  surface-canvas: "#faf8f8"
  surface-card: "#ffffff"
  surface-muted: "#f8fafc"
  surface-subtle: "#f1f5f9"
  surface-dark: "#1a0505"
  text-primary: "#0f172a"
  text-secondary: "#475569"
  text-muted: "#94a3b8"
  border-subtle: "#e2e8f0"
  border-card: "#e2e8f0"
  status-triage: "#d97706"
  status-assigned: "#2563eb"
  status-under-review: "#4f46e5"
  status-approved: "#059669"
  status-action-required: "#e11d48"
  doc-pdf: "#e11d48"
  doc-word: "#2563eb"
  doc-excel: "#059669"
  doc-powerpoint: "#d97706"
typography:
  display:
    fontFamily: "Montserrat, sans-serif"
    fontSize: "clamp(2rem, 4vw, 2.75rem)"
    fontWeight: 800
    lineHeight: 1.15
    letterSpacing: "-0.025em"
  headline:
    fontFamily: "Montserrat, sans-serif"
    fontSize: "clamp(1.5rem, 2.5vw, 1.875rem)"
    fontWeight: 700
    lineHeight: 1.25
    letterSpacing: "-0.02em"
  title:
    fontFamily: "Montserrat, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 600
    lineHeight: 1.4
    letterSpacing: "-0.01em"
  body:
    fontFamily: "Inter, system-ui, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: "normal"
  label:
    fontFamily: "Inter, system-ui, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 700
    lineHeight: 1.4
    letterSpacing: "0.05em"
rounded:
  xs: "4px"
  sm: "6px"
  md: "8px"
  lg: "12px"
  xl: "16px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "12px"
  lg: "16px"
  xl: "24px"
  2xl: "32px"
  3xl: "48px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.surface-card}"
    rounded: "{rounded.lg}"
    padding: "10px 20px"
    height: "44px"
  button-primary-hover:
    backgroundColor: "{colors.primary-hover}"
  button-secondary:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.text-primary}"
    rounded: "{rounded.lg}"
    padding: "10px 16px"
    height: "44px"
  input-field:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.text-primary}"
    rounded: "{rounded.lg}"
    padding: "10px 16px"
    height: "44px"
  card-container:
    backgroundColor: "{colors.surface-card}"
    rounded: "{rounded.xl}"
    padding: "24px"
---

# Design System: REO Research Ethics Portal

## Overview

**Creative North Star: "The Academic Oversight Ledger"**

The REO design system reflects the statutory gravity, precision, and dignity of an institutional research ethics review board. Designed as an operational cockpit for academic administrators, committee secretaries, and ethics evaluators, the visual environment balances high information density with uncompromised clarity. The interface rejects generic SaaS tropes: no pastel purple gradients, no floating card-in-card nesting, and no frivolous decorative flourishes.

The atmosphere is rooted in scholarly permanence. A deep obsidian crimson sidebar anchors the navigation, establishing institutional authority while receding behind the primary workspace. Content rests upon an off-white parchment canvas that eliminates harsh glare during marathon review sessions. Every visual element serves an audit purpose: data tables prioritize rapid scannability, status indicators employ high-contrast bold uppercase typography without cluttering backgrounds, and primary actions are reserved for definitive workflow transitions.

**Key Characteristics:**
- Institutional authority through curated WMSU Crimson accents and an obsidian sidebar anchor.
- Dense, highly scannable ledger typography pairing geometric Montserrat titles with neutral Inter data tables.
- High-contrast semantic typography for status cues with zero background pill fills and zero decorative dots.
- Hairline architectural boundaries separating data clusters without nested cards or claustrophobic padding.
- Touch-conscious ergonomic targets respecting mobile review workflows alongside dense desktop data views.

## Target Redesign Specification

Established via `design-taste-frontend` (`taste-skill`) and institutional product requirements:

- **Aesthetic Archetype:** **Technical / Swiss Modernism 2.0 / Academic Oversight Ledger**
  - Crisp grid lines, unadorned surfaces, high information density, stark semantic text hierarchy, and institutional permanence.

- **Core Design Dials:**
  - **`DESIGN_VARIANCE: Low` (3/10):** Rigid tabular grids, unified vertical alignment, structured single-pane tables, predictable layout patterns across all administrative modules.
  - **`VISUAL_DENSITY: Compact` (8/10):** Tight 4px/8px micro-spacing, compact table rows (padding 10px-12px), condensed metadata badges, minimal vertical wasted space.
  - **`MOTION_INTENSITY: Subtle` (2/10):** Zero physics-based bouncing or decorative micro-loops. Restrained 150ms opacity crossfades, 200ms slide-over panel drawers, and tactile `active:scale-[0.98]` button clicks.

- **Typography Pairing:**
  - Headings & Page Titles: Montserrat (Google Fonts, weights 600, 700, 800)
  - Data Records, Tables & Ledger Numerals: Inter (Google Fonts, weights 400, 500, 600, tabular-nums)

- **Cohesive Palette Tokens:**
  - Base Canvas: Ledger Parchment (`#faf8f8`)
  - Navigation Anchor: Obsidian Crimson (`#1a0505`)
  - University Authority: WMSU Crimson (`#8B0000`)
  - Subtle Borders: Hairline Slate (`#e2e8f0`, opacity 70% to 80%)
  - Semantic Status Text: Bold uppercase tracking typography without background pills (`text-emerald-600`, `text-amber-600`, `text-blue-600`, `text-indigo-600`, `text-rose-600`)

### Page & Surface Overrides

1. **Initial Intake / Recent Submissions (`initial-intake`):**
   - **`VISUAL_DENSITY: Compact`**, **`DESIGN_VARIANCE: Low`**, **`MOTION_INTENSITY: Subtle`**
   - Statuses: Plain English (`Triage`, `Approved`, `Action Required`), zero background fills, zero status dots.
   - High-contrast typography: `text-xs font-bold uppercase tracking-wider`.

2. **Active Protocols, Certifications, Revisions & Analytics (`admin-records`):**
   - **`VISUAL_DENSITY: Compact`**, **`DESIGN_VARIANCE: Low`**, **`MOTION_INTENSITY: Subtle`**
   - Dense single-pane ledger on desktop, responsive card transformation below 1024px, slide-over drawer filters.

3. **Document Viewer (`view-files`):**
   - **`VISUAL_DENSITY: Balanced`**, **`DESIGN_VARIANCE: Balanced`**, **`MOTION_INTENSITY: Subtle`**
   - Two-column ledger layout: tactile document list on left with color-coded format badges (PDF Rose `#e11d48`, Word Sapphire `#2563eb`, Excel Emerald `#059669`, PowerPoint Amber `#d97706`) and active WMSU Crimson selection stroke; responsive document preview iframe/canvas on right.

4. **Public Welcome & Login (`portal-auth`):**
   - **`VISUAL_DENSITY: Spacious`**, **`DESIGN_VARIANCE: Balanced`**, **`MOTION_INTENSITY: Subtle`**
   - Centered academic crest, clean parchment card, high-contrast form controls.

## Colors

The palette balances formal academic dignity with high-visibility audit feedback across all protocol states.

### Primary
- **WMSU Crimson** (#8B0000): The sovereign university brand accent. Used selectively for key brand headers, primary call-to-action buttons, active focus rings, and critical notification counter pills.
- **Deep Crimson Hover** (#6d0000): Darkened interactive state for primary crimson buttons and active selections.

### Secondary
- **Firebrick Accent** (#B22222): Complementary crimson used in subtle brand gradients, sidebar active boundary indicators, and high-priority alerts.

### Neutral
- **Ledger Parchment Canvas** (#faf8f8): Warm neutral canvas background that eliminates monitor glare and provides a gentle paper-like substrate.
- **Surface White** (#ffffff): Clean resting background for top-level cards, data table wrappers, and flyout drawer sheets.
- **Surface Muted** (#f8fafc): Subtle off-surface tint for table headers, drawer summaries, and disabled backgrounds.
- **Surface Slate** (#f1f5f9): Border fills, divider tracks, and scrollbar rails.
- **Obsidian Crimson** (#1a0505): Ultra-deep charcoal with warm crimson undertones, providing maximum contrast for the desktop and mobile navigation sidebars.
- **Text Slate 900** (#0f172a): Deepest neutral for protocol titles, modal headings, and primary tabular values.
- **Text Slate 600** (#475569): Subordinate neutral for researcher metadata, table headers, and form labels.
- **Text Slate 400** (#94a3b8): Tertiary neutral for protocol IDs, timestamp indicators, and search placeholder text.
- **Border Subtle** (#e2e8f0): Hairline divider stroke (opacity 70% to 80%) framing cards, rows, and modal dialogs.

### Status Semantic Typography Colors (No Backgrounds, No Dots)
- **Triage / Initial Intake**: Amber 600 (`#d97706`)
- **Reviewer Assigned**: Blue 600 (`#2563eb`)
- **Under Review**: Indigo 600 (`#4f46e5`)
- **Approved / Intake Completed / Active**: Emerald 600 (`#059669`)
- **Action Required / Deficiencies**: Rose 600 (`#e11d48`)

### Document Ledger Format Palette
- **PDF Documents**: Rose (`#e11d48`)
- **Word Documents**: Sapphire Blue (`#2563eb`)
- **Excel Spreadsheets**: Emerald Green (`#059669`)
- **PowerPoint Presentations**: Amber (`#d97706`)

### Named Rules
**The Academic Authority Rule.** Crimson (#8B0000) is reserved strictly for primary brand signposts, active navigation indicators, primary submit CTAs, and counter badges. It must occupy no more than 5 percent of any given viewport. Its rarity preserves its authority.

**The High-Contrast Typography Rule.** All table and dashboard protocol statuses must use bold, uppercase tracking typography (`text-xs font-bold uppercase tracking-wider`) with zero colored background fills and zero decorative dots.

**The Plain English Workflow Rule.** Always use standard Plain English terminology: `Triage`, `Reviewer Assigned`, `Under Review`, `Approved`, `Action Required`, and `Waiting`. Never use confusing bureaucratic abbreviations or opaque acronyms.

## Typography

**Display Font:** Montserrat (with system-ui fallback)
**Body Font:** Inter (with system-ui fallback)
**Label/Mono Font:** System Monospace / Inter Tabular Numbers

**Character:** The pairing couples Montserrat's decisive architectural geometry for structural headings with Inter's neutral, hyper-legible horizontal rhythm for dense academic registries.

### Hierarchy
- **Display** (800 Extrabold, clamp(2rem, 4vw, 2.75rem), line-height 1.15): Primary portal hero statements and major welcome gates.
- **Headline** (700 Bold, clamp(1.5rem, 2.5vw, 1.875rem), line-height 1.25): Top-level page headers such as Active Protocols and administrative section anchors.
- **Title** (600 Semibold, 1.125rem (18px), line-height 1.4): Modal dialog headers, slide-over drawer titles, and major card grouping labels.
- **Body** (400 Regular / 500 Medium, 0.875rem (14px), line-height 1.5): Standard protocol titles, table rows, researcher metadata, and descriptive instruction copy.
- **Label** (700 Bold, 0.75rem (12px), tracking 0.05em, uppercase): Status text labels, table column headers, and protocol code tags (#00010).

### Named Rules
**The Zero-Slop Punctuation Rule.** Never use em-dashes or en-dashes anywhere in administrative prose, table values, empty states, or modal descriptions. Use colons, parentheses, commas, or explicit sentences instead.

**The Tabular Alignment Rule.** All numerical identifiers, monetary receipts, dates, and protocol codes must use tabular figures (`tabular-nums`) to prevent optical jitter when scanning vertical columns.

## Layout

The spatial model uses a two-tier architectural shell: a fixed 288px (w-72) obsidian sidebar on the left, and an elastic primary content stage bounded at 1280px (max-w-7xl) centered on the canvas.

On desktop viewports (1024px and above), tabular data is presented in a dense, single-pane data ledger with horizontal scanning ergonomics. On tablet and mobile viewports (below 1024px), the table transforms into an adaptive card list that maintains full metadata visibility while stack-aligning details without horizontal scrollbars.

Spacing adheres strictly to a 4px modular scale:
- 4px (xs): Micro-gaps between label elements.
- 8px (sm): Spacing between form labels and input borders.
- 12px (md): Form control vertical padding and table cell padding.
- 16px (lg): Standard component gap, modal internal spacing, and mobile gutter.
- 24px (xl): Card padding, section margins, and desktop grid gap.
- 32px (2xl): Page header separation and drawer content padding.
- 48px (3xl): Major section dividers on public landing views.

Breakpoints:
- Mobile: below 768px (single-column cards, full-width slide-over drawer, fixed bottom action targets).
- Tablet: 768px to 1023px (two-column responsive cards, top navigation bar, collapsible filter drawer).
- Desktop: 1024px to 1279px (full table view, fixed sidebar, contextual slide-over drawers).
- Wide Desktop: 1280px and above (max-w-7xl centered ledger container).

## Elevation & Depth

The design system relies on tonal layering, surface contrast, and hairline border strokes rather than heavy shadows. The UI remains flat and tactile at rest, reserving elevated z-index planes strictly for active overlays.

### Shadow Vocabulary
- **shadow-2xs** (`0 1px 2px 0 rgba(0, 0, 0, 0.03)`): Resting state for search inputs, filter buttons, and secondary controls.
- **shadow-xs** (`0 1px 2px 0 rgba(0, 0, 0, 0.05)`): Subtle grounding stroke for top-level white cards and protocol ledger shells.
- **shadow-sm** (`0 1px 3px 0 rgba(0, 0, 0, 0.1)`): Interactive hover elevation on clickable protocol triggers and dropdown menus.
- **shadow-md** (`0 4px 6px -1px rgba(0, 0, 0, 0.1)`): Floating avatar badges, action tooltips, and elevated control pills.
- **shadow-2xl** (`0 25px 50px -12px rgba(0, 0, 0, 0.25)`): Deep separation for slide-over side sheets and centered modal dialogs over dimmed backdrops.

### Named Rules
**The Flat-By-Default Rule.** Surfaces remain entirely flat at rest. Elevation increases only as a direct response to user interaction (hover, focus) or spatial promotion (drawer, modal).

**The Frosted Scrim Rule.** All modal dialogs and slide-over drawers must project over a dimmed backdrop (`bg-slate-900/40`) paired with hardware-accelerated blur (`backdrop-blur-sm`) to isolate the user focus.

## Shapes

Shapes communicate functional hierarchy through disciplined border radii and consistent perimeter strokes.

- **4px radius (rounded)**: Micro tags, code tags, and small format indicators.
- **8px radius (rounded-lg)**: Sub-action buttons, reviewer profile avatars, and inner field wrappers.
- **12px radius (rounded-xl)**: Default interactive primitive. Used for text inputs, action buttons, filter triggers, and ellipsis buttons.
- **16px radius (rounded-2xl)**: Primary structural container. Used for data table shells, content card wrappers, and modal windows.
- **9999px radius (rounded-full)**: Notification counter badges and circular action toggles.

Perimeter strokes use a standardized hairline treatment of 1px solid slate border (`border-slate-200/80`), creating structural definition without heavy dark outlines.

## Components

### Buttons
Interactive controls designed with tactile clarity, 12px corner radii, and ergonomic hit targets.

- **Shape:** 12px corner radius (rounded-xl) with a minimum height of 40px to 44px.
- **Primary:** Background WMSU Crimson (#8B0000), text white, semibold 14px font. Hover transition to deep crimson (#6d0000) with subtle active scale down (active:scale-[0.98]).
- **Secondary / Filter:** Background white, text slate-700, 1px border (#e2e8f0), subtle shadow-2xs. Hover transition to slate-50.
- **Action Trigger (Ellipsis):** 38px by 38px touch target with 12px radius. Text slate-500, hover text crimson (#8B0000), hover background slate-100.
- **Focus State:** Explicit 2px focus ring in brand crimson (`focus:ring-2 focus:ring-[#8B0000] focus:outline-none`).

### Status Labels (High-Contrast Typography)
Status cues designed for immediate scannability without visual clutter.

- **Presentation:** Plain uppercase bold tracking text (`text-xs font-bold uppercase tracking-wider`). Zero colored background pills and zero decorative status dots.
- **Triage:** `text-amber-600`
- **Reviewer Assigned:** `text-blue-600`
- **Under Review:** `text-indigo-600`
- **Approved / Active:** `text-emerald-600`
- **Action Required:** `text-rose-600`

### Document Format Badges (File Viewer)
Distinctive tactile tiles identifying file types during document review.

- **PDF:** Soft rose badge (`bg-rose-50 text-rose-700 border-rose-200/60`) with dedicated PDF icon.
- **Word:** Soft blue badge (`bg-blue-50 text-blue-700 border-blue-200/60`) with Word document icon.
- **Excel:** Soft emerald badge (`bg-emerald-50 text-emerald-700 border-emerald-200/60`) with spreadsheet icon.
- **PowerPoint:** Soft amber badge (`bg-amber-50 text-amber-700 border-amber-200/60`) with presentation icon.
- **Active Document Selection:** Border-l-4 in WMSU Crimson (`border-l-[#8B0000]`) with elevated white canvas and subtle shadow-xs.

### Cards / Containers
Structural wrappers that organize dense datasets without visual fatigue.

- **Corner Style:** 16px corner radius (rounded-2xl).
- **Background:** Crisp surface white (#ffffff).
- **Shadow Strategy:** Resting shadow-xs with fine perimeter stroke (`border-slate-200/80`).
- **Internal Padding:** 16px on mobile viewports, scaling to 24px on desktop viewports.
- **Card-in-Card Prohibition:** Internal partitions use hairline dividers (`border-slate-100`) or subtle tinted surfaces (`bg-slate-50/50`) rather than nested cards.

### Inputs / Fields
Precision data entry controls with distinct focus indicators.

- **Style:** Background white, 1px border (#e2e8f0), 12px radius (rounded-xl), height 40px to 44px, text 14px slate-800.
- **Search Variant:** Integrated 14px search icon positioned 12px from the left boundary, with 36px to 40px left padding.
- **Focus:** 2px solid ring in WMSU Crimson (`focus:ring-2 focus:ring-[#8B0000] focus:border-transparent focus:outline-none`).

### Navigation
Vertical administrative sidebar engineered for long working sessions.

- **Desktop Sidebar:** Width 288px (w-72), background obsidian crimson (#1a0505), text slate-300.
- **Active Navigation Item:** Background crimson tint (`rgba(139, 0, 0, 0.35)`), text white, bold weight, with an inset 4px red highlight bar (`box-shadow: inset 4px 0 0 0 #dc2626`).
- **Notification Badge:** Circular pill with crimson background (#8B0000), white bold 11px text, and shadow-xs.

### Slide-over Drawer
Contextual sliding sheet for reviewing protocol actions, filters, and evaluator assignments.

- **Dimensions:** Width 100vw, max-width 320px (filter drawer) to 480px (protocol action drawer).
- **Elevation:** High elevation with deep shadow-2xl over frosted scrim.
- **Transitions:** Hardware-accelerated 300ms ease-in-out slide from right edge.

## Do's and Don'ts

### Do:
- **Do** maintain a minimum 38px bounding box (touch-manipulation) on all interactive table triggers, pagination controls, and buttons.
- **Do** render all status labels in high-contrast bold uppercase typography (`text-xs font-bold uppercase tracking-wider`) without background pill containers or dots.
- **Do** use standard Plain English terminology (`Triage`, `Reviewer Assigned`, `Under Review`, `Approved`, `Action Required`, `Waiting`).
- **Do** format all dates as "M d, Y" (for example: "Oct 24, 2026") and all protocol IDs with leading zeros (for example: "#00010").
- **Do** preserve the warm off-white canvas (#faf8f8) to prevent eye strain during extended administrative sessions.
- **Do** use hairline borders (slate-200/80 or slate-100) to segment internal card sections instead of nesting boxes.
- **Do** ensure all form controls feature the prominent crimson focus ring (`focus:ring-2 focus:ring-[#8B0000]`).

### Don't:
- **Don't** use em-dashes or en-dashes anywhere in administrative copy, table labels, tooltips, or empty states.
- **Don't** add colored background pill fills or circular dots to status indicators in tables or dashboards.
- **Don't** use confusing bureaucratic terms like "Pending Deficiencies" or opaque acronyms.
- **Don't** flood large surface backgrounds with saturated red or crimson. Crimson is an intentional punctuation mark, not a wallpaper.
- **Don't** nest a rounded-2xl card inside another rounded-2xl card.
- **Don't** allow table rows to overflow horizontally without clear column priority and responsive card adaptations below 1024px.
- **Don't** use generic centered hero landing patterns or AI purple-to-blue gradients anywhere in the portal.
- **Don't** rely on default browser alerts; use SweetAlert2 or customized modal dialogs adhering to the design system tokens.
