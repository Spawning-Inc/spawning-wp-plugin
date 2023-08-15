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
 * Function to enqueue the Dashicons style.
 * Dashicons is the official icon font of the WordPress admin as of 3.8.
 * 
 * @return void
 */
function spawning_ai_enqueue_dashicons() {
    // This function enqueues the dashicons style to be used in the admin interface.
    wp_enqueue_style("dashicons");
}

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

// This hook allows you to enqueue your scripts/styles in the admin area only.
// "spawning_ai_enqueue_dashicons" function will be called when scripts and styles are enqueued on the admin page.
add_action("admin_enqueue_scripts", "spawning_ai_enqueue_dashicons");
