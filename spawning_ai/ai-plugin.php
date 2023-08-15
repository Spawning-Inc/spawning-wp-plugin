<?php
/*
   Plugin Name: Spawning AI
   Description: This plugin creates a file named ai.txt with dynamic text based on user-selected checkboxes. The ai.txt file is deleted when the plugin is uninstalled.
   Version: 1.0
   Author URI: https://spawning.ai
   Author: Spawning AI
   */

/* 
    Copyright 2023 Spawning Inc

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License. 
    */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

require_once 'includes/enqueue-scripts.php';
require_once 'includes/menu-item.php';
require_once 'includes/enqueue-dashicons.php';
require_once 'includes/enqueue-styles.php';
require_once 'includes/spawning-ai-page.php';
require_once 'includes/form-handler.php';
require_once 'includes/uninstall-plugin.php';

register_uninstall_hook(__FILE__, "spawning_ai_uninstall");