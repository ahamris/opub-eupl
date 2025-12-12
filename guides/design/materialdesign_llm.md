# Material Design 3 + NL Design System Guide for Tailwind CSS

**Complete guide for building accessible, user-friendly UI/UX using Material Design 3 principles, NL Design System guidelines, and Tailwind CSS**

---

## Table of Contents

1. [Overview](#1-overview)
2. [Design Philosophy](#2-design-philosophy)
3. [Color System](#3-color-system)
4. [Typography](#4-typography)
5. [Spacing & Layout](#5-spacing--layout)
6. [Components](#6-components)
7. [Motion & Animation](#7-motion--animation)
8. [Accessibility (WCAG 2.2 AA)](#8-accessibility-wcag-22-aa)
9. [Implementation Patterns](#9-implementation-patterns)
10. [AI Tool Guidelines](#10-ai-tool-guidelines)

---

## 1. Overview

### 1.1 Purpose

This guide combines **Material Design 3 (M3)** design principles with **NL Design System** accessibility guidelines, implemented using **Tailwind CSS**. It provides a complete framework for building accessible, user-friendly, and visually consistent digital services.

### 1.2 Key Standards

- **Material Design 3**: Google's latest design system with dynamic colors, adaptive components, and inclusive design principles
- **NL Design System**: Dutch government design system based on WCAG 2.2 AA (with some AAA criteria)
- **WCAG 2.2 AA**: Legal accessibility requirement in the Netherlands
- **Tailwind CSS v4**: Utility-first CSS framework for rapid UI development

### 1.3 Design Principles

1. **Accessibility First**: All components meet WCAG 2.2 AA standards
2. **User-Centered**: Based on user research and usability testing
3. **Consistent**: Unified design language across all components
4. **Responsive**: Works seamlessly across all device sizes
5. **Inclusive**: Designed for the widest possible audience

---

## 2. Design Philosophy

### 2.1 Material Design 3 Core Principles

#### Dynamic Color
- Colors adapt to user preferences (light/dark mode)
- System-wide color schemes for personalized experiences
- Accessible color contrast ratios

#### Adaptive Components
- Components that adapt to different screen sizes
- Responsive layouts that work on mobile, tablet, and desktop
- Flexible grid systems

#### Inclusive Design
- Accessible to users with disabilities
- Keyboard navigation support
- Screen reader compatibility

### 2.2 NL Design System Principles

#### Accessibility (WCAG 2.2 AA)
- **2.4.13 Focusweergave** (AAA level): Clear, visible focus indicators
- **2.5.5 Grootte van het aanwijsgebied** (AAA level): Minimum 44x44px touch targets
- All Level AA criteria must be met

#### Usability
- Form labels always visible above input fields
- Clear error messages with text descriptions
- Consistent navigation patterns
- Descriptive link text (not "click here")

#### Consistency
- Unified component library
- Consistent spacing and typography
- Predictable interaction patterns

---

## 3. Color System

### 3.1 Material Design 3 Color Tokens

Material Design 3 uses a dynamic color system with semantic color tokens:

```css
/* Tailwind CSS v4 Theme Configuration */
@theme {
  /* Primary Colors (Material Design 3) */
  --color-primary: #6750A4;
  --color-primary-container: #EADDFF;
  --color-on-primary: #FFFFFF;
  --color-on-primary-container: #21005D;
  
  /* Secondary Colors */
  --color-secondary: #625B71;
  --color-secondary-container: #E8DEF8;
  --color-on-secondary: #FFFFFF;
  --color-on-secondary-container: #1D192B;
  
  /* Tertiary Colors */
  --color-tertiary: #7D5260;
  --color-tertiary-container: #FFD8E4;
  --color-on-tertiary: #FFFFFF;
  --color-on-tertiary-container: #31111D;
  
  /* Error Colors */
  --color-error: #BA1A1A;
  --color-error-container: #FFDAD6;
  --color-on-error: #FFFFFF;
  --color-on-error-container: #410002;
  
  /* Surface Colors */
  --color-surface: #FFFBFE;
  --color-surface-variant: #E7E0EC;
  --color-on-surface: #1C1B1F;
  --color-on-surface-variant: #49454F;
  
  /* Outline Colors */
  --color-outline: #79747E;
  --color-outline-variant: #CAC4D0;
  
  /* Shadow Colors */
  --color-shadow: #000000;
  
  /* NL Design System Colors (Dutch Government) */
  --color-nl-blue: #01689B;
  --color-nl-blue-light: #E3F2FD;
  --color-nl-orange: #FF6B35;
  --color-nl-green: #00A651;
  --color-nl-red: #D52B1E;
}
```

### 3.2 Tailwind CSS Implementation

```html
<!-- Primary Button -->
<button class="bg-primary text-on-primary hover:bg-primary/90 
               focus:outline-2 focus:outline-primary focus:outline-offset-2
               px-4 py-2 rounded-full transition-colors">
  Primary Action
</button>

<!-- Secondary Button -->
<button class="bg-secondary-container text-on-secondary-container
               hover:bg-secondary-container/80
               focus:outline-2 focus:outline-secondary focus:outline-offset-2
               px-4 py-2 rounded-full transition-colors">
  Secondary Action
</button>

<!-- Error State -->
<div class="bg-error-container text-on-error-container p-4 rounded-lg">
  <p class="text-error font-medium">Error message</p>
</div>
```

### 3.3 Color Contrast Requirements (WCAG AA)

**Normal Text** (under 18pt / 14pt bold):
- Minimum contrast ratio: **4.5:1**
- Example: `text-on-surface` on `bg-surface`

**Large Text** (18pt+ / 14pt+ bold):
- Minimum contrast ratio: **3:1**
- Example: Headings on colored backgrounds

**Interactive Elements**:
- Minimum contrast ratio: **3:1** for focus indicators
- Example: `focus:outline-2 focus:outline-primary`

### 3.4 Dark Mode Support

```css
@theme {
  @media (prefers-color-scheme: dark) {
    --color-surface: #1C1B1F;
    --color-on-surface: #E6E1E5;
    --color-primary: #D0BCFF;
    --color-primary-container: #4F378B;
  }
}
```

```html
<!-- Automatic dark mode support -->
<div class="bg-surface text-on-surface">
  Content adapts to user's system preference
</div>
```

---

## 4. Typography

### 4.1 Material Design 3 Type Scale

Material Design 3 defines a hierarchical typography system:

| Style | Size | Weight | Line Height | Use Case |
|-------|------|--------|-------------|----------|
| Display Large | 57px | 400 | 64px | Hero sections |
| Display Medium | 45px | 400 | 52px | Large headings |
| Display Small | 36px | 400 | 44px | Section headers |
| Headline Large | 32px | 400 | 40px | Page titles |
| Headline Medium | 28px | 400 | 36px | Section titles |
| Headline Small | 24px | 400 | 32px | Subsection titles |
| Title Large | 22px | 500 | 28px | Card titles |
| Title Medium | 16px | 500 | 24px | List item titles |
| Title Small | 14px | 500 | 20px | Button labels |
| Label Large | 14px | 500 | 20px | Form labels |
| Label Medium | 12px | 500 | 16px | Helper text |
| Label Small | 11px | 500 | 16px | Captions |
| Body Large | 16px | 400 | 24px | Body text |
| Body Medium | 14px | 400 | 20px | Secondary text |
| Body Small | 12px | 400 | 16px | Fine print |

### 4.2 Tailwind CSS Typography Configuration

```css
@theme {
  /* Font Families */
  --font-sans: 'Roboto', 'Noto Sans', ui-sans-serif, system-ui, sans-serif;
  --font-mono: 'Roboto Mono', ui-monospace, monospace;
  
  /* Type Scale */
  --font-size-display-large: 3.5625rem; /* 57px */
  --font-size-display-medium: 2.8125rem; /* 45px */
  --font-size-display-small: 2.25rem; /* 36px */
  --font-size-headline-large: 2rem; /* 32px */
  --font-size-headline-medium: 1.75rem; /* 28px */
  --font-size-headline-small: 1.5rem; /* 24px */
  --font-size-title-large: 1.375rem; /* 22px */
  --font-size-title-medium: 1rem; /* 16px */
  --font-size-title-small: 0.875rem; /* 14px */
  --font-size-label-large: 0.875rem; /* 14px */
  --font-size-label-medium: 0.75rem; /* 12px */
  --font-size-label-small: 0.6875rem; /* 11px */
  --font-size-body-large: 1rem; /* 16px */
  --font-size-body-medium: 0.875rem; /* 14px */
  --font-size-body-small: 0.75rem; /* 12px */
  
  /* Line Heights */
  --line-height-tight: 1.2;
  --line-height-normal: 1.5;
  --line-height-relaxed: 1.75;
}
```

### 4.3 Typography Usage Examples

```html
<!-- Display Styles -->
<h1 class="text-display-large font-normal leading-tight">
  Welcome to Our Platform
</h1>

<!-- Headline Styles -->
<h2 class="text-headline-large font-normal leading-tight">
  Section Title
</h2>

<h3 class="text-headline-medium font-normal leading-tight">
  Subsection Title
</h3>

<!-- Title Styles -->
<h4 class="text-title-large font-medium leading-normal">
  Card Title
</h4>

<!-- Body Text -->
<p class="text-body-large font-normal leading-relaxed">
  This is the main body text. It should be readable and comfortable to read.
</p>

<p class="text-body-medium font-normal leading-normal">
  This is secondary body text, used for less important content.
</p>

<!-- Labels -->
<label class="text-label-large font-medium leading-normal">
  Email Address
</label>

<span class="text-label-medium font-medium leading-normal">
  Helper text or caption
</span>
```

### 4.4 NL Design System Typography Requirements

- **Readable**: Minimum 16px for body text (14px acceptable for secondary text)
- **Hierarchical**: Clear heading structure (h1 → h2 → h3)
- **Contrast**: Text meets WCAG AA contrast requirements
- **Scalable**: Text can be resized up to 200% without breaking layout

---

## 5. Spacing & Layout

### 5.1 Material Design 3 Spacing System

Material Design 3 uses an 8dp (density-independent pixels) grid system:

| Spacing | Value | Use Case |
|---------|-------|----------|
| 4dp | 0.25rem | Tight spacing, icons |
| 8dp | 0.5rem | Standard spacing unit |
| 12dp | 0.75rem | Small gaps |
| 16dp | 1rem | Standard gaps |
| 24dp | 1.5rem | Medium gaps |
| 32dp | 2rem | Large gaps |
| 48dp | 3rem | Section spacing |
| 64dp | 4rem | Page spacing |

### 5.2 Tailwind CSS Spacing Configuration

```css
@theme {
  /* Material Design 3 Spacing Scale */
  --spacing-1: 0.25rem;  /* 4dp */
  --spacing-2: 0.5rem;   /* 8dp */
  --spacing-3: 0.75rem;  /* 12dp */
  --spacing-4: 1rem;     /* 16dp */
  --spacing-6: 1.5rem;   /* 24dp */
  --spacing-8: 2rem;     /* 32dp */
  --spacing-12: 3rem;    /* 48dp */
  --spacing-16: 4rem;    /* 64dp */
}
```

### 5.3 Layout Examples

```html
<!-- Card with Material Design spacing -->
<div class="bg-surface rounded-xl p-6 shadow-sm">
  <h3 class="text-title-large font-medium mb-4">Card Title</h3>
  <p class="text-body-medium mb-6">Card content goes here.</p>
  <div class="flex gap-4">
    <button class="px-4 py-2">Action 1</button>
    <button class="px-4 py-2">Action 2</button>
  </div>
</div>

<!-- Form with proper spacing -->
<form class="space-y-6">
  <div class="space-y-2">
    <label class="block text-label-large font-medium">
      Email Address
    </label>
    <input type="email" 
           class="w-full px-4 py-3 rounded-lg border border-outline 
                  focus:outline-2 focus:outline-primary focus:outline-offset-2">
    <span class="text-label-medium text-on-surface-variant">
      We'll never share your email
    </span>
  </div>
</form>
```

### 5.4 Responsive Grid System

```html
<!-- Material Design 3 Responsive Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <div class="bg-surface rounded-xl p-6">Card 1</div>
  <div class="bg-surface rounded-xl p-6">Card 2</div>
  <div class="bg-surface rounded-xl p-6">Card 3</div>
</div>

<!-- Breakpoints (Material Design 3) -->
<!-- Mobile: < 600px -->
<!-- Tablet: 600px - 840px -->
<!-- Desktop: > 840px -->
```

---

## 6. Components

### 6.1 Buttons

#### Material Design 3 Button Types

**Filled Button** (Primary actions):
```html
<button class="bg-primary text-on-primary 
               hover:bg-primary/90 active:bg-primary/80
               focus:outline-2 focus:outline-primary focus:outline-offset-2
               px-6 py-3 rounded-full font-medium
               transition-colors duration-200
               min-h-[44px] min-w-[44px]">
  Primary Action
</button>
```

**Outlined Button** (Secondary actions):
```html
<button class="border-2 border-outline text-primary
               hover:bg-primary-container
               focus:outline-2 focus:outline-primary focus:outline-offset-2
               px-6 py-3 rounded-full font-medium
               transition-colors duration-200
               min-h-[44px] min-w-[44px]">
  Secondary Action
</button>
```

**Text Button** (Tertiary actions):
```html
<button class="text-primary
               hover:bg-primary-container
               focus:outline-2 focus:outline-primary focus:outline-offset-2
               px-4 py-2 rounded-full font-medium
               transition-colors duration-200
               min-h-[44px] min-w-[44px]">
  Text Action
</button>
```

**Icon Button** (with NL Design System 44x44px minimum):
```html
<button class="text-on-surface-variant
               hover:bg-surface-variant
               focus:outline-2 focus:outline-primary focus:outline-offset-2
               p-3 rounded-full
               transition-colors duration-200
               min-h-[44px] min-w-[44px] flex items-center justify-center">
  <svg class="w-6 h-6" aria-hidden="true">
    <!-- Icon -->
  </svg>
  <span class="sr-only">Close dialog</span>
</button>
```

### 6.2 Form Inputs

#### Text Input (NL Design System: label always visible above)
```html
<div class="space-y-2">
  <label for="email" class="block text-label-large font-medium text-on-surface">
    Email Address
  </label>
  <input type="email" 
         id="email"
         name="email"
         class="w-full px-4 py-3 rounded-lg 
                border-2 border-outline bg-surface
                text-body-large text-on-surface
                focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                transition-colors duration-200
                aria-describedby="email-help">
  <span id="email-help" class="text-label-medium text-on-surface-variant">
    We'll never share your email address
  </span>
</div>
```

#### Input with Error (WCAG: error identified in text)
```html
<div class="space-y-2">
  <label for="email-error" class="block text-label-large font-medium text-on-surface">
    Email Address
  </label>
  <input type="email" 
         id="email-error"
         name="email"
         aria-invalid="true"
         aria-describedby="email-error-message"
         class="w-full px-4 py-3 rounded-lg 
                border-2 border-error bg-surface
                text-body-large text-on-surface
                focus:border-error focus:outline-2 focus:outline-error focus:outline-offset-2
                transition-colors duration-200">
  <span id="email-error-message" class="text-label-medium text-error" role="alert">
    Please enter a valid email address
  </span>
</div>
```

#### Select Dropdown (using Tailwind Plus Elements)
```html
<el-select name="status" value="active" class="w-full">
  <button type="button" 
          class="w-full px-4 py-3 rounded-lg 
                 border-2 border-outline bg-surface
                 text-body-large text-on-surface text-left
                 focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                 transition-colors duration-200
                 min-h-[44px]">
    <el-selectedcontent>Active</el-selectedcontent>
  </button>
  <el-options popover anchor="bottom start" class="w-full bg-surface rounded-lg shadow-lg border border-outline">
    <el-option value="active" class="px-4 py-3 hover:bg-primary-container cursor-pointer">Active</el-option>
    <el-option value="inactive" class="px-4 py-3 hover:bg-primary-container cursor-pointer">Inactive</el-option>
    <el-option value="archived" class="px-4 py-3 hover:bg-primary-container cursor-pointer">Archived</el-option>
  </el-options>
</el-select>
```

### 6.3 Cards

```html
<!-- Material Design 3 Card -->
<div class="bg-surface rounded-xl p-6 shadow-sm 
            hover:shadow-md transition-shadow duration-200
            border border-outline-variant">
  <h3 class="text-title-large font-medium mb-2">Card Title</h3>
  <p class="text-body-medium text-on-surface-variant mb-4">
    Card description or content goes here.
  </p>
  <div class="flex gap-4">
    <button class="text-primary font-medium">Action 1</button>
    <button class="text-primary font-medium">Action 2</button>
  </div>
</div>
```

### 6.4 Navigation

#### Top Navigation Bar
```html
<nav class="bg-surface border-b border-outline-variant" role="navigation" aria-label="Main navigation">
  <div class="max-w-7xl mx-auto px-4 py-4">
    <div class="flex items-center justify-between">
      <a href="/" class="text-headline-small font-normal">Logo</a>
      <ul class="flex gap-6">
        <li><a href="/about" class="text-body-large hover:text-primary transition-colors">About</a></li>
        <li><a href="/contact" class="text-body-large hover:text-primary transition-colors">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>
```

### 6.5 Dialogs (using Tailwind Plus Elements)

```html
<!-- Material Design 3 Dialog -->
<el-dialog>
  <dialog id="confirm-dialog" class="backdrop:bg-black/50">
    <el-dialog-backdrop class="pointer-events-none bg-black/50 transition-opacity duration-200 data-closed:opacity-0" />
    <el-dialog-panel class="bg-surface rounded-2xl p-6 max-w-md mx-auto 
                            transition-all duration-200 
                            data-closed:scale-95 data-closed:opacity-0">
      <h2 class="text-headline-small font-normal mb-4">Confirm Action</h2>
      <p class="text-body-large text-on-surface-variant mb-6">
        Are you sure you want to proceed? This action cannot be undone.
      </p>
      <div class="flex gap-4 justify-end">
        <button command="close" commandfor="confirm-dialog" 
                class="px-6 py-3 rounded-full border-2 border-outline
                       hover:bg-primary-container
                       focus:outline-2 focus:outline-primary focus:outline-offset-2
                       transition-colors min-h-[44px]">
          Cancel
        </button>
        <button type="submit" 
                class="px-6 py-3 rounded-full bg-primary text-on-primary
                       hover:bg-primary/90
                       focus:outline-2 focus:outline-primary focus:outline-offset-2
                       transition-colors min-h-[44px]">
          Confirm
        </button>
      </div>
    </el-dialog-panel>
  </dialog>
</el-dialog>
```

---

## 7. Motion & Animation

### 7.1 Material Design 3 Motion Principles

- **Meaningful**: Animations provide feedback and guide users
- **Purposeful**: Every animation has a clear purpose
- **Natural**: Motion feels natural and follows physics
- **Accessible**: Respects `prefers-reduced-motion`

### 7.2 Animation Timing

Material Design 3 uses standard easing curves:

| Duration | Use Case |
|----------|----------|
| 50ms | Micro-interactions (hover, focus) |
| 150ms | Small state changes |
| 250ms | Standard transitions |
| 300ms | Page transitions |
| 400ms | Complex animations |

### 7.3 Tailwind CSS Animation Examples

```html
<!-- Hover transition -->
<button class="bg-primary text-on-primary
               hover:bg-primary/90 
               transition-colors duration-200">
  Hover me
</button>

<!-- Scale on interaction -->
<button class="bg-primary text-on-primary
               active:scale-95
               transition-transform duration-150">
  Click me
</button>

<!-- Respect reduced motion -->
<div class="transition-all duration-300 
            motion-reduce:transition-none">
  Animated content
</div>
```

### 7.4 Focus Transitions

```html
<!-- Smooth focus ring -->
<button class="focus:outline-2 focus:outline-primary focus:outline-offset-2
               transition-all duration-150">
  Focus me
</button>
```

---

## 8. Accessibility (WCAG 2.2 AA)

### 8.1 Required WCAG Criteria

#### 2.4.13 Focusweergave (AAA - NL Design System requirement)
- **Requirement**: Focus indicators must be clearly visible
- **Implementation**: 2px outline with 2px offset, high contrast color

```html
<button class="focus:outline-2 focus:outline-primary focus:outline-offset-2">
  Accessible button
</button>
```

#### 2.5.5 Grootte van het aanwijsgebied (AAA - NL Design System requirement)
- **Requirement**: Minimum 44x44px touch targets
- **Implementation**: All interactive elements meet minimum size

```html
<button class="min-h-[44px] min-w-[44px]">
  Touch-friendly button
</button>
```

#### 1.4.3 Contrast (Minimum) - Level AA
- **Requirement**: 4.5:1 for normal text, 3:1 for large text
- **Implementation**: Use semantic color tokens

```html
<p class="text-on-surface bg-surface">
  Meets contrast requirements
</p>
```

#### 2.1.1 Keyboard - Level A
- **Requirement**: All functionality accessible via keyboard
- **Implementation**: Use semantic HTML, proper tab order

```html
<button type="button" class="focus:outline-2 focus:outline-primary">
  Keyboard accessible
</button>
```

#### 3.3.1 Foutidentificatie - Level A
- **Requirement**: Errors identified in text
- **Implementation**: Error messages with `role="alert"`

```html
<span id="error" class="text-error" role="alert">
  Error message in text
</span>
```

### 8.2 Accessibility Checklist

- [ ] All images have `alt` text
- [ ] All form fields have `<label>` elements
- [ ] All interactive elements are keyboard accessible
- [ ] Focus indicators are visible (2px outline)
- [ ] Touch targets are minimum 44x44px
- [ ] Color contrast meets WCAG AA (4.5:1 / 3:1)
- [ ] Error messages are in text with `role="alert"`
- [ ] Page language is declared (`<html lang="nl">`)
- [ ] Semantic HTML is used correctly
- [ ] ARIA attributes used when needed

---

## 9. Implementation Patterns

### 9.1 Complete Form Example

```html
<form class="max-w-md mx-auto space-y-6" novalidate>
  <!-- Email Field -->
  <div class="space-y-2">
    <label for="email" class="block text-label-large font-medium text-on-surface">
      Email Address
    </label>
    <input type="email" 
           id="email"
           name="email"
           required
           aria-required="true"
           aria-describedby="email-help email-error"
           class="w-full px-4 py-3 rounded-lg 
                  border-2 border-outline bg-surface
                  text-body-large text-on-surface
                  focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                  transition-colors duration-200
                  invalid:border-error">
    <span id="email-help" class="text-label-medium text-on-surface-variant">
      We'll never share your email
    </span>
    <span id="email-error" class="text-label-medium text-error hidden" role="alert">
      Please enter a valid email address
    </span>
  </div>

  <!-- Submit Button -->
  <button type="submit" 
          class="w-full bg-primary text-on-primary 
                 hover:bg-primary/90 active:bg-primary/80
                 focus:outline-2 focus:outline-primary focus:outline-offset-2
                 px-6 py-3 rounded-full font-medium
                 transition-colors duration-200
                 min-h-[44px]">
    Submit Form
  </button>
</form>
```

### 9.2 Card Grid Layout

```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
  <article class="bg-surface rounded-xl p-6 shadow-sm 
                  hover:shadow-md transition-shadow duration-200
                  border border-outline-variant">
    <h3 class="text-title-large font-medium mb-2">Card Title</h3>
    <p class="text-body-medium text-on-surface-variant mb-4">
      Card description goes here.
    </p>
    <a href="/detail" 
       class="text-primary font-medium inline-flex items-center gap-2
              hover:underline focus:outline-2 focus:outline-primary focus:outline-offset-2
              transition-all duration-200">
      Read more
      <svg class="w-5 h-5" aria-hidden="true">
        <path d="M9 5l7 7-7 7"/>
      </svg>
    </a>
  </article>
  <!-- Repeat for more cards -->
</article>
```

### 9.3 Search Interface

```html
<div class="max-w-2xl mx-auto p-6">
  <h1 class="text-headline-large font-normal mb-6">Search Documents</h1>
  
  <!-- Search Input -->
  <el-autocomplete class="w-full mb-6">
    <input type="search" 
           name="query"
           placeholder="Search..."
           aria-label="Search documents"
           class="w-full px-4 py-3 rounded-lg 
                  border-2 border-outline bg-surface
                  text-body-large text-on-surface
                  focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                  transition-colors duration-200
                  min-h-[44px]">
    <button type="button" 
            aria-label="Search"
            class="absolute right-2 top-1/2 -translate-y-1/2
                   text-on-surface-variant hover:text-primary
                   p-2 rounded-full hover:bg-primary-container
                   focus:outline-2 focus:outline-primary focus:outline-offset-2
                   transition-colors duration-200
                   min-h-[44px] min-w-[44px]">
      <svg class="w-6 h-6" aria-hidden="true">
        <!-- Search icon -->
      </svg>
    </button>
    
    <el-options popover anchor="bottom start" class="w-full bg-surface rounded-lg shadow-lg border border-outline">
      <el-option value="result-1" class="px-4 py-3 hover:bg-primary-container cursor-pointer">
        Search Result 1
      </el-option>
      <el-option value="result-2" class="px-4 py-3 hover:bg-primary-container cursor-pointer">
        Search Result 2
      </el-option>
    </el-options>
  </el-autocomplete>
  
  <!-- Search Results -->
  <div class="space-y-4">
    <!-- Result items -->
  </div>
</div>
```

---

## 10. AI Tool Guidelines

### 10.1 Code Generation Rules

When generating HTML/CSS code with Tailwind CSS, AI tools should:

1. **Always use Material Design 3 color tokens**:
   ```html
   <!-- Good -->
   <button class="bg-primary text-on-primary">Action</button>
   
   <!-- Bad -->
   <button class="bg-blue-500 text-white">Action</button>
   ```

2. **Include accessibility attributes**:
   ```html
   <button class="bg-primary text-on-primary
                  focus:outline-2 focus:outline-primary focus:outline-offset-2
                  min-h-[44px] min-w-[44px]"
           aria-label="Close dialog">
     Close
   </button>
   ```

3. **Use Material Design 3 typography scale**:
   ```html
   <!-- Good -->
   <h1 class="text-headline-large font-normal">Title</h1>
   <p class="text-body-large">Body text</p>
   
   <!-- Bad -->
   <h1 class="text-2xl font-bold">Title</h1>
   <p class="text-sm">Body text</p>
   ```

4. **Follow 8dp spacing grid**:
   ```html
   <!-- Good -->
   <div class="p-6 space-y-4">
   
   <!-- Bad -->
   <div class="p-5 space-y-3">
   ```

5. **Include focus states for all interactive elements**:
   ```html
   <a href="/link" 
      class="text-primary
             focus:outline-2 focus:outline-primary focus:outline-offset-2">
     Link
   </a>
   ```

6. **Ensure minimum touch target size (44x44px)**:
   ```html
   <button class="min-h-[44px] min-w-[44px]">Button</button>
   ```

7. **Use semantic HTML**:
   ```html
   <!-- Good -->
   <nav role="navigation" aria-label="Main">
   <main>
   <article>
   
   <!-- Bad -->
   <div class="nav">
   <div class="main">
   ```

8. **Include transitions for interactive elements**:
   ```html
   <button class="transition-colors duration-200 hover:bg-primary/90">
     Hover me
   </button>
   ```

### 10.2 Component Patterns

#### Button Pattern
```html
<button class="bg-primary text-on-primary 
               hover:bg-primary/90 active:bg-primary/80
               focus:outline-2 focus:outline-primary focus:outline-offset-2
               px-6 py-3 rounded-full font-medium
               transition-colors duration-200
               min-h-[44px] min-w-[44px]">
  Button Text
</button>
```

#### Input Pattern
```html
<div class="space-y-2">
  <label for="field-id" class="block text-label-large font-medium text-on-surface">
    Field Label
  </label>
  <input type="text" 
         id="field-id"
         name="field"
         aria-describedby="field-help field-error"
         class="w-full px-4 py-3 rounded-lg 
                border-2 border-outline bg-surface
                text-body-large text-on-surface
                focus:border-primary focus:outline-2 focus:outline-primary focus:outline-offset-2
                transition-colors duration-200
                min-h-[44px]">
  <span id="field-help" class="text-label-medium text-on-surface-variant">
    Helper text
  </span>
</div>
```

#### Card Pattern
```html
<article class="bg-surface rounded-xl p-6 shadow-sm 
                hover:shadow-md transition-shadow duration-200
                border border-outline-variant">
  <h3 class="text-title-large font-medium mb-2">Card Title</h3>
  <p class="text-body-medium text-on-surface-variant">
    Card content
  </p>
</article>
```

### 10.3 Validation Checklist

Before finalizing code, verify:

- [ ] Uses Material Design 3 color tokens (not arbitrary colors)
- [ ] Uses Material Design 3 typography scale
- [ ] Follows 8dp spacing grid
- [ ] All interactive elements have focus states
- [ ] All touch targets are minimum 44x44px
- [ ] All form fields have labels
- [ ] Error messages use `role="alert"`
- [ ] Semantic HTML is used
- [ ] Transitions are included for interactive elements
- [ ] Color contrast meets WCAG AA requirements
- [ ] Keyboard navigation is supported
- [ ] ARIA attributes used when needed

---

## Quick Reference

### Color Tokens
- `bg-primary` / `text-on-primary` - Primary actions
- `bg-secondary-container` / `text-on-secondary-container` - Secondary actions
- `bg-surface` / `text-on-surface` - Background and text
- `border-outline` - Borders
- `text-error` / `bg-error-container` - Error states

### Typography Classes
- `text-display-large` - Hero text
- `text-headline-large` - Page titles
- `text-title-large` - Card titles
- `text-body-large` - Body text
- `text-label-large` - Form labels

### Spacing
- `p-4` / `px-4` / `py-4` - 16dp spacing
- `gap-4` - 16dp gap
- `space-y-4` - 16dp vertical spacing

### Focus States
- `focus:outline-2 focus:outline-primary focus:outline-offset-2`

### Touch Targets
- `min-h-[44px] min-w-[44px]`

---

## References

- **Material Design 3**: https://m3.material.io/
- **NL Design System**: https://nldesignsystem.nl/richtlijnen/
- **WCAG 2.2**: https://www.w3.org/WAI/WCAG22/quickref/
- **Tailwind CSS v4**: https://tailwindcss.com/
- **Tailwind Plus Elements**: See `guides/tailwind_llm.md`

---

**Last Updated**: Based on Material Design 3, NL Design System guidelines, and WCAG 2.2 AA requirements

**For interactive components**: See `guides/tailwind_llm.md` for Tailwind Plus Elements documentation

