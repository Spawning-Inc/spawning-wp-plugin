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
 * Function to enqueue the main scripts for the Spawning AI plugin.
 *
 * @return void
 */

 function spawning_ai_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-tooltip');
    wp_enqueue_script('thickbox');

    // Path to the script file relative to the WordPress root directory
    $script_file = plugin_dir_path(__FILE__) . '../js/scripts.js';

    // Use file modification time for cache busting
    $script_version = file_exists($script_file) ? filemtime($script_file) : '1.0.0';

    wp_enqueue_script('spawning-ai-script', plugins_url('../js/scripts.js', __FILE__), array('jquery'), $script_version, true);
}

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

add_action('wp_enqueue_scripts', 'spawning_ai_enqueue_scripts');
add_action('admin_enqueue_scripts', 'spawning_ai_enqueue_scripts');