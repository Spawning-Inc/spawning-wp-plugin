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
            <div class="section card">
    <form method="post" id="kudurruForm">
        <?php wp_nonce_field('spawning_handle_kudurru_form_action', 'kudurru_nonce'); ?>
        <input type="hidden" name="activate_kudurru" value="1">
        <button type="submit" class="buttonSecondary" id="activate-kudurru">Activate Kudurru (Beta)</button>
    </form>
    <!-- Detailed View (Hidden by default) -->
    <div id="spawning-admin-panel">
                <?php
                if (get_option('spawning_kudurru_hooks_active') === '1') :

                    $blocked_requests = get_option('blocked_requests', []);
            
                    // Prepare data for charts
                    $timestamps = [];
                    $images = [];
                    $weekly_data = [];
                    $hourly_labels = [];
                    $hourly_data = [];

                    // Get the last 7 days including today
                    for ($i = 0; $i < 7; $i++) {
                        $weekly_data[date('Y-m-d', strtotime("-$i days"))] = 0;
                    }

                    // Get data for the last 24 hours
                    for ($i = 0; $i < 24; $i++) {
                        $hourLabel = date('Y-m-d H:i', strtotime("-$i hours"));
                        $hourly_labels[] = $hourLabel;
                        $hourly_data[$hourLabel] = 0;
                    }

                    foreach ($blocked_requests as $request) {
                        $date = date('Y-m-d H:i', strtotime($request['timestamp']));
                        if (isset($hourly_data[$date])) {
                            $hourly_data[$date]++;
                        }

                        if (isset($weekly_data[date('Y-m-d', strtotime($request['timestamp']))])) {
                            $weekly_data[date('Y-m-d', strtotime($request['timestamp']))]++;
                        }
                        
                        $images[] = basename($request['image']); // Extract only the image name
                    }

                    $images_counted = array_count_values($images);

                    // Display canvas for the charts
                    echo '<h1>Blocked Requests Charts</h1>';
                    echo '<div style="display: flex; justify-content: space-between;">'; // Wrapper div for the 3 charts
                    echo '<div style="width: 32%;"><h2>Requests Over Time</h2><canvas id="blockedRequestsTimeChart"></canvas></div>';
                    echo '<div style="width: 32%;"><h2>Image URLs Popularity</h2><canvas id="imageURLsPopularityChart"></canvas></div>';
                    echo '<div style="width: 32%;"><h2>Requests Over the Last Week</h2><canvas id="lastWeekRequestsChart"></canvas></div>';
                    echo '</div>'; // End of wrapper div

                    // Link to image that opens in a new window/tab
                    $current_server = $_SERVER['HTTP_HOST'];
                    $image_url = "http://34.132.90.61/proxy?url=https://{$current_server}/wp-content/test.jpg";

                    echo '<div class="embedded-image-wrapper">';
                    echo '<a href="' . $image_url . '" target="_blank" rel="noopener noreferrer">';
                    echo 'Test poison image';
                    echo '</a>';
                    echo '</div>';


                    // Generate the charts
                    ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            // Chart for Blocked Requests Over Time (Past 24 hours)
                            var ctxTime = document.getElementById("blockedRequestsTimeChart").getContext("2d");
                            new Chart(ctxTime, {
                                type: "line",
                                data: {
                                    labels: <?php echo json_encode($hourly_labels); ?>,
                                    datasets: [{
                                        label: "Blocked Requests Over Time",
                                        data: <?php echo json_encode(array_values($hourly_data)); ?>,
                                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                                        borderColor: "rgba(75, 192, 192, 1)",
                                        borderWidth: 1,
                                        fill: false
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        },
                                        x: {
                                            type: 'time',
                                            time: {
                                                unit: 'hour',
                                                displayFormats: {
                                                    hour: 'HH:mm'
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                            // Chart for Image URLs Popularity
                            var ctxImage = document.getElementById("imageURLsPopularityChart").getContext("2d");
                            new Chart(ctxImage, {
                                type: "bar",
                                data: {
                                    labels: <?php echo json_encode(array_keys($images_counted)); ?>,
                                    datasets: [{
                                        label: "Image URLs Popularity",
                                        data: <?php echo json_encode(array_values($images_counted)); ?>,
                                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                                        borderColor: "rgba(255, 99, 132, 1)",
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        // Chart for Requests Over the Last Week
                            var ctxWeek = document.getElementById("lastWeekRequestsChart").getContext("2d");
                            new Chart(ctxWeek, {
                                type: "bar",
                                data: {
                                    labels: <?php echo json_encode(array_keys($weekly_data)); ?>,
                                    datasets: [{
                                        label: "Requests Over the Last Week",
                                        data: <?php echo json_encode(array_values($weekly_data)); ?>,
                                        backgroundColor: "rgba(153, 102, 255, 0.2)",
                                        borderColor: "rgba(153, 102, 255, 1)",
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                <?php
                endif; 
                ?>
            </div>
        </div>

            <!-- START: Separate section for CCBot and GPTBot -->
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
    UIManager.handleKudurruFormSubmission();

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