<?php
   /* 
       Copyright 2023 Spawning Inc
   
       Licensed under the Apache License, Version 2.0 (the "License");
       you may not use this file except in compliance with the License.
       You may obtain a copy of the License at:
   
           http://www.apache.org/licenses/LICENSE-2.0
   
       Unless required by applicable law or agreed to in writing, software
       distributed under the License is distributed on an "AS IS" BASIS,
       WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
       See the License for the specific language governing permissions and
       limitations under the License. 
       */
   
   // Plugin page content
   function spawning_ai_page()
   {
   
       // Load the options from the JSON file
       $json_file = plugin_dir_path(dirname(__FILE__)) . "config/ai_txt_options.json";
       $options = json_decode(file_get_contents($json_file), true);
   
       // Use WordPress's ABSPATH constant for a more secure directory path
       $ai_txt_enabled = file_exists(ABSPATH . "ai.txt");
   ?>
<div id="spawning-admin-panel" style="display: none;">
    <div class="wrapper">
        <div class="container">
            <div id="notification" style="opacity: 0; padding: 5px; border: 1px solid #CFD5FF; background-color: #CFD5FF; 
    height: 25px; line-height: 25px; text-align: center; transition: opacity 0.5s;">
                <!-- Success message will appear here -->
            </div>
            <div class="section headerWrapper">
                <div class="titleWrapper">
                    <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . "assets/header.svg"); ?>"
                        class="logo" />
                </div>
            </div>
            <div class="divider"></div>
            <div class="section card">
                <form method="post" id="aiForm">
                    <?php wp_nonce_field('spawning_handle_ai_form_action', 'ai_nonce'); ?>
                    <p>
                        <?php echo esc_html__(
                     "An ai.txt file will let AI miners know how to use your content. Use the toggles below to set your preferences.",
                     "spawning-ai"
                     ); ?>
                    </p>
                    <div class="selections">
                        <table id='ai-txt-table' class='options-table'>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Block ⛔️</th>
                                    <th>Allow ✔️</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                           // Loop through options and generate checkboxes dynamically
                           foreach ($options["options"] as $option) {
                               $file_path = sanitize_text_field($_SERVER["DOCUMENT_ROOT"]) . "/ai.txt";
                               $checked = false;
                               $optionValues = explode(",", $option["value"]);
                               $firstOptionValue = trim($optionValues[0]);
                           
                               $allowOptionValue = $options["allow"] . $firstOptionValue;
                           
                               if (file_exists($file_path) && strpos(file_get_contents($file_path), $allowOptionValue) !== false) {
                                   $checked = true;
                               }
                           ?>
                                <tr>
                                    <td><span><img style="vertical-align: middle;"
                                                src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . "assets/" . $option["icon"]); ?>" />&nbsp;
                                            <?php echo esc_html($option["label"]); ?></span>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" class="wppd-ui-toggle-opposite"
                                                name="<?php echo esc_attr($option["label"]); ?>_opposite" <?php if (!isset($_POST[$allowOptionValue]) && !$checked) {
                                    echo "checked";
                                    } ?>>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="checkbox" class="wppd-ui-toggle"
                                                name="<?php echo esc_attr($option["label"]); ?>" <?php if (isset($_POST[$allowOptionValue]) || $checked) {
                                    echo "checked";
                                    } ?>>
                                        </label>
                                    </td>
                                </tr>
                                <?php
                           }
                           ?>
                            </tbody>
                        </table>
                        <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            UIManager.toggleCheckboxStates();
                        });
                        </script>
                    </div>
                    <div class="consent-div">
                        <span for="ai-risk-checkbox"><?php echo esc_html__(
                     "By using the ai.txt generator, you agree to the ",
                     "spawning-ai"
                     ); ?>
                            <a class="viewLink" id="ai-txt-link" href="https://site.spawning.ai/spawning-ai-txt#tos"
                                target="_blank" rel="noreferrer">TERMS OF SERVICE ↗</a>
                        </span>
                    </div>
                    <button type="submit" name="ai_update_file" class="buttonSecondary create-hidden"
                        value="Update ai.txt" id="ai-update-button"
                        style="<?php echo esc_attr($ai_txt_enabled ? '' : 'display:none;'); ?>" disabled><?php echo esc_html__(
                  "Update ai.txt",
                  "spawning-ai"
                  ); ?></button>
                    <button type="submit" name="ai_create_file" class="buttonSecondary create-show"
                        value="Create ai.txt" id="ai-create-button"
                        style="<?php echo esc_attr(!$ai_txt_enabled ? '' : 'display:none;'); ?>"><?php echo esc_html__(
                  "Create ai.txt",
                  "spawning-ai"
                  ); ?></button>
                    <a class="viewLink preview-link create-hidden" id="ai-txt-link"
                        href="<?php echo esc_url(home_url("/ai.txt")); ?>" target="_blank" rel="noreferrer"
                        style="<?php echo esc_attr($ai_txt_enabled ? '' : 'display:none;'); ?>"><?php
