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
function spawning_ai_page() {

    // Load the options from the JSON file
    $json_file = plugin_dir_path(dirname(__FILE__)) . "config/ai_txt_options.json";
    $options = json_decode(file_get_contents($json_file), true);
    
    // Use WordPress's ABSPATH constant for a more secure directory path
    $ai_txt_enabled = file_exists(ABSPATH . "ai.txt");
    ?>
            
    <div id="spawning-admin-panel" style="display: none;">
        <div class="wrapper">
            <div class="container">

                <div class="section headerWrapper">
                    <div class="titleWrapper">
                        <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . "assets/header.svg"); ?>" class="logo" />
                    </div>
                </div>

                <div class="divider"></div>

                <div class="section card">
                    <form method="post" id="aiForm">
                        <?php wp_nonce_field('spawning_handle_ai_form_action'); ?>
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
                                        $file_path = $_SERVER["DOCUMENT_ROOT"] . "/ai.txt";
                                        $checked = false;
                                        $optionValues = explode(",", $option["value"]);
                                        $firstOptionValue = trim($optionValues[0]);
                                    
                                        $allowOptionValue = $options["allow"] . $firstOptionValue;
                                    
                                        if (file_exists($file_path) && strpos(file_get_contents($file_path), $allowOptionValue) !== false) {
                                            $checked = true;
                                        }
                                        ?>
                                    <tr>
                                    <td><span><img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . "assets/" . $option["icon"]); ?>" />&nbsp;
                                    <?php echo esc_html($option["label"]); ?></span></td>
                                    <td>
                                        <label>
                                        <input type="checkbox" class="wppd-ui-toggle-opposite" name="<?php echo esc_attr($option["label"]); ?>_opposite"
                                        <?php if (!isset($_POST[$allowOptionValue]) && !$checked) {
                                            echo "checked";
                                        } ?>>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                        <input type="checkbox" class="wppd-ui-toggle" name="<?php echo esc_attr($option["label"]); ?>"
                                        <?php if (isset($_POST[$allowOptionValue]) || $checked) {
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
                                document.addEventListener("DOMContentLoaded", function() { UIManager.toggleCheckboxStates(); });
                            </script>
                        </div>
                        <div class="consent-div">    
                            <span for="ai-risk-checkbox"><?php echo esc_html__(
                                "By using the ai.txt generator, you agree to the ",
                                "spawning-ai"
                            ); ?>
                            <a
                            class="viewLink"
                            id="ai-txt-link"
                            href="https://site.spawning.ai/spawning-ai-txt#tos"
                            target="_blank"
                            rel="noreferrer"
                            >TERMS OF SERVICE ↗</a>
                            </span>
                        </div>
                        <button type="submit" 
                            name="ai_update_file" 
                            class="buttonSecondary create-hidden" 
                            value="Update ai.txt" 
                            id="ai-update-button"
                            style="<?php echo esc_attr($ai_txt_enabled ? '' : 'display:none;'); ?>" 
                            disabled><?php echo esc_html__(
                            "Update ai.txt",
                            "spawning-ai"
                        ); ?></button>
                        <button type="submit" 
                            name="ai_create_file" 
                            class="buttonSecondary create-show" 
                            value="Create ai.txt" 
                            id="ai-create-button" 
                            style="<?php echo esc_attr(!$ai_txt_enabled ? '' : 'display:none;'); ?>"
                            ><?php echo esc_html__(
                            "Create ai.txt",
                            "spawning-ai"
                        ); ?></button>
                        <a
                            class="viewLink preview-link create-hidden"
                            id="ai-txt-link"
                            href="<?php echo esc_url(home_url("/ai.txt")); ?>"
                            target="_blank"
                            rel="noreferrer"
                            style="<?php echo esc_attr($ai_txt_enabled ? '' : 'display:none;'); ?>"
                            ><?php 
                                $home_url = home_url("/ai.txt");
                                $parsed_url = parse_url($home_url);
                                $home_url_without_http = $parsed_url['host'] . $parsed_url['path'];
                                echo esc_html(__("View " . $home_url_without_http, "spawning-ai")); 
                                ?> ↗</a>
                    </form>

                    <?php

                    // LinkedIn share URL

                        echo '<div class="share-links">';
                        $linkedin_share_url = 'https://www.linkedin.com/sharing/share-offsite/?url=https://site.spawning.ai/spawning-ai-txt';

                        // LinkedIn logo image path
                        $linkedin_logo_path = plugin_dir_url(dirname(__FILE__)) . "assets/linkedin.svg";

                        // Output the LinkedIn share button with the logo
                        echo '<a href="' . esc_url($linkedin_share_url) . '" target="_blank" rel="noopener noreferrer" style="' . esc_attr($ai_txt_enabled ? '' : 'display:none;') . '">';
                        echo '<img src="' . esc_url($linkedin_logo_path) . '" alt="LinkedIn" class="share-logo"/>';
                        echo '</a>';

                        // Get the current site's URL
                        $site_url = esc_url(home_url('/'));

                        // Encode the site URL and path for use in the Twitter share URL
                        $encoded_site_url = rawurlencode($site_url . 'ai.txt');

                        $twitter_msg = rawurlencode('I just installed ai.txt, a tool that sets permissions for what AI miners can and can\'t use on my site. Make one at https://site.spawning.ai/spawning-ai-txt. @spawning_');

                        // Twitter share URL
                        $twitter_share_url = 'https://twitter.com/intent/tweet?url=' . $encoded_site_url . '&text=' . $twitter_msg;

                        // Twitter logo image path
                        $twitter_logo_path = plugin_dir_url(dirname(__FILE__)) . "assets/twitter.svg";

                        // Output the Twitter share button with the logo
                        echo '<a href="' . esc_url($twitter_share_url) . '" target="_blank" rel="noopener noreferrer" style="' . esc_attr($ai_txt_enabled ? '' : 'display:none;') . '">';
                        echo '<img src="' . esc_url($twitter_logo_path) . '" alt="Twitter" class="share-logo"/>';
                        echo '</a>';
                        echo '</div>';
                        ?>

                </div>

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

                    // Output the file download link
                    echo '<a href="' . esc_url($file_download_link) . '" download class="create-hidden" style="' . esc_attr($ai_txt_enabled ? '' : 'display:none;') . '">';
                    echo '<img src="' . esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/download.svg') . '" alt="Download" /><span>Download</span>';
                    echo '</a>';
                    ?>    
                    <a
                        class="ccLink"
                        href="https://site.spawning.ai/spawning-ai-txt#faq"
                        target="_blank"
                        rel="noreferrer"
                        > 
                    <?php echo esc_html(__("FAQ", "spawning-ai")); ?>
                    </a>
                    <a
                    class="ccLink"
                    href="https://spawning.ai"
                    target="_blank"
                    rel="noreferrer"
                    >
                    <?php echo esc_html(__("About Us", "spawning-ai")); ?>
                    </a>
                   <a
                        class="ccLink"
                        href="https://site.spawning.ai/contact?ctx=wp"
                        target="_blank"
                        rel="noreferrer"
                        > 
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
    };
    
    document.addEventListener("DOMContentLoaded", function() { 
        UIManager.enableUpdateButton();
    });
    
    </script>

<?php
}
?>
