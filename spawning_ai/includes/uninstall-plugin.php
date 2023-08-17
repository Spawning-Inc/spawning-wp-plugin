<?php

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

/**
 * Uninstall function for the Spawning AI plugin.
 *
 * This function is typically triggered when the plugin is deactivated and deleted.
 * It removes the ai.txt file from the server's root directory.
 */
function spawning_ai_uninstall()
{
    // Define the path of the ai.txt file using ABSPATH.
    $ai_file_path = ABSPATH . "ai.txt";

    // Check if the ai.txt file exists.
    if (file_exists($ai_file_path)) {
        // If the file exists, delete it.
        unlink($ai_file_path);
    }

    // Path to the robots.txt file.
    $robots_file_path = ABSPATH . "robots.txt";

    // Check if the robots.txt file exists.
    if (file_exists($robots_file_path)) {

        // Remove the block_ccbot and block_gptbot options
        delete_option('block_ccbot');
        delete_option('block_gptbot');

        // Get the contents of the robots.txt file.
        $robots_content = file_get_contents($robots_file_path);

        // Remove any directives related to CCBot and GPTBot added by the plugin.
        $robots_content = preg_replace("/\n?User-agent: CCBot\nDisallow: \/\n?/", "\n", $robots_content);
        $robots_content = preg_replace("/\n?User-agent: GPTBot\nDisallow: \/\n?/", "\n", $robots_content);
        
        // Remove potential double line breaks.
        $robots_content = str_replace("\n\n", "\n", $robots_content);

        // Save the modified content back to the robots.txt file.
        file_put_contents($robots_file_path, $robots_content);
    }
}