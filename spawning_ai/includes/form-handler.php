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
 * Handle form submissions from the AI form.
 * This function is hooked into the WordPress AJAX system.
 */
function handle_ai_form() {
    try {
        // Parse the form data from the serialized string.
        parse_str($_POST['form'], $form_data); 

        // Load the AI options from the JSON file.
        $json_file = plugin_dir_path(dirname(__FILE__)) . "config/ai_txt_options.json";
        $options = json_decode(file_get_contents($json_file), true);

        // Begin constructing the file content.
        $file_content = $options["pre"];
        $file_content .= $options["userAgent"];
        $global_setting = $options["disallow"] . "/\n";

        // Determine whether all options are set.
        $allSet = true;
        foreach ($options["options"] as $option) {
            if (isset($form_data[$option["label"]])) {
                $allSet = false;
                break;
            }
        }

        // If all options are set, apply the global disallow rule.
        if ($allSet) {
            $file_content .= $options["globalDisallow"];
        } else {
            // If not all options are set, iterate over each one.

            // First, write all the allowed values.
            foreach ($options["options"] as $option) {
                if (isset($form_data[$option["label"]])) {
                    $values = explode(",", $option["value"]);
                    foreach ($values as $value) {
                        $value = trim($value); // Remove any whitespace.
                        $file_content .= $options["allow"] . $value . "\n";
                    }
                    if ($option["globalFlag"] == true) {
                        $global_setting = $options["allow"] . "/\n";
                    }
                }
            }

            // Then, write all the disallowed values.
            foreach ($options["options"] as $option) {
                if (!isset($form_data[$option["label"]])) {
                    $values = explode(",", $option["value"]);
                    foreach ($values as $value) {
                        $value = trim($value); // Remove any whitespace.
                        $file_content .= $options["disallow"] . $value . "\n";
                    }
                }
            }

            // Finally, apply the global setting.
            $file_content .= $global_setting;
        }

        // Add any post-content options.
        $file_content .= $options["post"];

        // Write the content to the AI.txt file in the server's root directory.
        $file_path = $_SERVER["DOCUMENT_ROOT"] . "/ai.txt";
        $written = file_put_contents($file_path, $file_content);

        // Construct a response message based on the form data.
        $message = "";
        if (isset($form_data["ai_create_file"])) {
            $message = __("ai.txt successfully created!", "spawning-ai");
        } elseif (isset($form_data["ai_update_file"])) {
            $message = __("ai.txt successfully updated!", "spawning-ai");
        }

        // Prepare the AJAX response.
        $response = array(
            'message' => $message,
        );

    } catch (Exception $e) {
        // If an error occurred, prepare an error response.
        $response = array(
            'message' => $e->getMessage(),
            'status' => 'error'
        );
    }

    // Output the AJAX response.
    echo json_encode($response);

    // Always call wp_die() at the end of an AJAX function to ensure a proper response.
    wp_die(); 
}

// Hook our function into the AJAX system on the wp instance.
add_action('wp_ajax_handle_ai_form', 'handle_ai_form');