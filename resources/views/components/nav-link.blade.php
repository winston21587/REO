@props(['mode' => 'desktop', 'route' => ''])

@php
    // Desktop (Dark Sidebar) Styles
    $desktopBase = "flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group relative";
    $desktopActive = "nav-item active"; // Relies on the CSS in layout
    $desktopInactive = "text-slate-400 hover:bg-white/5 hover:text-white";

    // Mobile (Light Dropdown) Styles
    $mobileBase = "flex items-center gap-4 px-6 py-4 border-l-4 transition-all duration-200 group";
    $mobileActive = "border-[#8B0000] bg-red-50 text-[#8B0000] font-bold";
    $mobileInactive = "border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900";

    $isActive = request()->routeIs($route);

    $finalClasses = $mode === 'desktop'
        ? ($desktopBase . ' ' . ($isActive ? $desktopActive : $desktopInactive))
        : ($mobileBase . ' ' . ($isActive ? $mobileActive : $mobileInactive));
@endphp

<a {{ $attributes->merge(['class' => $finalClasses]) }}>
    {{ $slot }}
</a>