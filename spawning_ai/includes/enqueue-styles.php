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
 * Function to enqueue the main style for the Spawning AI plugin.
 *
 * @return void
 */
function spawning_ai_enqueue_styles() {
    // This function uses WordPress's wp_enqueue_style to include a CSS file located in the plugin's css directory.
    // The plugins_url function is used to create a URL for the css file based on the location of the current file (__FILE__).
    wp_enqueue_style(
        "spawning-ai-style", // Handle for the stylesheet. Should be unique as it is used to identify the script in the whole system.
        plugins_url("../css/main.css", __FILE__) // Path to the CSS file. plugins_url function is used to get the correct URL regardless of where the WordPress is installed.
    );
    wp_enqueue_style('jquery-ui-css', plugins_url("../css/smoothness-jquery-ui.css", __FILE__));  // You might want to host this CSS yourself or choose a different theme.
    wp_enqueue_style('thickbox');

}

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

// This hook allows you to enqueue your scripts/styles in the admin area only.
// "spawning_ai_enqueue_styles" function will be called when scripts and styles are enqueued on the admin page.
add_action("admin_enqueue_scripts", "spawning_ai_enqueue_styles");