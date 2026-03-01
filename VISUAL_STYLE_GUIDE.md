# Visual Style Guide - Coop eHub Branding

## Color Palette

### Primary Colors
```
┌─────────────────────────────────────────────────────────┐
│ DEEP BLUE (Primary)                                     │
│ #0d6efd                                                 │
│ RGB: 13, 110, 253                                       │
│ HSL: 217°, 98%, 52%                                     │
│ Usage: Sidebar, Headers, Primary buttons, Links         │
├─────────────────────────────────────────────────────────┤
│ DARK BLUE (Primary Dark)                                │
│ #0b5ed7                                                 │
│ RGB: 11, 94, 215                                        │
│ HSL: 220°, 93%, 44%                                     │
│ Usage: Hover states, Active buttons                     │
└─────────────────────────────────────────────────────────┘
```

### Status Colors
```
┌──────────────────────────────────────────────────────────┐
│ SUCCESS (Active Status) - GREEN                          │
│ #198754                                                  │
│ Usage: Active badge, Success alerts, Positive amounts    │
│                                                          │
│ WARNING (Client Status) - YELLOW                         │
│ #ffc107                                                  │
│ Usage: Client badge, Warning alerts                      │
│                                                          │
│ DANGER (Inactive) - RED                                  │
│ #dc3545                                                  │
│ Usage: Inactive badge, Error alerts, Negative amounts    │
│                                                          │
│ INFO (Information) - CYAN                                │
│ #0dcaf0                                                  │
│ Usage: Info alerts, Info badges                          │
└──────────────────────────────────────────────────────────┘
```

### Neutral Colors
```
┌──────────────────────────────────────────────────────────┐
│ LIGHT GRAY (Light background)                            │
│ #f8f9fa                                                  │
│ Usage: Page background, Card backgrounds                 │
│                                                          │
│ DARK GRAY (Section background)                           │
│ #e9ecef                                                  │
│ Usage: Table headers, Section dividers                   │
│                                                          │
│ BORDER COLOR                                             │
│ #dee2e6                                                  │
│ Usage: Table borders, Card borders                       │
│                                                          │
│ TEXT PRIMARY                                             │
│ #212529                                                  │
│ Usage: Body text, Headers                                │
│                                                          │
│ TEXT SECONDARY                                           │
│ #6c757d                                                  │
│ Usage: Helper text, Muted content                        │
└──────────────────────────────────────────────────────────┘
```

## Typography

### Font Family
```
Primary: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
Fallback: system-ui, ui-sans-serif
```

### Sizes
```
Body:         0.95rem  (15.2px)
Small:        0.875rem (14px)
XSmall:       0.75rem  (12px)

H1:           2.5rem   (40px) - Page titles
H2:           2rem     (32px) - Section headers
H3:           1.5rem   (24px) - Subsection headers
H4:           1.25rem  (20px) - Card titles
H5:           1.125rem (18px) - Small headers
H6:           1rem     (16px) - Smallest headers
```

### Line Heights
```
Base:         1.6
Headers:      1.2
Small text:   1.4
```

### Font Weights
```
Light:        300
Normal:       400
Medium:       500
Semibold:     600 (headers)
Bold:         700
ExtraBold:    800
```

## Spacing System

### Base Unit: 0.25rem (4px)

```
Spacing scale:
  1 unit   = 0.25rem  (4px)
  2 units  = 0.5rem   (8px)
  3 units  = 0.75rem  (12px)
  4 units  = 1rem     (16px)
  6 units  = 1.5rem   (24px)
  8 units  = 2rem     (32px)
  12 units = 3rem     (48px)
  16 units = 4rem     (64px)
  20 units = 5rem     (80px)
```

