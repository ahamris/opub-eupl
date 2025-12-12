# WCAG (Web Content Accessibility Guidelines) Reference Guide

**Complete reference guide for implementing WCAG 2.1 AA and WCAG 2.2 accessibility standards**

---

## Table of Contents

1. [Overview](#1-overview)
2. [WCAG Standards & Legal Requirements](#2-wcag-standards--legal-requirements)
3. [The Four Principles](#3-the-four-principles)
4. [Principle 1: Perceivable (Waarneembaar)](#4-principle-1-perceivable-waarneembaar)
5. [Principle 2: Operable (Bedienbaar)](#5-principle-2-operable-bedienbaar)
6. [Principle 3: Understandable (Begrijpelijk)](#6-principle-3-understandable-begrijpelijk)
7. [Principle 4: Robust (Robuust)](#7-principle-4-robust-robuust)
8. [Implementation Guidelines](#8-implementation-guidelines)
9. [Conformance Levels](#9-conformance-levels)
10. [AI Tool Guidelines](#10-ai-tool-guidelines)

---

## 1. Overview

### 1.1 Purpose

This guide documents the **Web Content Accessibility Guidelines (WCAG)** standards for making web content accessible to the widest possible audience. WCAG is developed by the W3C (World Wide Web Consortium) and provides a comprehensive framework for digital accessibility.

### 1.2 Key Information

- **WCAG 2.1 AA**: Current legal requirement in the Netherlands (Wet digitale overheid)
- **WCAG 2.2**: Published October 5, 2023 (recommendation, not yet legal requirement)
- **Legal Basis**: EN 301 549 (European standard) includes WCAG 2.1 AA as of September 7, 2018
- **Structure**: 4 principles → 13 guidelines → testable success criteria → 3 conformance levels (A, AA, AAA)

### 1.3 Target Audience

This guide is designed for:
- **Developers** implementing accessible web applications
- **Designers** creating accessible user interfaces
- **AI tools** (Cursor, Copilot, etc.) generating accessible code
- **QA teams** testing for accessibility compliance

---

## 2. WCAG Standards & Legal Requirements

### 2.1 WCAG 2.1 AA (Current Legal Standard)

- **Status**: Legal requirement in the Netherlands
- **Included in**: EN 301 549 (European standard)
- **Effective date**: September 7, 2018
- **Applies to**: Public sector websites and applications (Wet digitale overheid)

### 2.2 WCAG 2.2 (Latest Recommendation)

- **Status**: W3C recommendation (not yet legal requirement)
- **Published**: October 5, 2023
- **Focus**: Additional success criteria for mobile accessibility, low vision users, and cognitive disabilities
- **Recommendation**: Implement WCAG 2.2 criteria alongside 2.1 AA for future-proofing

### 2.3 Why WCAG Matters

WCAG focuses on **principles rather than technology**, emphasizing the need to consider different ways people interact with web content:

- Users who only use a keyboard (no mouse)
- Users with screen readers (text-to-speech or braille displays)
- Users who modify browser settings for readability
- Users with various disabilities (visual, auditory, motor, cognitive)

---

## 3. The Four Principles

WCAG is built on **four foundational principles** that form the basis of accessible web design:

1. **Perceivable** (Waarneembaar) - Information must be presentable to users in ways they can perceive
2. **Operable** (Bedienbaar) - Interface components must be operable by all users
3. **Understandable** (Begrijpelijk) - Information and UI operation must be understandable
4. **Robust** (Robuust) - Content must be robust enough for various assistive technologies

Each principle contains **guidelines**, which are broken down into **testable success criteria** organized into three **conformance levels**: A (minimum), AA (standard), and AAA (enhanced).

---

## 4. Principle 1: Perceivable (Waarneembaar)

**Goal**: Ensure users can see and hear content, even if they cannot see or hear everything.

### 4.1 Key Requirements

#### Text Alternatives for Images
- **Requirement**: Provide appropriate text alternatives (alt text) for all images
- **Implementation**:
  ```html
  <!-- Good -->
  <img src="chart.png" alt="Sales increased 25% in Q3 2024">
  
  <!-- Decorative images -->
  <img src="decoration.png" alt="">
  ```

#### Video Captions
- **Requirement**: Provide captions for videos for deaf and hard-of-hearing users
- **Implementation**:
  ```html
  <video>
    <source src="video.mp4" type="video/mp4">
    <track kind="captions" src="captions.vtt" srclang="nl" label="Dutch">
  </video>
  ```

#### Color Contrast
- **Requirement**: Use sufficient color contrast between text and background
- **WCAG AA Standard**:
  - Normal text (under 18pt): **4.5:1** contrast ratio
  - Large text (18pt+ or 14pt+ bold): **3:1** contrast ratio
- **Tools**: Use contrast checkers (WebAIM Contrast Checker, axe DevTools)

#### Scalable Content
- **Requirement**: Content must be resizable up to 200% without loss of functionality
- **Implementation**:
  ```css
  /* Use relative units */
  font-size: 1rem; /* Not 12px */
  width: 100%; /* Not 800px */
  
  /* Avoid fixed sizes */
  min-height: 2.5rem; /* Not 40px */
  ```

### 4.2 Success Criteria Examples

- **1.1.1 Non-text Content** (Level A): All images have alt text
- **1.3.1 Info and Relationships** (Level A): Structure is programmatically determinable
- **1.4.3 Contrast (Minimum)** (Level AA): Text meets contrast requirements
- **1.4.4 Resize Text** (Level AA): Text can be resized up to 200%

---

## 5. Principle 2: Operable (Bedienbaar)

**Goal**: Make it possible for users to operate the interface using keyboard or other assistive devices, not just a mouse.

### 5.1 Key Requirements

#### Keyboard Accessibility
- **Requirement**: All functionality must be accessible via keyboard
- **Implementation**:
  ```html
  <!-- Interactive elements must be keyboard accessible -->
  <button onclick="submit()">Submit</button>
  
  <!-- Custom controls need keyboard support -->
  <div role="button" tabindex="0" onkeydown="handleKeyPress(event)">
    Custom Button
  </div>
  ```

#### Focus Indicators
- **Requirement**: Provide visible keyboard focus indicators
- **Implementation**:
  ```css
  /* Visible focus styles */
  *:focus {
    outline: 2px solid #0066cc;
    outline-offset: 2px;
  }
  
  /* Never remove focus outline */
  *:focus {
    outline: none; /* ❌ BAD */
  }
  ```

#### Page Titles
- **Requirement**: Web pages must have descriptive titles
- **Implementation**:
  ```html
  <head>
    <title>Contact - Company Name</title>
  </head>
  ```

#### Descriptive Link Text
- **Requirement**: Use descriptive link text (not "click here" or "read more")
- **Implementation**:
  ```html
  <!-- Good -->
  <a href="/about">Learn more about our company</a>
  
  <!-- Bad -->
  <a href="/about">Click here</a>
  ```

#### No Seizure-Inducing Content
- **Requirement**: Do not include content that flashes more than 3 times per second
- **Implementation**: Avoid rapid animations or flashing effects

### 5.2 Success Criteria Examples

- **2.1.1 Keyboard** (Level A): All functionality available via keyboard
- **2.1.2 No Keyboard Trap** (Level A): Users can navigate away from all components
- **2.4.2 Page Titled** (Level A): Pages have descriptive titles
- **2.4.4 Link Purpose** (Level A): Link purpose is clear from link text
- **2.4.7 Focus Visible** (Level AA): Keyboard focus is visible

---

## 6. Principle 3: Understandable (Begrijpelijk)

**Goal**: Ensure information and UI operation are understandable to users and assistive technologies.

### 6.1 Key Requirements

#### Language Declaration
- **Requirement**: Declare the language of the web page in code
- **Implementation**:
  ```html
  <html lang="nl">
    <head>
      <title>Nederlandse Website</title>
    </head>
  </html>
  
  <!-- For content in different languages -->
  <p>This is English text.</p>
  <p lang="nl">Dit is Nederlandse tekst.</p>
  ```

#### Consistent Navigation
- **Requirement**: Maintain consistent website structure and navigation
- **Implementation**:
  - Use consistent header/footer across pages
  - Maintain consistent navigation menu structure
  - Use consistent form layouts

#### Form Labels and Instructions
- **Requirement**: Provide visible labels and instructions for form fields
- **Implementation**:
  ```html
  <!-- Good -->
  <label for="email">Email Address</label>
  <input type="email" id="email" name="email" required>
  
  <!-- With instructions -->
  <label for="password">Password</label>
  <input type="password" id="password" name="password" 
         aria-describedby="password-help" required>
  <small id="password-help">Must be at least 8 characters</small>
  ```

#### Error Identification
- **Requirement**: Clearly identify form errors in text
- **Implementation**:
  ```html
  <label for="email">Email</label>
  <input type="email" id="email" name="email" 
         aria-invalid="true" 
         aria-describedby="email-error">
  <span id="email-error" role="alert" class="error">
    Please enter a valid email address
  </span>
  ```

#### Predictable Functionality
- **Requirement**: Components behave consistently and predictably
- **Implementation**:
  - Don't change context unexpectedly (e.g., auto-submit on selection)
  - Warn users before opening new windows
  - Use consistent button styles for similar actions

### 6.2 Success Criteria Examples

- **3.1.1 Language of Page** (Level A): Page language is declared
- **3.2.3 Consistent Navigation** (Level AA): Navigation is consistent
- **3.2.4 Consistent Identification** (Level AA): Components with same function are identified consistently
- **3.3.1 Error Identification** (Level A): Errors are identified and described
- **3.3.2 Labels or Instructions** (Level A): Labels or instructions are provided

---

## 7. Principle 4: Robust (Robuust)

**Goal**: Build websites that work reliably across different devices and assistive technologies, now and in the future.

### 7.1 Key Requirements

#### Valid Code
- **Requirement**: Use code according to specifications
- **Implementation**:
  ```html
  <!-- Valid HTML5 -->
  <!DOCTYPE html>
  <html lang="nl">
    <head>
      <meta charset="UTF-8">
      <title>Page Title</title>
    </head>
    <body>
      <!-- Semantic HTML -->
      <header>...</header>
      <main>...</main>
      <footer>...</footer>
    </body>
  </html>
  ```

#### Semantic HTML
- **Requirement**: Use semantic HTML elements
- **Implementation**:
  ```html
  <!-- Good: Semantic structure -->
  <header>
    <nav>...</nav>
  </header>
  <main>
    <article>
      <h1>Article Title</h1>
      <section>...</section>
    </article>
  </main>
  <footer>...</footer>
  
  <!-- Bad: Div soup -->
  <div class="header">
    <div class="nav">...</div>
  </div>
  ```

#### ARIA When Needed
- **Requirement**: Use ARIA attributes when HTML semantics aren't sufficient
- **Implementation**:
  ```html
  <!-- Custom button -->
  <div role="button" 
       tabindex="0" 
       aria-label="Close dialog"
       onkeydown="handleKeyPress(event)">
    ×
  </div>
  
  <!-- Form validation -->
  <input type="email" 
         aria-invalid="true"
         aria-describedby="email-error">
  <span id="email-error" role="alert">Invalid email</span>
  ```

#### Name, Role, Value
- **Requirement**: Assistive technologies can determine name, role, and value of UI components
- **Implementation**:
  ```html
  <!-- Good: Native button with accessible name -->
  <button>Submit Form</button>
  
  <!-- Good: Custom control with ARIA -->
  <div role="checkbox" 
       aria-checked="false"
       aria-label="Accept terms and conditions"
       tabindex="0">
    <span class="checkbox-icon"></span>
  </div>
  ```

### 7.2 Success Criteria Examples

- **4.1.1 Parsing** (Level A): Markup is valid (no duplicate IDs, properly nested)
- **4.1.2 Name, Role, Value** (Level A): UI components have accessible names and roles
- **4.1.3 Status Messages** (Level AA): Status messages are programmatically determinable

---

## 8. Implementation Guidelines

### 8.1 Development Checklist

#### Images
- [ ] All images have appropriate `alt` text
- [ ] Decorative images have empty `alt=""`
- [ ] Complex images (charts, graphs) have detailed descriptions

#### Forms
- [ ] All form fields have `<label>` elements
- [ ] Required fields are clearly marked
- [ ] Error messages are associated with fields (`aria-describedby`)
- [ ] Form validation errors are announced to screen readers

#### Navigation
- [ ] Skip links provided for main content
- [ ] Navigation is keyboard accessible
- [ ] Focus order is logical
- [ ] No keyboard traps

#### Color & Contrast
- [ ] Text meets contrast requirements (4.5:1 for normal text)
- [ ] Color is not the only means of conveying information
- [ ] Interactive elements have visible focus indicators

#### Structure
- [ ] Semantic HTML elements used (`<header>`, `<nav>`, `<main>`, `<article>`, `<footer>`)
- [ ] Heading hierarchy is logical (`<h1>` → `<h2>` → `<h3>`)
- [ ] Page language is declared (`<html lang="nl">`)

### 8.2 Testing Tools

#### Automated Testing
- **axe DevTools**: Browser extension for accessibility testing
- **WAVE**: Web accessibility evaluation tool
- **Lighthouse**: Built into Chrome DevTools
- **Pa11y**: Command-line accessibility testing

#### Manual Testing
- **Keyboard Navigation**: Test entire site with keyboard only (Tab, Enter, Space, Arrow keys)
- **Screen Reader Testing**: Test with NVDA (Windows) or VoiceOver (Mac)
- **Color Contrast**: Use WebAIM Contrast Checker
- **Zoom Testing**: Zoom to 200% and verify functionality

### 8.3 Common Mistakes to Avoid

❌ **Don't:**
- Remove focus outlines (`outline: none` without replacement)
- Use color alone to convey information
- Create keyboard traps
- Use generic link text ("click here", "read more")
- Skip heading levels (`<h1>` → `<h3>`)
- Use `<div>` or `<span>` for buttons without proper ARIA

✅ **Do:**
- Provide visible focus indicators
- Use multiple indicators (color + text + icon)
- Test keyboard navigation
- Use descriptive link text
- Maintain logical heading hierarchy
- Use semantic HTML or proper ARIA

---

## 9. Conformance Levels

WCAG success criteria are organized into **three conformance levels**:

### Level A (Minimum)
- **Description**: Basic accessibility requirements
- **Impact**: Addresses the most critical barriers
- **Example**: Alt text for images, keyboard accessibility, page titles

### Level AA (Standard)
- **Description**: Enhanced accessibility (includes all Level A)
- **Impact**: Addresses major barriers for most users
- **Legal Requirement**: WCAG 2.1 AA is required by law in the Netherlands
- **Example**: Color contrast (4.5:1), focus indicators, consistent navigation

### Level AAA (Enhanced)
- **Description**: Highest level of accessibility (includes all Level A and AA)
- **Impact**: Addresses barriers for users with specific needs
- **Note**: Not required for all content (may not be achievable for all content types)
- **Example**: Sign language interpretation, extended audio descriptions

**For most websites**: Target **WCAG 2.1 AA** compliance as the standard.

---

## 10. AI Tool Guidelines

### 10.1 Code Generation Rules

When generating HTML/CSS/JavaScript code, AI tools should:

1. **Always include accessibility attributes**:
   ```html
   <!-- Images -->
   <img src="..." alt="descriptive text">
   
   <!-- Forms -->
   <label for="field-id">Label Text</label>
   <input id="field-id" type="text" aria-describedby="help-text">
   
   <!-- Buttons -->
   <button aria-label="Action description">Action</button>
   ```

2. **Use semantic HTML**:
   - Prefer `<button>` over `<div>` for buttons
   - Use `<nav>`, `<header>`, `<main>`, `<footer>` for structure
   - Use proper heading hierarchy (`<h1>` → `<h2>` → `<h3>`)

3. **Ensure keyboard accessibility**:
   - All interactive elements must be keyboard accessible
   - Provide visible focus indicators
   - Never create keyboard traps

4. **Check color contrast**:
   - Normal text: minimum 4.5:1 contrast ratio
   - Large text: minimum 3:1 contrast ratio
   - Don't rely on color alone

5. **Include ARIA when needed**:
   - Use ARIA for custom components
   - Associate error messages with form fields
   - Provide accessible names for icon-only buttons

### 10.2 Validation Checklist

Before finalizing code, verify:

- [ ] All images have `alt` attributes
- [ ] All form fields have associated `<label>` elements
- [ ] All interactive elements are keyboard accessible
- [ ] Focus indicators are visible
- [ ] Page has `<title>` and `<html lang="...">`
- [ ] Color contrast meets WCAG AA standards
- [ ] Semantic HTML is used appropriately
- [ ] ARIA attributes are used correctly (when needed)

### 10.3 Framework-Specific Notes

#### Laravel Blade Templates
```blade
{{-- Always include alt text --}}
<img src="{{ $image }}" alt="{{ $altText }}">

{{-- Form labels --}}
<label for="email">{{ __('Email') }}</label>
<input type="email" id="email" name="email" 
       aria-describedby="email-help">
<span id="email-help">{{ __('Enter your email address') }}</span>
```

#### React Components
```jsx
// Always include accessible props
<img src={src} alt={altText} />
<button aria-label={ariaLabel}>{children}</button>
<input 
  id={id}
  aria-invalid={hasError}
  aria-describedby={errorId}
/>
```

---

## References

- **WCAG 2.1**: https://www.w3.org/WAI/WCAG21/quickref/
- **WCAG 2.2**: https://www.w3.org/WAI/WCAG22/quickref/
- **WCAG.nl**: https://wcag.nl/kennis/richtlijnen/
- **EN 301 549**: European accessibility standard
- **Wet digitale overheid**: Dutch digital government law

---

## Quick Reference: WCAG 2.1 AA Checklist

### Must Have (Level A)
- [ ] Alt text for all images
- [ ] Keyboard accessibility
- [ ] Page titles
- [ ] Descriptive link text
- [ ] Language declaration
- [ ] Form labels
- [ ] Error identification

### Should Have (Level AA)
- [ ] Color contrast 4.5:1 (normal text) / 3:1 (large text)
- [ ] Visible focus indicators
- [ ] Consistent navigation
- [ ] Resizable text (up to 200%)
- [ ] Multiple ways to find content
- [ ] Headings and labels are descriptive

---

**Last Updated**: Based on WCAG 2.1 AA (legal standard) and WCAG 2.2 (recommendation as of October 2023)

**Source**: https://wcag.nl/kennis/richtlijnen/

