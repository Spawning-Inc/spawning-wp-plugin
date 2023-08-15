<?php

/*
    Copyright 2023 Spawning Inc

    Licensed under the Apache License, Version 2.0 (the "License");
    You may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
*/

/**
 * Function to add the Spawning AI menu item in the WordPress admin menu.
 *
 * @return void
 */
function spawning_ai_menu_item() {
    // add_menu_page is a WordPress function used to add a top-level menu page.
    add_menu_page(
        __("Spawning AI", "spawning_ai"), // Page title. This is the title that is displayed in the browser window/tab.
        __("Spawning AI", "spawning_ai"), // Menu title. This is the title that is displayed in the WordPress admin menu.
        "manage_options", // Capability required for this menu to be displayed to the user.
        "spawning-ai", // The slug by which the menu page is referred to.
        "spawning_ai_page", // The function that displays the page content for the menu page.
        "data:image/svg+xml;base64," . // The URL to the menu's icon.
            base64_encode( // Encode the SVG file content to base64
                file_get_contents(plugin_dir_path(__DIR__) . "assets/icon.svg") // Fetch the SVG file content
            )
    );
}

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

// This hook allows you to add new menus to the admin area.
// "spawning_ai_menu_item" function will be called when the admin menu is built.
add_action("admin_menu", "spawning_ai_menu_item");