### Bootstrap Margin/Padding Classes
```
m-1 = margin: 0.25rem
m-2 = margin: 0.5rem
m-3 = margin: 0.75rem
m-4 = margin: 1rem
m-5 = margin: 1.5rem
m-6 = margin: 3rem

p-1 = padding: 0.25rem
p-2 = padding: 0.5rem
p-3 = padding: 0.75rem
p-4 = padding: 1rem
p-5 = padding: 1.5rem
p-6 = padding: 3rem

Responsive: m-md-3 (applies at md breakpoint and above)
```

## Border Radius

```
None:     0
Small:    0.25rem  (4px)   - Small buttons, inputs
Medium:   0.375rem (6px)   - Standard (default)
Large:    0.5rem   (8px)   - Cards
XLarge:   0.75rem  (12px)  - Large components
Full:     9999px           - Perfect circles/pills
```

## Shadow System

### Shadows
```
None:       none
Small:      0 1px 3px rgba(0, 0, 0, 0.05)
Medium:     0 4px 12px rgba(0, 0, 0, 0.1)
Large:      0 10px 15px rgba(0, 0, 0, 0.1)
```

### Usage
```
Hover states:       box-shadow: shadow-md
Elevated items:     box-shadow: shadow-lg
Flat layout:        box-shadow: shadow-sm
```

## Component Designs

### Badge / Status Indicator

```
┌─────────────────────────┐
│  ● Active               │  Green background
│  ● Client               │  Yellow background
│  ● Inactive             │  Red background
└─────────────────────────┘

Styles:
- Padding: 0.5rem 0.75rem (8px 12px)
- Font-weight: 600
- Font-size: 0.8rem
- Border-radius: 0.25rem
- Color: White (except warning = black)
```

### Summary Card

```
┌──────────────────────────────────────┐
│ ■ Left border (4px, colored)         │
│                                      │
│ TITLE (uppercase, 0.875rem)          │
│                                      │
│ ₱45,000.00  (1.75rem, bold, colored) │
│                                      │
│ Subtext (0.875rem, muted)            │
└──────────────────────────────────────┘

Border colors by type:
- Default/Info: #0d6efd (Blue)
- Success: #198754 (Green)
- Warning: #ffc107 (Yellow)
- Danger: #dc3545 (Red)
```

### Header / Title Section

```
┌──────────────────────────────────────┐
│ Page Title                           │  H1, color: #0d6efd
│ Subtitle or description              │  Small text, muted
└──────────────────────────────────────┘

Properties:
- White background
- Padding: 1.5rem 2rem
- Border-bottom: 1px solid #dee2e6
- Margin-bottom: 2rem
- Border-radius: 0.5rem
```

### Card

```
┌──────────────────────────────────────┐
│ ░ Header (background: #f5f6f7)       │  H5, color: #0d6efd
├──────────────────────────────────────┤  1px border
│ Body content                         │  Padding: 1.5rem
│                                      │
│ Multiple lines of content            │
└──────────────────────────────────────┘

Properties:
- Border: 1px solid #dee2e6
- Shadow: 0 1px 3px rgba(0,0,0,0.05)
- Border-radius: 0.5rem
- Hover shadow: 0 4px 12px rgba(0,0,0,0.1)
```

### Button

```
┌─────────────────────────┐
│      PRIMARY BUTTON     │  #0d6efd background
│  (Font-weight: 600)     │  White text
└─────────────────────────┘  1px border radius

States:
Normal:     #0d6efd background
Hover:      #0b5ed7 background + shadow
Active:     Darker blue
Disabled:   Opacity: 0.5

Types:
Primary:    Solid blue
Secondary:  Outline blue
Danger:     Solid red (delete actions)
Success:    Solid green
```

### Table Design

```
┌──────────────┬──────────────┬──────────────┐
│ COLUMN 1     │ COLUMN 2     │ COLUMN 3     │  Header: bg #f5f6f7
├──────────────┼──────────────┼──────────────┤  Color: #0d6efd
│ Data         │ Data         │ Data         │  Font-weight: 600
├──────────────┼──────────────┼──────────────┤  Text-transform: uppercase
│ Data         │ Data         │ Data         │  Font-size: 0.875rem
└──────────────┴──────────────┴──────────────┘

Row properties:
- Height: Auto (max content)
- Padding: 0.875rem 1rem
- Border: 1px solid #dee2e6
- Hover: bg #f8f9fa

Last row: No bottom border
```

