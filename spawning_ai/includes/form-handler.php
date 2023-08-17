<?php

/*
    Copyright 2023 Spawning Inc

    Licensed under the Apache License, Version 2.0 (the "License");
    You may not use this file except in compliance with the License.
    You may obtain a copy of the License at:

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
*/

function spawning_ai_handle_ai_form() {
    // Verify nonce
    $nonce = $_POST['_wpnonce'] ?? '';
    if (isset($_POST['robots_update_file']) && !wp_verify_nonce($nonce, 'spawning_handle_robots_form_action')) {
        echo json_encode(['message' => 'Nonce verification failed.', 'status' => 'error']);
        wp_die();
    } elseif (!isset($_POST['robots_update_file']) && !wp_verify_nonce($nonce, 'spawning_handle_ai_form_action')) {
        echo json_encode(['message' => 'Nonce verification failed.', 'status' => 'error']);
        wp_die();
    }

    try {
        $form_data = [];
        parse_str(sanitize_text_field($_POST['form']), $form_data);

    

    $json_file = plugin_dir_path(dirname(__FILE__)) . "config/ai_txt_options.json";
    $options = json_decode(sanitize_textarea_field(file_get_contents($json_file)), true);

    if (!$options) {
        throw new Exception('Invalid options data.');
    }

    $file_content = spawning_construct_file_content($options, $form_data);
    
    $file_path = ABSPATH . "ai.txt";
    file_put_contents($file_path, sanitize_textarea_field($file_content));

    $message = isset($form_data["ai_create_file"]) ? __("ai.txt successfully created!", "spawning-ai") : __("ai.txt successfully updated!", "spawning-ai");
    $response = ['message' => $message];

    } catch (Exception $e) {
        $response = ['message' => sanitize_text_field($e->getMessage()), 'status' => 'error'];
    }

    echo json_encode($response);
    wp_die();
}

function spawning_construct_file_content($options, $form_data) {
    $file_content = sanitize_textarea_field($options["pre"]) . "/\n" . sanitize_textarea_field($options["userAgent"]) . "/\n";
    $global_setting = sanitize_textarea_field($options["disallow"]) . "/\n";

    $allSet = array_reduce($options["options"], function($carry, $option) use ($form_data) {
        return $carry && !isset($form_data[$option["label"]]);
    }, true);

    if ($allSet) {
        $file_content .= $options["globalDisallow"];
    } else {
        foreach ($options["options"] as $option) {
            if (isset($form_data[$option["label"]])) {
                $values = array_map('trim', explode(",", $option["value"]));
                foreach ($values as $value) {
                    $file_content .= $options["allow"] . $value . "\n";
                }
                if ($option["globalFlag"]) {
                    $global_setting = $options["allow"] . "/\n";
                }
            } else {
                $values = array_map('trim', explode(",", $option["value"]));
                foreach ($values as $value) {
                    $file_content .= $options["disallow"] . $value . "\n";
                }
            }
        }
        $file_content .= $global_setting;
    }

    return $file_content . $options["post"];
}

function spawning_handle_robots_form() {
    // Verify nonce
    $nonce = $_POST['_wpnonce'] ?? '';
    if (!wp_verify_nonce($nonce, 'spawning_handle_robots_form_action')) {
        echo json_encode(['message' => 'Nonce verification failed.', 'status' => 'error']);
        wp_die();
    }

    try {
        // Parse the serialized form data into an array
        parse_str($_POST['form'], $parsed_form_data);

        // Merge the parsed form data with the rest of the $_POST data
        $form_data = array_merge($_POST, $parsed_form_data);

        $robots_txt_path = ABSPATH . "robots.txt";
        $robots_content = file_exists($robots_txt_path) ? file_get_contents($robots_txt_path) : "";

        // Save the block_ccbot and block_gptbot checkbox states to WordPress options
        update_option('block_ccbot', isset($form_data['block_ccbot']) ? 'on' : 'off');
        update_option('block_gptbot', isset($form_data['block_gptbot']) ? 'on' : 'off');
        
        if (get_option('block_ccbot') === 'on' && strpos($robots_content, "User-agent: CCBot") === false) {
            $robots_content .= "\nUser-agent: CCBot\nDisallow: /\n";
        } elseif (get_option('block_ccbot') !== 'on') {
            $robots_content = preg_replace("/\n?User-agent: CCBot\nDisallow: \/\n?/", "\n", $robots_content);
            // Remove potential double line breaks
            $robots_content = str_replace("\n\n", "\n", $robots_content);
        }
        
        if (get_option('block_gptbot') === 'on' && strpos($robots_content, "User-agent: GPTBot") === false) {
            $robots_content .= "\nUser-agent: GPTBot\nDisallow: /\n";
        } elseif (get_option('block_gptbot') !== 'on') {
            $robots_content = preg_replace("/\n?User-agent: GPTBot\nDisallow: \/\n?/", "\n", $robots_content);
            // Remove potential double line breaks
            $robots_content = str_replace("\n\n", "\n", $robots_content);
        }
        

        file_put_contents($robots_txt_path, $robots_content);

        // Combine the debug logs into a single message
        $debug_logs = "Form Data: " . print_r($form_data, true) . "\n\nRobots Content:\n" . $robots_content;
        $response = ['message' => $debug_logs];

    } catch (Exception $e) {
        $response = ['message' => sanitize_text_field($e->getMessage()), 'status' => 'error'];
    }

    echo json_encode($response);
    wp_die();
}

function modify_robots_txt($output, $public) {
    if (get_option('block_ccbot') === 'on') {
        $output .= "\nUser-agent: CCBot\nDisallow: /\n";
    }
    if (get_option('block_gptbot') === 'on') {
        $output .= "\nUser-agent: GPTBot\nDisallow: /\n";
    }
    return $output;
}


if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_handle_ai_form', 'spawning_ai_handle_ai_form');
add_action('wp_ajax_handle_robots_form', 'spawning_handle_robots_form');
add_filter('robots_txt', 'modify_robots_txt', 10, 2);

?>