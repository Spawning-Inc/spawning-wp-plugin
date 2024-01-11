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
    $nonce = isset($_POST['_wpnonce']) ? sanitize_key($_POST['_wpnonce']) : '';
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
    $nonce = isset($_POST['_wpnonce']) ? sanitize_key($_POST['_wpnonce']) : '';
    if (!wp_verify_nonce($nonce, 'spawning_handle_robots_form_action')) {
        echo json_encode(['message' => 'Nonce verification failed.', 'status' => 'error']);
        wp_die();
    }

    try {
        // Parse the serialized form data into an array
        if (isset($_POST['form'])) {
            parse_str(sanitize_text_field($_POST['form']), $parsed_form_data);
        } else {
            $parsed_form_data = array();
        }

        // Define the expected keys
        $expected_keys = array('block_ccbot', 'block_gptbot');

        // Filter the parsed form data using the expected keys
        $form_data = array_intersect_key($parsed_form_data, array_flip($expected_keys));

        $robots_txt_path = ABSPATH . "robots.txt";
        $robots_content = file_exists($robots_txt_path) ? file_get_contents($robots_txt_path) : "";

        // Save the block_ccbot and block_gptbot checkbox states to WordPress options
        update_option('spawning_block_ccbot', isset($form_data['block_ccbot']) ? 'on' : 'off');
        update_option('spawning_block_gptbot', isset($form_data['block_gptbot']) ? 'on' : 'off');
        
        
        if (get_option('spawning_block_ccbot') === 'on' && strpos($robots_content, "User-agent: CCBot") === false) {
            $robots_content .= "\nUser-agent: CCBot\nDisallow: /\n";
        } elseif (get_option('spawning_block_ccbot') !== 'on') {
            $robots_content = preg_replace("/\n?User-agent: CCBot\nDisallow: \/\n?/", "\n", $robots_content);
            // Remove potential double line breaks
            $robots_content = str_replace("\n\n", "\n", $robots_content);
        }
        
        if (get_option('spawning_block_gptbot') === 'on' && strpos($robots_content, "User-agent: GPTBot") === false) {
            $robots_content .= "\nUser-agent: GPTBot\nDisallow: /\n";
        } elseif (get_option('spawning_block_gptbot') !== 'on') {
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

function spawning_modify_robots_txt($output, $public) {
    if (get_option('spawning_block_ccbot') === 'on') {
        $output .= "\nUser-agent: CCBot\nDisallow: /\n";
    }
    if (get_option('spawning_block_gptbot') === 'on') {
        $output .= "\nUser-agent: GPTBot\nDisallow: /\n";
    }
    return $output;
}


if (!defined('ABSPATH')) {
    exit;
}

function spawning_handle_spoofing_form() {
    // Verify nonce
    $nonce = isset($_POST['spoofing_nonce']) ? sanitize_key($_POST['spoofing_nonce']) : '';
    if (!wp_verify_nonce($nonce, 'spawning_handle_spoofing_form_action')) {
        echo json_encode(['message' => 'Nonce verification failed.', 'status' => 'error']);
        wp_die();
    }

    $current_status = get_option('spawning_trick_chat_gpt_enabled', 'off');
    $new_status = $current_status === 'on' ? 'off' : 'on';
    update_option('spawning_trick_chat_gpt_enabled', $new_status);

    echo json_encode(['status' => 'success', 'new_status' => $new_status]);
    wp_die();
}

function spawning_trick_chatgpt() {
    if (get_option('spawning_trick_chat_gpt_enabled', 'off') === 'off') {
        return;
    }

    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);  // Convert user agent to lowercase
    
    // Check for ChatGPT in the user agent and output custom text if found
    if (stripos($user_agent, 'chatgpt') !== false || stripos($user_agent, 'gptbot') !== false) {
        // Disable template rendering
        define('WP_USE_THEMES', false);
        
        // Load the txt_options.php config file
        $config_file_path = plugin_dir_path(__FILE__) . '../config/txt_options.php';
        if (file_exists($config_file_path)) {
            include_once $config_file_path;
        }
        
        // Check if $txt_config is set and if 'gpt_cloak' key exists in the array
        if (isset($txt_config) && array_key_exists('gpt_cloak', $txt_config)) {
            // Use the value of 'gpt_cloak' key as custom text
            $custom_text = $txt_config['gpt_cloak'];
        } else {
            // Handle error (e.g., log it, display a default message, etc.)
            error_log('Error: Config file not found or gpt_cloak key not set in txt_options.php');
            $custom_text = '<html>Error fetching custom text</html>';
        }

        // Output custom text
        echo $custom_text;

        // Stop further processing
        exit;
    }
}

function spawning_handle_kudurru_form() {
    // Verify nonce
    $nonce = isset($_POST['kudurru_nonce']) ? sanitize_key($_POST['kudurru_nonce']) : '';
    if (!wp_verify_nonce($nonce, 'spawning_handle_kudurru_form_action')) {
        echo json_encode(['message' => 'Nonce verification failed.', 'status' => 'error']);
        wp_die();
    }

    update_option('kudurru_full_node', isset($form_data['block_ccbot']) ? 'on' : 'off');

    $current_status = get_option('spawning_kudurru_enabled', 'off');
    $new_status = $current_status === 'on' ? 'off' : 'on';

    // Retrieve and sanitize the API key
    $api_key = isset($_POST['api_key']) ? sanitize_text_field($_POST['api_key']) : '';

    update_option('spawning-kudurru-api-key', $api_key);

    $rule = "\n# Begin Image Redirector with Blacklist\n<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteRule ^wp-content/(.*\.(jpg|jpeg|png|gif))$ /index.php?image_redirect_request=$1 [L]\n</IfModule>\n# End Image Redirector with Blacklist\n";
    $htaccess_file = ABSPATH . '.htaccess';

    if ($new_status === 'on') {
        spawning_get_blacklisted_ips();

        if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
            file_put_contents($htaccess_file, $rule);  // Remove FILE_APPEND flag to overwrite the file
        }
    }
    else {
        if (file_exists($htaccess_file) && is_writable($htaccess_file) && strpos(file_get_contents($htaccess_file), '# Begin Image Redirector with Blacklist')) {
            $contents = file_get_contents($htaccess_file);
            $contents = str_replace($rule, '', $contents);
            file_put_contents($htaccess_file, $contents);
        }
    }

    update_option('spawning_kudurru_enabled', $new_status);

    echo json_encode(['status' => 'success', 'new_status' => $new_status]);
    wp_die();
}