### Form Input

```
┌──────────────────────────────┐
│ Label (font-weight: 600)     │  Margin-bottom: 0.5rem
├──────────────────────────────┤
│ [Input field placeholder...] │  Padding: 0.625rem 0.875rem
├──────────────────────────────┤  Border: 1px solid #dee2e6
│ Helper text or error         │  Border-radius: 0.375rem
└──────────────────────────────┘  Focus: border #0d6efd
                                   Focus: shadow: 0 0 0 0.2rem rgba(13,110,253,0.15)
```

### Modal Dialog

```
┌──────────────────────────────────┐
│ ■ Modal Title         [X]         │  Header: bg #f5f6f7
├──────────────────────────────────┤  Color: #0d6efd
│                                  │
│ Modal content                    │  Padding: varies
│                                  │  Max-width: 500px
│                                  │
├──────────────────────────────────┤
│ [Cancel] [Action Button]         │  Right-aligned buttons
└──────────────────────────────────┘  Padding: 1rem
```

## Responsive Breakpoints

```
Mobile:    < 576px   (xs)    Full width layout
          576px-    (sm)    Small adjustments

Tablet:   768px-    (md)    Sidebar appears
         992px-    (lg)    Standard layout

Desktop: 1200px-   (xl)    Full features
        1400px-   (xxl)   Extra wide layout
```

## Animations & Transitions

```
Default duration:  0.15s
Timing function:   cubic-bezier(0.4, 0, 0.2, 1)

Transitions:
- Box-shadow: on card hover
- Color: on link hover
- All: on button state changes
- Opacity: on alert fade
```

## Density

```
Comfortable:    Forms, Cards, Modals
                Padding: 1rem - 1.5rem

Compact:        Tables, Lists
                Padding: 0.75rem - 1rem

Spacious:       Headers, Main sections
                Padding: 1.5rem - 2rem
```

## Icon System

### Font Awesome Integration

```
<i class="fas fa-chart-line"></i>     Dashboard
<i class="fas fa-users"></i>          Members
<i class="fas fa-receipt"></i>        Receipts
<i class="fas fa-book"></i>           Ledger
<i class="fas fa-file-alt"></i>       Audit
<i class="fas fa-plus"></i>           Add
<i class="fas fa-edit"></i>           Edit
<i class="fas fa-trash"></i>          Delete
<i class="fas fa-print"></i>          Print
<i class="fas fa-download"></i>       Download
<i class="fas fa-search"></i>         Search

Icon size: 1.25rem in navigation
Icon size: 1rem in tables/lists
Color: Inherit from text
```

## Dark Mode (Optional)

```
Dark background:    #0a0a0a
Dark surface:       #161615
Dark border:        #3e3e3a
Dark text:          #ededec
Dark secondary:     #a1a09a

(Use CSS variables for easy implementation)
```

## Accessibility Notes

### Color Contrast
```
✓ #0d6efd on white: 8.12:1 ratio (AAA compliant)
✓ #198754 on white: 5.47:1 ratio (AA compliant)
✓ #6c757d on white: 5.74:1 ratio (AA compliant)
✓ #dc3545 on white: 3.98:1 ratio (AA compliant)
```

### Focus States
```
Keyboard navigation:    Blue focus ring
Tab order:              Logical flow
Skip links:             Available on all pages
```

---

## Summary

The Coop eHub design system emphasizes:
- **Professionalism**: Clean, business-like appearance
- **Clarity**: Clear hierarchy and information flow
- **Efficiency**: Responsive, quick to scan
- **Consistency**: Uniform styling throughout
- **Accessibility**: High contrast, readable text
- **Trust**: Professional color palette

Use these guidelines to maintain consistency when extending the design.
