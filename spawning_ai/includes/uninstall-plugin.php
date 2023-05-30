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
    // Define the path of the ai.txt file.
    $file_path = $_SERVER["DOCUMENT_ROOT"] . "/ai.txt";

    // Check if the ai.txt file exists.
    if (file_exists($file_path)) {
        // If the file exists, delete it.
        unlink($file_path);
    }
}
