# Simple Client Portfolio for Elementor

![Version](https://img.shields.io/badge/Version-2.0.0-blue)
![Status](https://img.shields.io/badge/Status-Complete-brightgreen)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)

A lightweight, performance-focused WordPress plugin that registers a "Clients" Custom Post Type and provides a full set of user-friendly Elementor widgets to display the client data in any way you imagine.

---

## The Philosophy: Performance & Simplicity

In many cases, a simple functionality like a client portfolio does not require a heavy, multi-purpose plugin like `Advanced Custom Fields (ACF)` or `Toolset`. Such plugins, while powerful, can add unnecessary overhead and database queries.

This plugin was built with a "less is more" approach. It uses only native WordPress APIs to create a lean, fast, and secure solution. It then extends this functionality directly into the Elementor editor, providing a seamless and intuitive experience for site builders without sacrificing performance.

## 🌟 Key Features

#### **Backend (The Engine):**
*   **Custom Post Type:** Creates a clean "Clients" CPT in your WordPress admin menu.
*   **Custom Taxonomy:** Adds a filterable "Industries" taxonomy to categorize your clients.
*   **Custom Meta Field:** Includes a simple field to add each client's website URL.
*   **Optimized Admin Experience:** Displays the client's logo directly in the admin list for quick visual reference.
*   **Zero Dependencies:** Built entirely with native WordPress functions. No third-party plugins are required for the core functionality.

#### **Frontend (Elementor Integration):**
A complete set of widgets, designed to work perfectly with Elementor's `Single Templates` and `Loop Grids`.

*   **`Simple Clients Grid`**: A quick, standalone widget to display a basic grid of client logos anywhere on your site.
*   **`Client Title (Single/Loop)`**: Displays the client's name. Fully styleable.
*   **`Client Logo (Single/Loop)`**: Displays the client's logo, with options for linking and image size.
*   **`Client Data (Single/Loop)`**: A flexible widget to display other data, such as the website URL or the associated industries.

## 🚀 How to Use

1.  **Install & Activate:** Install and activate the plugin like any other.
2.  **Add Your Clients:** Navigate to the new "Clients" menu in your admin dashboard and add your clients, assigning them a logo (as a Featured Image), a website URL, and industries.
3.  **Design with Elementor:**
    *   **For Archive Pages:** Create a new "Loop Item" template in Elementor's Theme Builder. Inside this template, use the `Client Title`, `Client Logo`, and `Client Data` widgets to design how a single client in a list should look. Then, on your main archive page, use Elementor's `Loop Grid` widget and set its source to your new template.
    *   **For Single Pages:** Create a new "Single" template for "Clients" in the Theme Builder. Use the `Client Title`, `Client Logo`, and `Client Data` widgets to build the detailed page for a single client.

## 🛠️ Technologies Used

*   WordPress Plugin API (`register_post_type`, `register_taxonomy`, `add_meta_box`)
*   Elementor Widget API
*   PHP 7.4+
*   `WP_Query`

