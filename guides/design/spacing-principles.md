# Minimalistic Spacing & Layout Principles

Based on Tailwind UI example patterns - a guide for consistent, clean, modern design.

## Container Pattern

**Standard Container** (use for all main sections):
```html
<div class="mx-auto max-w-7xl px-6 lg:px-8">
```

**Content Container** (for text-heavy content):
```html
<div class="mx-auto max-w-2xl">  <!-- or max-w-4xl for wider -->
```

**Key Rules:**
- Always use `px-6 lg:px-8` for horizontal padding (24px mobile, 32px desktop)
- Never mix different padding values - consistency is key
- Use `mx-auto` to center containers

## Section Spacing Rhythm

**Major Section Spacing:**
```html
<div class="mt-32 sm:mt-56">  <!-- 128px mobile, 224px desktop -->
```

**Internal Section Spacing:**
- Between subsections: `mt-16`, `mt-20`, `mt-24`
- Between content blocks: `mt-6`, `mt-8`, `mt-10`

**Pattern:**
- Hero sections: `pt-14 pb-16 sm:pb-20`
- Feature sections: `mt-32 sm:mt-56`
- Footer: `mt-32 sm:mt-56` (matches section spacing)

## Spacing Between Elements

**Use Gap Utilities (NOT margins):**
```html
<!-- Flex containers -->
<div class="flex gap-x-6 gap-y-10">

<!-- Grid containers -->
<div class="grid grid-cols-3 gap-x-8 gap-y-10 lg:gap-x-8 lg:gap-y-16">
```

**List Spacing:**
```html
<ul class="space-y-3">  <!-- or space-y-4 for larger lists -->
```

**Never use margins between grid/flex items** - always use gap utilities.

## Typography Spacing

**Line Heights (use slash notation):**
- `text-sm/6` - Small text, 1.5 line-height
- `text-base/7` - Base text, 1.75 line-height
- `text-lg/8` - Large text, 2 line-height
- `text-xl/8` - Extra large, 2 line-height

**Heading Spacing:**
```html
<h2 class="text-4xl font-semibold">Title</h2>
<p class="mt-6 text-lg/8 text-gray-600">Description</p>
```

**Common patterns:**
- Heading + paragraph: `mt-2` or `mt-6`
- Section title + content: `mt-8` or `mt-10`
- List items: `space-y-3` or `space-y-4`

## Typography Hierarchy

**Headings:**
```html
<h1 class="text-5xl font-semibold tracking-tight text-balance sm:text-7xl">
<h2 class="text-4xl font-semibold tracking-tight text-pretty sm:text-5xl">
<h3 class="text-base/7 font-semibold">
```

**Body Text:**
```html
<p class="text-lg/8 text-gray-600">  <!-- Primary -->
<p class="text-base/7 text-gray-600">  <!-- Secondary -->
```

## Minimal Design Elements

**Borders (subtle):**
```html
<div class="ring-1 ring-gray-900/10">
<!-- or -->
<div class="border-t border-gray-200">
```

**Shadows (minimal use):**
```html
<div class="shadow-xs">  <!-- Subtle -->
<div class="shadow-2xl">  <!-- For emphasis -->
```

**Backgrounds:**
- Use opacity for subtle backgrounds: `bg-white/60`, `bg-gray-900/20`
- Use subtle gradients sparingly

## Padding Consistency

**Buttons:**
```html
<a class="px-3.5 py-2.5 text-sm font-semibold">
```

**Cards:**
```html
<div class="p-8">  <!-- or p-6 for smaller cards -->
```

**Sections:**
```html
<div class="py-16">  <!-- or py-24, py-32 for major sections -->
```

## Component Patterns

**Feature List Items:**
```html
<div class="relative pl-9">
  <dt class="inline font-semibold text-gray-900">
    <svg class="absolute top-1 left-1 size-5">...</svg>
    Title
  </dt>
  <dd class="inline">Description</dd>
</div>
```

**FAQ Items:**
```html
<div class="py-6 first:pt-0 last:pb-0">
  <!-- Content -->
</div>
```

**Grid Items:**
```html
<div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 lg:gap-x-8 lg:gap-y-16">
```

## Responsive Patterns

**Consistent Breakpoints:**
- Mobile: Base classes (e.g., `px-6`, `mt-32`)
- Tablet: `sm:` prefix (e.g., `sm:mt-56`, `sm:px-8`)
- Desktop: `lg:` prefix (e.g., `lg:px-8`, `lg:grid-cols-3`)

**Spacing Scale:**
- Small: `gap-3`, `space-y-3`, `mt-6`
- Medium: `gap-6`, `space-y-4`, `mt-8`, `mt-10`
- Large: `gap-8`, `gap-10`, `gap-12`, `mt-16`, `mt-20`, `mt-24`
- Extra Large: `mt-32`, `mt-56`

## Summary Checklist

✅ Always use `px-6 lg:px-8` for containers  
✅ Use `mt-32 sm:mt-56` for major sections  
✅ Use `gap-*` utilities, never margins between grid/flex items  
✅ Use `space-y-*` for lists  
✅ Use slash notation for line heights (`text-base/7`)  
✅ Keep borders/shadows minimal (`ring-1`, `shadow-xs`)  
✅ Maintain consistent vertical rhythm  
✅ Use opacity for subtle backgrounds  
✅ Match footer spacing to section spacing  
