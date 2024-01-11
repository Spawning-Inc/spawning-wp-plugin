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
    $main_css_path = "../css/main.css";
    $jquery_ui_css_path = "../css/smoothness-jquery-ui.css";

    // Fetch the file system path for main.css and smoothness-jquery-ui.css
    $main_css_file_path = plugin_dir_path( __FILE__ ) . $main_css_path;
    $jquery_ui_css_file_path = plugin_dir_path( __FILE__ ) . $jquery_ui_css_path;

    // Get the last modified time of the files
    $main_css_version = file_exists($main_css_file_path) ? filemtime($main_css_file_path) : false;
    $jquery_ui_css_version = file_exists($jquery_ui_css_file_path) ? filemtime($jquery_ui_css_file_path) : false;

    // Enqueue the main stylesheet with cache busting
    wp_enqueue_style(
        "spawning-ai-style",
        plugins_url($main_css_path, __FILE__),
        array(), // Dependencies
        $main_css_version // Version number for cache busting
    );

    // Enqueue the jQuery UI stylesheet with cache busting
    wp_enqueue_style(
        'jquery-ui-css',
        plugins_url($jquery_ui_css_path, __FILE__),
        array(),
        $jquery_ui_css_version
    );

    // Enqueue the Thickbox stylesheet
    wp_enqueue_style('thickbox');
}

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

// This hook allows you to enqueue your scripts/styles in the admin area only.
// "spawning_ai_enqueue_styles" function will be called when scripts and styles are enqueued on the admin page.
add_action("admin_enqueue_scripts", "spawning_ai_enqueue_styles");
