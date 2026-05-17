# Admin Management - Vacation Rental WordPress Website Plugin

A professional, modular WordPress administration plugin for managing system pages, settings mappings, and core configuration values in a unified custom database schema.

---

## Table of Contents

1. [Overview](#1-overview)
2. [Features](#2-features)
3. [How It Works](#3-how-it-works)
4. [File & Directory Structure](#4-file--directory-structure)
5. [Database Schema](#5-database-schema)
6. [AJAX API Reference](#6-ajax-api-reference)
7. [Security](#7-security)
8. [Asset Loading System](#8-asset-loading-system)
9. [Installation & Setup](#9-installation--setup)

---

## 1. Overview

The **Admin Management** plugin provides site administrators with a unified control center to:

- **Map application routes to WordPress pages** - Link structural views like Profile Dashboard, Listing Archive, and OAuth credentials to specific WordPress page objects
- **Store configuration values** - Centralize system values like login/logout routes, auth client IDs
- **Manage database records** - Monitor, create, update, or prune the custom database table
- **Real-time feedback** - AJAX-powered saves with toast notifications

Rather than scattering settings across WordPress options API, this plugin stores all configuration in a dedicated custom table (`wp_admin_management`) and provides a modern admin interface with micro-animations and real-time updates.

---

## 2. Features

### Page Management
- Asynchronously link application routes to WordPress pages via dropdown menus
- Visual status indicators showing synced/unsaved changes
- Tabbed interface for Page Mappings and General Settings

### General Configuration
- Logout Route - Dynamic redirect URLs for session termination
- Login Route - Authentication gate redirects
- Auth Client ID - Google/OAuth Client Credentials

### Database Management
- Create/Update custom database table with one click
- Real-time status monitoring (table existence, row count, last updated)
- View all database records with inline actions
- Automatic data pruning - removes obsolete/deprecated entries

### Notifications
- Lightweight toaster system for success/error/warning messages
- Non-blocking toast notifications for all AJAX operations

---

## 3. How It Works

### 3.1 Plugin Initialization Flow

```
WordPress loads plugin
        ↓
admin-management.php executes
        ↓
Defines constants: ADMIN_MANG_PATH, ADMIN_MANG_URL
        ↓
Includes files in order:
  1. helpers.php        (Database utility functions)
  2. db-schema.php      (Table creation & schema)
  3. assets-loader.php  (CSS/JS loading system)
  4. class-admin-mang-ajax.php (AJAX handlers)
  5. class-admin-mang-plugin.php (Main controller)
        ↓
Instantiates: new Admin_Mang_Plugin()
        ↓
Registers WordPress hooks:
  - admin_menu         (Creates admin menu)
  - admin_enqueue_scripts (Loads assets)
  - plugin_action_links (Settings link)
```

### 3.2 Page Rendering Flow

```
Admin visits plugin page
        ↓
WordPress triggers admin_menu hook
        ↓
Admin_Mang_Plugin::admin_mang_register_menus()
  Adds: "Admin Management" parent menu
        ↓
WordPress triggers admin_enqueue_scripts hook
        ↓
Admin_Mang_Plugin::admin_mang_enqueue_assets()
        ↓
Delegates to assets-loader.php::admin_mang_load_assets()
        ↓
Enqueues CSS/JS based on current screen
  - Global styles (global.css)
  - Global utilities (global-utils.js)
  - Toaster component (toaster.css, toaster.js)
  - Screen-specific assets
        ↓
Renders view via admin_mang_render_view('template-name')
        ↓
Template loads data from database and renders HTML
```

### 3.3 AJAX Request Flow

```
User interacts (form submit, button click)
        ↓
JavaScript calls window.adminMang.ajax(action, data)
        ↓
Wraps data with nonce and sends POST to admin-ajax.php
        ↓
WordPress routes to: wp_ajax_admin_mang_{action}
        ↓
class-admin-mang-ajax.php handles request
        ↓
verify_request() validates:
  - Nonce verification (wp_verify_nonce)
  - Capability check (manage_options)
        ↓
Processes database operations via:
  - db-schema.php (create tables)
  - helpers.php (CRUD operations)
        ↓
Returns JSON response
        ↓
JavaScript receives response and shows toaster notification
```

### 3.4 Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        USER INTERFACE                            │
│  ┌─────────────────┐    ┌─────────────────┐                   │
│  │ Page Management │    │    Database     │                   │
│  │     Screen      │    │     Screen      │                   │
│  └────────┬────────┘    └────────┬────────┘                   │
│           │                       │                             │
│           ▼                       ▼                             │
│  ┌─────────────────────────────────────────────┐                │
│  │         JavaScript Layer                   │                │
│  │  admin-management.js  │  database.js       │                │
│  │  window.adminMang.ajax() wrapper          │                │
│  └─────────────────────┬─────────────────────┘                │
└────────────────────────┼────────────────────────────────────────┘
                         │ AJAX POST (admin-ajax.php)
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                      AJAX HANDLER LAYER                          │
│  ┌─────────────────────────────────────────────┐                │
│  │     class-admin-mang-ajax.php               │                │
│  │  • verify_request() - Security check        │                │
│  │  • admin_mang_save_page_entries()           │                │
│  │  • admin_mang_update_tables()               │                │
│  │  • admin_mang_refresh_status()              │                │
│  └─────────────────────┬─────────────────────┘                │
└────────────────────────┼────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER                             │
│  ┌──────────────────┐    ┌──────────────────┐                   │
│  │   db-schema.php  │    │   helpers.php   │                   │
│  │  • create_tables │    │  • table_exists │                   │
│  │  • get_defaults  │    │  • get_row_count│                   │
│  │  • prune_data    │    │  • get_all_rows │                   │
│  └────────┬─────────┘    └────────┬─────────┘                   │
│           │                        │                              │
│           └───────────┬────────────┘                              │
│                       ▼                                           │
│         ┌─────────────────────────┐                             │
│         │  wp_admin_management    │                             │
│         │      (MySQL Table)       │                             │
│         └─────────────────────────┘                             │
└─────────────────────────────────────────────────────────────────┘
```

---

## 4. File & Directory Structure

```
Vacation-Rental-Admin-Management/
├── admin-management.php                 # Plugin bootstrap & entry point
│
├── includes/                            # Core PHP Controllers
│   ├── class-admin-mang-plugin.php      # Main plugin class - menu registration, screen routing
│   ├── class-admin-mang-ajax.php        # AJAX request handler - 3 endpoints
│   ├── assets-loader.php                # Centralized asset loading & view rendering
│   ├── db-schema.php                    # Database table creation, schema, pruning
│   └── helpers.php                       # Database utility functions
│
├── assets/                              # Production assets
│   ├── css/
│   │   ├── admin-management.css         # Page Management screen styles
│   │   └── database.css                 # Database Management screen styles
│   └── js/
│       ├── admin-management.js          # Tab switching, form submission, status tracking
│       └── database.js                   # Table operations, status refresh
│
├── components/                          # Shared components
│   ├── global/
│   │   ├── global.css                   # Design tokens, CSS variables, global resets
│   │   └── global-utils.js              # Global AJAX wrapper (adminMang.ajax)
│   └── toaster/
│       ├── toaster.php                  # Toast notification HTML markup
│       ├── toaster.css                  # Toast animation & styling
│       └── toaster.js                   # Toast show/hide functionality
│
└── templates/                           # View templates
    ├── admin-management.php             # Page Management UI - tabs, forms, inputs
    └── database.php                      # Database Management UI - status, actions, table
```

### File Descriptions

| File | Purpose |
|------|---------|
| **admin-management.php** | Plugin entry point - defines constants, includes files, instantiates main class |
| **class-admin-mang-plugin.php** | Main controller - registers admin menu, enqueues assets, renders screens |
| **class-admin-mang-ajax.php** | AJAX handler - processes save requests, table operations, status checks |
| **assets-loader.php** | Asset management - enqueues CSS/JS with cache busting, renders views |
| **db-schema.php** | Database schema - creates table, defines default entries, auto-pruning logic |
| **helpers.php** | Database helpers - table_exists, get_row_count, get_all_entries utilities |
| **global.css** | Design tokens - CSS variables for colors, typography, spacing |
| **global-utils.js** | Global utilities - adminMang.ajax() wrapper for AJAX calls |
| **toaster.php** | Toast HTML - container, icons, message elements |
| **toaster.js** | Toast logic - showToaster() function, auto-hide, close button |
| **admin-management.js** | Page management - tab switching, form collection, save via AJAX |
| **database.js** | Database operations - create table, refresh status, render table |

---

## 5. Database Schema

### Table Structure

All configuration data is stored in a single custom table: `wp_{prefix}admin_management`

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique row identifier |
| `name` | VARCHAR(255) | UNIQUE KEY, NOT NULL | Unique setting key (e.g., `my_profile`, `logout`) |
| `page_id` | VARCHAR(255) | DEFAULT NULL | WordPress page ID for page-type entries |
| `value` | TEXT | DEFAULT NULL | Configuration value (URLs, tokens, IDs) |
| `created_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Creation timestamp |
| `updated_at` | DATETIME | ON UPDATE CURRENT_TIMESTAMP | Last modified timestamp |

### Default Schema Entries

The plugin automatically manages these default entries:

**Page Type Entries** (select WordPress page from dropdown):
| Key | Description |
|-----|-------------|
| `my_profile` | User profile dashboard page |
| `listing_archive` | Listings archive page |
| `listing_single` | Single listing view page |
| `add_listing` | Add new listing page |
| `my_listings` | User's listings page |
| `my_wishlist` | Wishlist page |

**Value Type Entries** (text input):
| Key | Description |
|-----|-------------|
| `logout` | Logout redirect URL |
| `login` | Login redirect URL |
| `auth_client_id` | Google/OAuth Client ID |

### Automatic Pruning

When creating/updating the table, the system automatically:
1. Validates all entries defined in the default schema
2. Identifies obsolete/deprecated entries (entries in DB but not in schema)
3. Deletes orphaned rows via `$wpdb->delete`
4. Ensures only active configuration entries remain

---

## 6. AJAX API Reference

All AJAX requests use the global wrapper: `window.adminMang.ajax(action, data, onSuccess, onError)`

### Endpoint 1: save_page_entries

Save page mappings and configuration values.

```javascript
window.adminMang.ajax('save_page_entries', {
    entries: [
        { name: 'my_profile', page_id: '42', value: '' },
        { name: 'logout', page_id: '', value: '/custom-logout' },
        { name: 'auth_client_id', page_id: '', value: 'abc123.apps.googleusercontent.com' }
    ]
}, function(response) {
    // response.data.message
}, function(error) {
    // error message
});
```

**Action:** `admin_mang_save_page_entries`  
**Data:** `{entries: Array<{name, page_id, value}>}`  
**Response:** `{success: true/false, data: {message: string}}`  
**Capability Required:** `manage_options`

---

### Endpoint 2: update_tables

Create or update the custom database table.

```javascript
window.adminMang.ajax('update_tables', {}, function(response) {
    // Triggers status refresh after success
});
```

**Action:** `admin_mang_update_tables`  
**Data:** `{}` (empty)  
**Response:** `{success: true/false, data: {message: string}}`  
**Capability Required:** `manage_options`

---

### Endpoint 3: refresh_status

Get current database status and all records.

```javascript
window.adminMang.ajax('refresh_status', {}, function(response) {
    // response.data.exists      (boolean)
    // response.data.rowCount    (number)
    // response.data.lastUpdated (string)
    // response.data.entries     (array)
});
```

**Action:** `admin_mang_refresh_status`  
**Data:** `{}` (empty)  
**Response:**
```json
{
    "success": true,
    "data": {
        "exists": true,
        "rowCount": 10,
        "lastUpdated": "2024-01-15 14:30:00",
        "entries": [...]
    }
}
```
**Capability Required:** `manage_options`

---

## 7. Security

### Nonce Verification
All AJAX requests include a cryptographic nonce for CSRF protection.

- **Nonce Creation:** Generated in `assets-loader.php` via `wp_create_nonce('admin_mang_nonce')`
- **Nonce Location:** Passed to JavaScript via `admin_mang_obj.nonce`
- **Verification:** `wp_verify_nonce($_POST['nonce'], 'admin_mang_nonce')`

### Capability Check
All sensitive operations verify user capability:

- **Required Capability:** `manage_options` (Administrator level)
- **Verification Method:** `current_user_can('manage_options')`
- **Enforced Actions:**
  - Saving page entries
  - Creating/updating database table
  - Any database modifications

### Security Flow

```
AJAX Request Received
        ↓
Check nonce (wp_verify_nonce)
        ↓ ✗ Reject → Return JSON error
        ↓ ✓ Pass
Check capability (current_user_can)
        ↓ ✗ Reject → Return JSON error
        ↓ ✓ Pass
Process request
        ↓
Return JSON response
```

---

## 8. Asset Loading System

### Centralized Loader (assets-loader.php)

The plugin uses a centralized asset loading system to avoid scattered file references.

**Key Features:**
- **Dynamic Cache Busting:** Uses `filemtime()` to auto-version assets
- **Screen-Specific Loading:** Only loads assets on plugin pages
- **Unified API:** Single function for all CSS/JS enqueueing

### Asset Loading Order

```
1. Global Design Tokens
   └── components/global/global.css
   
2. Global JavaScript Utilities
   └── components/global/global-utils.js
   
3. Toaster Component (all plugin screens)
   └── components/toaster/toaster.css
   └── components/toaster/toaster.js
   
4. Screen-Specific Assets
   └── assets/css/admin-management.css + assets/js/admin-management.js
   OR
   └── assets/css/database.css + assets/js/database.js
```

### Toaster Auto-Injection

The toaster component is automatically mounted on all plugin screens via:

```php
add_action('admin_footer', 'admin_mang_include_toaster_template');
```

This eliminates the need to manually include toaster markup in each template file.

### Localized JavaScript Data

The `admin_mang_obj` object is localized to JavaScript:

```javascript
window.admin_mang_obj = {
    ajax_url: 'https://site.com/wp-admin/admin-ajax.php',
    nonce: 'abc123xyz'
};
```

Used by all JavaScript files for AJAX communication.

---

## 9. Installation & Setup

### Installation

1. **Upload Plugin**
   - Compress the `Vacation-Rental-Admin-Management` folder to a zip file
   - Navigate to WordPress Dashboard → Plugins → Add New → Upload Plugin
   - Select the zip file and install

   **OR**

   - Copy the `Vacation-Rental-Admin-Management` folder directly to `wp-content/plugins/`

2. **Activate**
   - Go to Plugins → Installed Plugins
   - Find "Admin Management" and click **Activate**

### Initial Setup

1. **Access Plugin**
   - A new menu "Admin Management" appears in the left sidebar
   - Click to access the main dashboard

2. **Create Database Table**
   - Navigate to **Admin Management** → **Database**
   - Click **Create / Update Table** button
   - Wait for success toast notification
   - Status will show: "EXISTS" with row count

3. **Configure Settings**
   - Go to **Page Management** tab
   - Select WordPress pages for each route
   - Enter values for login/logout URLs and auth client ID
   - Click **Save Changes**
   - Toast confirms successful save

### Verification Checklist

- [ ] Plugin appears in WordPress plugin list (active state)
- [ ] "Admin Management" menu visible in admin sidebar
- [ ] Database table created (`wp_admin_management` exists)
- [ ] Default entries populated (10 rows in table)
- [ ] Page dropdowns populated with WordPress pages
- [ ] Save changes triggers success toast
- [ ] Database status shows accurate row count

---

## Technical Support

For issues or questions:
- Review AJAX response in browser console
- Check WordPress debug.log for PHP errors
- Verify database table exists in phpMyAdmin

---

*Document Version: 1.0*
*Compatible with WordPress 5.0+*