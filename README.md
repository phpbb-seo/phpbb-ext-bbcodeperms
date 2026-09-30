# phpBB SEO BBCode Permissions

[![phpBB Version](https://img.shields.io/badge/phpBB-3.3.0%20--%203.3.19-blue.svg)](https://www.phpbb.com/)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.2.0-8892bf.svg)](https://www.php.net/)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-orange.svg)](LICENSE)
[![Latest Release](https://img.shields.io/badge/release-v1.0.0-brightgreen.svg)](https://github.com/phpbb-seo/phpbb-ext-bbcodeperms/releases/tag/v1.0.0)

**phpBB SEO BBCode Permissions** is a lightweight, secure, and modern phpBB 3.3 extension that allows forum administrators to restrict the visibility of specific BBCodes (both built-in phpBB tags and custom forum BBCodes) from selected user groups (e.g. Guests, Newly Registered Users, or custom groups).

When an unauthorized viewer opens a post containing restricted BBCodes, the hidden content is cleanly replaced by a customizable, responsive alert box containing your custom notice and dynamic action buttons.

---

## ✨ Features

- **Built-in & Custom BBCode Support:** Restrict default phpBB BBCodes (`code`, `quote`, `url`, `img`, `attachment`, `size`, `color`, `b`, `i`, `u`, `list`, `email`) as well as any custom BBCodes created in ACP.
- **Manual Extra Tags:** Easily append comma-separated tags directly via ACP for third-party extensions (e.g. `spoiler`, `video`, `download`).
- **Flexible Group Checking Modes:**
  - **Any Group (Default):** Content is hidden if the viewer is a member of *any* restricted group.
  - **Primary Group Only:** Content is hidden *only* if the viewer's primary/default group is restricted.
- **Administrator Bypass:** Administrators and board founders can always view all BBCodes without restriction.
- **Quote & Reply Preview Protection:** Automatically sanitizes content inside nested quotes and prevents unauthorized users from inspecting hidden text via quote/reply previews.
- **Modern ACP Interface:**
  - Filterable checklist boxes with real-time search.
  - One-click **Select All** and **Deselect All** controls.
  - Dynamic selected item counters.
  - **Live Message Preview:** Real-time preview of your access denied alert as you type.
  - **Quick Insert Toolbar & Tags Table:** Click any tag badge or "+ Insert" button to insert dynamic placeholders at the cursor position.
- **Full HTML Support:** Craft responsive alert notices with custom HTML, FontAwesome icons, and styled call-to-action buttons.
- **Multilingual Support:** Complete English (`en`) and Persian (`fa`) translations included out of the box.
- **Theme & RTL Ready:** Styled for prosilver, dark themes, and right-to-left languages.

---

## 🏷️ Available Dynamic Placeholders

You can use the following placeholders inside your custom Access Denied message template:

| Placeholder Tag | Description | Example Replacement |
| :--- | :--- | :--- |
| `{REGISTER_URL}` | Direct URL to forum registration page (with session ID) | `./ucp.php?mode=register` |
| `{LOGIN_URL}` | Direct URL to forum login page | `./ucp.php?mode=login` |
| `{USERNAME}` | Current viewer's username (or "Guest" if anonymous) | `JohnDoe` |
| `{SITENAME}` | Board site name as configured in board settings | `Forum Community` |
| `{TAG}` | Name of the restricted BBCode tag | `code` |

---

## 📋 Requirements

- **phpBB:** 3.3.0 or higher (tested up to 3.3.19)
- **PHP:** 7.2 or higher (PHP 8.0, 8.1, 8.2, 8.3+ compatible)

---

## 🚀 Installation

1. Download the latest release (`phpbbseo_bbcodeperms_1.0.0.zip`).
2. Extract the archive into the `ext/` directory of your phpBB board.
   - The resulting directory structure must be:  
     `ext/phpbbseo/bbcodeperms/composer.json`
3. In your Web browser, navigate to **ACP** &rarr; **Customise** &rarr; **Manage extensions**.
4. Locate **phpBB SEO BBCode Permissions** under *Disabled Extensions* and click **Enable**.
5. Configure your permissions in **ACP** &rarr; **Extensions** &rarr; **BBCode Permissions**.

---

## 🔄 Uninstallation

1. Navigate to **ACP** &rarr; **Customise** &rarr; **Manage extensions**.
2. Locate **phpBB SEO BBCode Permissions** and click **Disable**.
3. To permanently remove all data, click **Delete data**, then remove the `ext/phpbbseo/bbcodeperms/` directory from your server.

---

## 🌐 Languages

- English (`en`) - Default
- Persian (`fa`) - فارسی

---

## 📄 License

This extension is open-source software licensed under the [GNU General Public License v2 (GPL-2.0)](LICENSE).

Copyright (c) 2026 [Meis@M](https://www.phpbbseo.com)