function spawning_check_image_request() {
    if (get_option('spawning_kudurru_enabled') === 'on') {
        if (preg_match('/.*\.(jpg|jpeg|png|gif)$/i', $_SERVER['REQUEST_URI'])) {
            spawning_redirect_images_to_api();
            exit; // Exit after handling image request
        } else {
            // If it's not an image request, continue with normal WordPress processing
            return;
        }
    }
    // If Kudurru is disabled, continue with normal WordPress processing
    return;
}

function spawning_get_blacklisted_ips() {
    // Retrieve the Bearer API key from the database
    $bearer_api_key = get_option('spawning-kudurru-api-key', '');
    if (!$bearer_api_key) {
        error_log('No Bearer API key found in the database.');
        return [];
    }

    // Check if the blacklist is already cached
    $blacklisted_ips = get_transient('blacklisted_ips_cache');

    // If not, fetch and cache it
    if (false === $blacklisted_ips) {
        $response = wp_remote_get('https://api-xb2cbucfja-uc.a.run.app/get_blocklist', [
            'headers' => [
                'accept' => 'application/json',
                'Authorization' => 'Bearer ' . $bearer_api_key, // Use the Bearer token from the database
            ]
        ]);

        if (is_wp_error($response)) {
            error_log(print_r($response, true)); // Log the error for debugging
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Directly use the blacklist_ips array from the response
        $blacklisted_ips = $data['blacklist_ips'] ?? []; // Null coalescing operator for safety

        // Cache for 15 minutes
        set_transient('blacklisted_ips_cache', $blacklisted_ips, 60 * MINUTE_IN_SECONDS);

        // Set the current timestamp as the last updated time
        $current_timestamp = current_time('mysql'); // Get the current time in MySQL format
        set_transient('blacklist_last_updated', $current_timestamp, 60 * MINUTE_IN_SECONDS);
    }

    return $blacklisted_ips;
}

function spawning_image_request_query_vars($vars) {
    $vars[] = 'image_redirect_request';
    return $vars;
}

function spawning_redirect_images_to_api() {
    if (preg_match('/.*\.(jpg|jpeg|png|gif)$/i', $_SERVER['REQUEST_URI'], $matches)) {
        $blacklisted_ips = spawning_get_blacklisted_ips();
        $full_node_enabled = get_option('kudurru_full_node') === 'on';

        if ($full_node_enabled) {

            $request = [
                'ip' => $_SERVER['REMOTE_ADDR'],
                'image' => $_SERVER['REQUEST_URI'],
                'timestamp' => current_time('mysql'),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'referrer' => $_SERVER['HTTP_REFERER']
            ];
            $api_base_url = 'https://api-xb2cbucfja-uc.a.run.app/node_submission';
            $domain_name = $_SERVER['SERVER_NAME'];
            $message = "Incoming request from IP: {$request['ip']} for image: {$request['image']} at {$request['timestamp']}";
            $api_url = $api_base_url . '?domain_name=' . urlencode($domain_name) . '&message=' . urlencode($message);
            wp_remote_post($api_url, [
                'headers' => [
                    'accept' => 'application/json'
                ]
            ]);
        }

        if (in_array($_SERVER['REMOTE_ADDR'], $blacklisted_ips)) {
            $blocked_requests = get_option('blocked_requests', []);
            $blocked_request = [
                'ip' => $_SERVER['REMOTE_ADDR'],
                'image' => $_SERVER['REQUEST_URI'],
                'timestamp' => current_time('mysql'),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'referrer' => $_SERVER['HTTP_REFERER']
            ];
            $blocked_requests[] = $blocked_request;
            update_option('blocked_requests', $blocked_requests);

            $api_base_url = 'https://api-xb2cbucfja-uc.a.run.app/intercepted';
            $domain_name = $_SERVER['SERVER_NAME'];
            $message = "Blocked request from IP: {$blocked_request['ip']} for image: {$blocked_request['image']} at {$blocked_request['timestamp']}";
            $api_url = $api_base_url . '?domain_name=' . urlencode($domain_name) . '&message=' . urlencode($message);

            $response = wp_remote_post($api_url, [
                'headers' => [
                    'accept' => 'application/json'
                ]
            ]);

            // TODO: expose custom image uploads?

            $custom_image_data = get_option('custom_redirect_image_data');
            if ($custom_image_data) {
                header("Content-Type: image/jpeg");
                echo base64_decode($custom_image_data);
                exit;
            } else {
                header('Location: https://a-us.storyblok.com/f/1012441/1155x1155/0c9dae5d49/qr-code.png');
                exit;
            }
        } else {
            $image_path = ABSPATH . $_SERVER['REQUEST_URI'];
            if (file_exists($image_path)) {
                $image_info = getimagesize($image_path);
                header("Content-Type: " . $image_info['mime']);
                readfile($image_path);
                exit;
            } else {
                header('HTTP/1.0 404 Not Found');
                exit;
            }
        }
    }

    wp_die();
}

function spawning_handle_full_node_checkbox() {
    // Verify nonce
    $nonce = isset($_POST['fullNodeNonce']) ? sanitize_key($_POST['fullNodeNonce']) : '';
    if (!wp_verify_nonce($nonce, 'kudurru_full_node_checkbox_action')) {
        echo json_encode(['message' => 'Nonce verification failed.', 'status' => 'error']);
        wp_die();
    }

    // Check if 'full_node' checkbox was sent in the POST data
    $full_node_state = isset($_POST['full_node']) ? sanitize_text_field($_POST['full_node']) : 'off';

    // Update the 'kudurru_full_node' option based on the checkbox state
    update_option('kudurru_full_node', $full_node_state);

    // Prepare response data
    $response = [
        'status' => 'success',
        'message' => 'Full node state updated successfully',
        'new_state' => $full_node_state
    ];

    echo json_encode($response);
    wp_die();
}

function spawning_init_kudurru_hooks() {
    if (get_option('spawning_kudurru_enabled') === 'on') {
        add_filter('query_vars', 'spawning_image_request_query_vars');
        add_action('template_redirect', 'spawning_check_image_request');
        add_action('init', 'spawning_redirect_images_to_api');
        exit;
    }
}
// add_action('init', 'spawning_init_kudurru_hooks');
add_action('template_redirect', 'spawning_trick_chatgpt');
add_action('wp_ajax_handle_ai_form', 'spawning_ai_handle_ai_form');
add_action('wp_ajax_handle_robots_form', 'spawning_handle_robots_form');
add_action('wp_ajax_handle_spoofing_form', 'spawning_handle_spoofing_form');
add_action('wp_ajax_handle_kudurru_form', 'spawning_handle_kudurru_form');
add_action('wp_ajax_handle_full_node_checkbox', 'spawning_handle_full_node_checkbox');
add_filter('robots_txt', 'spawning_modify_robots_txt', 10, 2);

?>