echo sprintf(esc_html__('View your %s', 'spawning-ai'), "ai.txt");                  
?>
                        ↗</a>
                </form>
            </div>
            <!-- START: Separate section for CCBot and GPTBot -->
                <div class="section card">
                <form method="post" id="spoofingForm">
                    <?php wp_nonce_field('spawning_handle_spoofing_form_action', 'spoofing_nonce'); ?>
                    <p>
                        <?php echo esc_html__("Use the button below to toggle Chat GPT Spoofing.", "spawning-ai"); ?>
                    </p>
                    <div class="selections">
                    <button type="button" id="toggle-spoofing" class="buttonSecondary">
                        <?php echo get_option('spawning_trick_chat_gpt_enabled') === 'on' ? 'Disable Spoofing' : 'Enable Spoofing'; ?>
                    </button>
                    </div>
                </form>
                </div>
                <div class="section card">
                <form method="post" id="kudurruForm">
                    <?php wp_nonce_field('spawning_handle_kudurru_form_action', 'kudurru_nonce'); ?>
                    <div class="selections">
                    <h2><span>𒋧Kudurru </span><a class="titleLink" target="_blank" href="https://kudurru.ai">↗</a></h2> 
                        <span id="kudurru-blocks-count" class="blocks-count"></span>


                        <div class="button-container">

                        <a href="#TB_inline?width=600&height=200&inlineId=configureModal" class="thickbox button buttonSecondary" id="viewConfigureModal">Settings</a>
                        <div id="configureModal" style="display: none;">
                        <div class="api-key-input">
                                <label for="spawning-kudurru-api-key">Api-Key</label>
                                <input type="text" id="spawning-kudurru-api-key" name="spawning-kudurru-api-key" value="<?php echo get_option('spawning-kudurru-api-key'); ?>"/>
                                <button type="button" id="validate-api-key-button">Validate API Key</button>
                                <p id="api-key-validation-result"></p> <!-- Placeholder for the result message -->
                                <button type="button" id="toggle-kudurru" style="display: none;" class="buttonSecondary">
                                    <?php echo get_option('spawning_kudurru_enabled') === 'on' ? 'Disable Kudurru' : 'Enable Kudurru'; ?>
                                </button>
                                <div id="refreshing-page" style="display: none;"> Please Wait for updates to take effect...</div>
                        </div>
                        </div>

                        <?php if (get_option('spawning_kudurru_enabled') === 'on') : ?>

                            <?php 
                                // Get the API key from the options
                                $api_key = get_option('spawning-kudurru-api-key');
                                
                                if (!empty($api_key)) {
                                    $current_server = $_SERVER['HTTP_HOST'];
                                    $api_url = "https://api-xb2cbucfja-uc.a.run.app/get_intercepted_messages_count?domain={$current_server}";

                                    $response = wp_remote_get($api_url, [
                                        'headers' => [
                                            'accept' => 'application/json',
                                            'Authorization' => 'Bearer ' . $api_key,
                                        ]
                                    ]);

                                    if (is_wp_error($response)) {
                                        echo '<p class="intercepted-messages">No intercepted messages found.</p>';
                                    } else {
                                        $body = wp_remote_retrieve_body($response);
                                        $data = json_decode($body, true);

                                        if (isset($data['message_count'])) {
                                            echo '<p class="intercepted-messages">Total Intercepted Messages: ' . esc_html($data['message_count']) . '</p>';
                                        } else {
                                            echo '<p class="intercepted-messages">No intercepted messages found.</p>';
                                        }
                                    }
                                } else {
                                    echo '<p>API key not set.</p>';
                                }
                                $current_server = $_SERVER['HTTP_HOST'];
                                $image_url = "http://34.132.90.61/proxy?url=https://{$current_server}/wp-content/test.jpg";
                            
                                // Fetching image content using file_get_contents
                                $image_data = file_get_contents($image_url);
                            
                                // Encoding image content to base64
                                $base64 = base64_encode($image_data);
                            
                                // Embedding the image using data URL
                                echo '<div class="embedded-image-wrapper">';
                                echo '<img width="100px" height="100px" src="data:image/jpeg;base64,' . $base64 . '" alt="Test Image" />';
                                echo '</div>';
                            ?>
                            <!-- Button to open modal -->
                            <a href="#TB_inline?width=600&height=550&inlineId=blacklistModal" class="thickbox button buttonSecondary" id="viewBlacklistButton">View Blocklist</a>

                            <!-- Modal for displaying blacklisted IPs -->
                            <div id="blacklistModal" style="display: none;">
                                <h3>Blocklisted IP Addresses</h3>
                                <?php
                                $last_updated = get_transient('blacklist_last_updated');
                                if ($last_updated) {
                                    $last_updated_time = new DateTime($last_updated);
                                    $current_time = new DateTime(current_time('mysql'));
                                    $interval = $last_updated_time->diff($current_time);
                            
                                    echo '<p>Last updated: ' . $interval->format('%a days, %h hours, %i minutes ago') . '</p>';
                                } else {
                                    echo '<p>Last update time not available.</p>';
                                }
                            ?>
                                <div id="blacklistContent" class="spawning-grid-container">
                                    <?php
                                    $blacklisted_ips = spawning_get_blacklisted_ips();
                                    if (!empty($blacklisted_ips)) {
                                        foreach ($blacklisted_ips as $ip) {
                                            echo '<div class="spawning-grid-item"><a href="https://ipinfo.io/' . esc_attr($ip) . '" target="_blank">' . esc_html($ip) . '</a></div>';
                                        }
                                    } else {
                                        echo '<p>No blocklisted IPs found.</p>';
                                    }
                                    ?>
                        </div>

                            </div>
                        <?php endif; ?>
                        </div>

                    </div>
                </form>

                </div>
                <div class="section card">

                <form method="post" id="robotsForm">
                    <?php wp_nonce_field('spawning_handle_robots_form_action', 'robots_nonce'); ?>
                    <p>
                        <?php echo esc_html__("Use the options below to modify your site's robots.txt file.", "spawning-ai"); ?>
                    </p>
                    <div class="selections">
                        <div class="robots-txt-options">
                            <div class="checkbox-option">
                                <label>
                                    <input type="checkbox" name="block_ccbot"
                                        <?php checked(get_option('spawning_block_ccbot'), 'on'); ?> />
                                    Opt out of Common Crawl's CCBot
                                    <span class="info-icon"
                                        data-tooltip="By checking this, you'll add a directive to the robots.txt file that instructs the Common Crawl bot (CCBot) not to crawl your site.">ⓘ</span>
                                </label>
                            </div>
                            <div class="checkbox-option">
                                <label>
                                    <input type="checkbox" name="block_gptbot"
                                        <?php checked(get_option('spawning_block_gptbot'), 'on'); ?> />
                                    Opt out of OpenAI's GPTbot
                                    <span class="info-icon"
                                        data-tooltip="By checking this, you'll add a directive to the robots.txt file that instructs OpenAI's GPTbot not to crawl your site.">ⓘ</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="robots_update_file" id="robots-update-file"
                        class="buttonSecondary">Modify
                        Robots.txt</button>
                    <a class="viewLink preview-link" id="ai-txt-link"
                        href="<?php echo esc_url(home_url("/robots.txt")); ?>" target="_blank" rel="noreferrer">
                        <?php
                        echo sprintf(esc_html__('View your %s', 'spawning-ai'), "robots.txt");
                        ?> ↗
                    </a>
                </form>
            </div>

            <!-- END: Separate section for CCBot and GPTBot -->
            <div class="divider"></div>
            <div class="footer-links">
                <p class="link-row">
                    <?php
                  // Get the current site's URL
                  $site_url = esc_url(home_url('/'));
                  
                  // Encode the site URL and path for use in the file download link
                  $encoded_site_url = rawurlencode($site_url . 'ai.txt');
                  
                  // File download link
                  $file_download_link = $site_url . 'ai.txt';
                  
                  ?>
                    <a class="ccLink" href="https://site.spawning.ai/spawning-ai-txt#faq" target="_blank"
                        rel="noreferrer">
                        <?php echo esc_html(__("FAQ", "spawning-ai")); ?>
                    </a>
                    <a class="ccLink" href="https://spawning.ai" target="_blank" rel="noreferrer">
                        <?php echo esc_html(__("About Us", "spawning-ai")); ?>
                    </a>
                    <a class="ccLink" href="https://site.spawning.ai/contact?ctx=wp" target="_blank" rel="noreferrer">
                        <?php echo esc_html(__("Help", "spawning-ai")); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<script>
window.onload = function() {
    UIManager.showAdminPanel();
    UIManager.handleFormSubmission();
    UIManager.handleRobotsFormSubmission();
    UIManager.handleSpoofingFormSubmission();
    UIManager.handleKudurruFormSubmission();
    UIManager.validateApiKey();
    UIManager.fetchAndDisplayKudurruBlocks();

};

document.addEventListener("DOMContentLoaded", function() {
    UIManager.enableUpdateButton();
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('robotsForm');
    const saveButton = form.querySelector('.buttonSecondary');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');

    // Store the initial state of checkboxes
    const initialState = Array.from(checkboxes).map(checkbox => checkbox.checked);

    form.addEventListener('change', function() {
        let hasChanged = false;

        checkboxes.forEach((checkbox, index) => {
            if (checkbox.checked !== initialState[index]) {
                hasChanged = true;
            }
        });
    });
});

jQuery(document).ready(function($) {
    $('.info-icon').tooltip({
        items: "[data-tooltip]",
        content: function() {
            return $(this).attr("data-tooltip");
        }
    });
});
</script>
<?php
   }
?>