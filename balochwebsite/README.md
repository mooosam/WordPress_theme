# Baloch Heritage Canada — WordPress Theme

**Version:** 1.0.0
**Requires WordPress:** 6.0+
**Requires PHP:** 8.0+
**Author:** Baloch Cultural Society of Canada

---

## Overview

A full-featured WordPress theme for the Baloch Cultural Society of Canada. Includes a complete membership system with multi-level approval workflow, custom post types for all content areas, a **dedicated theme settings panel**, and **first-class Elementor integration** (custom widget category, 11 native widgets, theme builder support, design-token sync).

---

## Installation

1. Upload `baloch-heritage-theme.zip` via **Appearance → Themes → Add New → Upload Theme**
2. Activate.
3. **Settings → Permalinks** → choose "Post name" → Save. This registers CPT URLs.
4. Visit **Baloch Heritage → Dashboard** in the admin sidebar to start configuring.

---

## Theme Admin Panel

A top-level menu **Baloch Heritage** (palmtree icon) is added with these tabs:

| Tab | What you control |
|---|---|
| Dashboard | Member / event / article counts, site health, quick toggles |
| Site Identity | Site title, hero copy, contact info, social profiles |
| Appearance | Color palette (7 tokens), typography, layout, carpet motif |
| Homepage | Drag-reorder + toggle homepage sections |
| Menus | Pointer to WP nav menu locations |
| Elementor | Builder support per template, 11 widgets, site-settings sync |
| Forms | Contact, captcha, newsletter provider |
| Performance | Cache and asset toggles |
| Tools | Export / import / reset / flush rewrites + system info |

All settings live in a single option: `bh_theme_options`. The theme emits CSS variables in `<head>` so palette / typography / layout changes take effect immediately on save.

### Maintenance mode
Turn on **Dashboard → Quick Toggles → Maintenance mode** to show a "back soon" page to anyone except logged-in admins.

---

## Elementor Integration

**Activates automatically** once Elementor is installed. The theme:

- Declares `add_theme_support('elementor')` and `add_theme_support('elementor-pro')`
- Registers a **CBCSC** widget category in the Elementor panel
- Ships **11 native widgets** (toggleable in **Baloch Heritage → Elementor**):

| Widget | Class | Purpose |
|---|---|---|
| Events Grid | `Class_CBCSC_Events` | Pulls upcoming events, RSVP button |
| Leadership Grid | `Class_CBCSC_Leaders` | Avatar cards with role/social |
| Carpet Border | `Class_CBCSC_Carpet` | Inline motif divider |
| RSVP Form | `Class_CBCSC_Rsvp` | Bind any event, AJAX-based |
| Newsletter | `Class_CBCSC_Newsletter` | Email field + double opt-in |
| Donation CTA | `Class_CBCSC_Donate` | Campaign progress bar + button |
| Gallery Masonry | `Class_CBCSC_Gallery` | Filterable image grid |
| Story Quote | `Class_CBCSC_Quote` | Pull-quote with attribution |
| Member Login | `Class_CBCSC_Login` | Login form using `wp_login_url()` |
| Language Switch | `Class_CBCSC_Lang` | EN / Balochi toggle |
| CPT Filter Bar | `Class_CBCSC_Filter` | Taxonomy chip filter for any CPT |

- Syncs the **color palette** and **typography** to Elementor → Site Settings → Global Colors / Fonts (toggleable, on by default)
- Registers two page templates:
  - **CBCSC Canvas** — Elementor blank canvas (no header / footer)
  - **CBCSC Full-width** — keeps theme header / footer, no inner wrapper
- Enqueues `baloch-shared.css` inside the Elementor editor so widget previews match the front-end exactly

To use Elementor as your theme builder:
1. Install Elementor + Elementor Pro
2. Go to **Templates → Theme Builder** and create overrides for Header, Footer, Single `bh_event`, Archive, 404, Popups
3. Theme falls back to PHP partials wherever you haven't defined an Elementor template

---

## Custom Post Types

All managed in the WordPress admin sidebar:

| Post Type | Slug | Used For |
|---|---|---|
| `bh_event` | events | Events & Festivals |
| `bh_article` | community | Community Hub articles |
| `bh_resource` | resources | Resources & Initiatives |
| `bh_leadership` | about/team | Leadership profiles |
| `bh_gallery` | gallery | Gallery images |

---

## Membership System

### Role Hierarchy

```
pending_member → bh_member → bh_moderator → bh_board → administrator
```

| Role | Can Do |
|---|---|
| `pending_member` | Browse site only |
| `bh_member` | RSVP, view directory, post in forum |
| `bh_moderator` | + Level 1 approval |
| `bh_board` | + Level 2 approval + promote members |
| `administrator` | Full access |

### Approving Members
- **WordPress Admin** → **BH Members** → Approve / Reject
- Or frontend **Member Portal** → Approvals panel (mod / board / admin only)
- Approval emails are sent automatically.

---

## Shortcodes

| Shortcode | Output |
|---|---|
| `[bh_events limit="3"]` | Upcoming events grid |
| `[bh_leaders]` | Leadership team grid |
| `[bh_carpet_border]` | Decorative carpet border SVG |

---

## AJAX Endpoints

All use nonce `bh_nonce` (localized via `bhAjax.nonce`):

`bh_register`, `bh_login`, `bh_logout`, `bh_password_reset`, `bh_update_profile`, `bh_get_member_data`, `bh_get_members_list`, `bh_approve_member_ajax`, `bh_reject_member_ajax`, `bh_change_role`, `bh_event_rsvp`, `bh_newsletter`, `bh_contact`.

---

## File Structure

```
baloch-heritage-theme/
├── style.css
├── functions.php
├── header.php  ·  footer.php  ·  index.php
├── front-page.php
├── single.php  ·  page.php
├── page-about.php ... page-resources.php
├── inc/
│   ├── custom-post-types.php
│   ├── user-roles.php
│   ├── membership.php
│   ├── admin-panel.php           ← Theme settings UI
│   ├── elementor.php             ← Elementor integration
│   └── elementor-widgets/        ← 11 widget classes + base
│       ├── class-cbcsc-base.php
│       ├── class-cbcsc-events.php
│       ├── class-cbcsc-leaders.php
│       ├── class-cbcsc-carpet.php
│       ├── class-cbcsc-rsvp.php
│       ├── class-cbcsc-newsletter.php
│       ├── class-cbcsc-donate.php
│       ├── class-cbcsc-gallery.php
│       ├── class-cbcsc-quote.php
│       ├── class-cbcsc-login.php
│       ├── class-cbcsc-lang.php
│       └── class-cbcsc-filter.php
├── templates/
│   ├── elementor-canvas.php
│   └── elementor-fullwidth.php
└── assets/
    ├── css/
    │   ├── baloch-shared.css     ← Front-end styles
    │   └── admin-panel.css       ← Admin panel styles
    └── js/
        ├── baloch-shared.js
        └── admin-panel.js
```

---

## Recommended Plugins

| Plugin | Why |
|---|---|
| **Elementor + Elementor Pro** | Theme builder, custom widgets work out of the box |
| **WP Mail SMTP** | Reliable email delivery |
| **Yoast SEO** | SEO |
| **Wordfence** | Security |
| **WP Super Cache** | Caching |

---

*© 2026 Baloch Heritage Canada.*
