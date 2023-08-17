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

// UIManager is an object containing methods related to UI functionalities.
var UIManager = {
  // Variable to store the last sent state
  lastSentState: "",
  // This method handles the logic for toggling checkboxes states.
  toggleCheckboxStates: function () {
    // It selects all the elements with class "wppd-ui-toggle".
    var checkboxes = document.getElementsByClassName("wppd-ui-toggle");
    // It also selects all the elements with class "wppd-ui-toggle-opposite".
    var oppositeCheckboxes = document.getElementsByClassName(
      "wppd-ui-toggle-opposite"
    );

    // It then loops through all these checkboxes.
    for (let i = 0; i < checkboxes.length; i++) {
      // For each checkbox, it adds an event listener that reacts to the 'change' event.
      checkboxes[i].addEventListener("change", function () {
        // When the checkbox changes state, it inverses the state of the corresponding oppositeCheckbox.
        oppositeCheckboxes[i].checked = !this.checked;
      });
      oppositeCheckboxes[i].addEventListener("change", function () {
        // Vice versa for the opposite checkboxes.
        checkboxes[i].checked = !this.checked;
      });
    }
  },

  // This method enables the 'ai-update-button' when any checkbox changes state.
  enableUpdateButton: function () {
    // It first selects all the checkboxes.
    var checkboxes = document.querySelectorAll(
      ".wppd-ui-toggle, .wppd-ui-toggle-opposite"
    );
    // Then selects the button.
    var button = document.getElementById("ai-update-button");

    UIManager.lastSentState = Array.from(checkboxes)
      .map((checkbox) => checkbox.checked)
      .join();

    // For each checkbox, it adds an event listener that reacts to the 'change' event.
    checkboxes.forEach(function (checkbox) {
      checkbox.addEventListener("change", function () {
        // If the state is the same as previous, no need to enable the button.
        if (UIManager.lastSentState !== "") {
          var stateboxes = document.querySelectorAll(
            ".wppd-ui-toggle, .wppd-ui-toggle-opposite"
          );
          button.disabled =
            UIManager.lastSentState ===
            Array.from(stateboxes)
              .map((checkbox) => checkbox.checked)
              .join();
        } else {
          // When a checkbox changes state, it enables the button.
          button.disabled = false;
        }
      });
    });
  },

  // This method shows the admin panel once all its images are loaded.
  showAdminPanel: function () {
    // Selects the admin panel.
    var adminPanel = document.getElementById("spawning-admin-panel");
    // Selects all images in the admin panel.
    var images = document.querySelectorAll("#spawning-admin-panel img");
    var loadedCount = 0;

    // This helper function increases the count of loaded images.
    function checkAllImagesLoaded() {
      loadedCount++;
      // If all images have loaded, it makes the admin panel visible with a fade-in effect.
      if (loadedCount === images.length) {
        adminPanel.style.opacity = 0; // Set initial opacity to 0
        adminPanel.style.display = "block";
        setTimeout(function () {
          adminPanel.style.opacity = 1; // Gradually increase opacity to 1
        }, 100); // Delay the opacity change to allow the display property to take effect
      }
    }

    // For each image, it checks if it has already loaded.
    for (var i = 0; i < images.length; i++) {
      if (images[i].complete) {
        checkAllImagesLoaded();
      } else {
        // If not, it adds an event listener to trigger when it loads.
        images[i].addEventListener("load", checkAllImagesLoaded);
      }
    }
  },

  handleRobotsFormSubmission: function () {
    // IIFE with jQuery as the argument
    (function ($) {
      // Bind an event handler to the 'submit' event of the form with the ID 'robotsForm'
      $("#robotsForm").on("submit", function (e) {
        // Prevent the default form submission
        e.preventDefault();

        // AJAX request to submit the form data
        $.ajax({
          type: "POST",
          url: ajaxurl,
          data: {
            action: "handle_robots_form", // This action should correspond to a PHP hook on the server side
            form: $(this).serialize(),
            _wpnonce: $("#robots_nonce").val(), // Use the ID "robots_nonce"
          },
          success: function (response) {
            // Handle the successful response here
            if (response.status === "error") {
              console.error(response.message);
            } else {
              $("#notification")
                .text("Robots.txt modified successfully!")
                .css("opacity", "1")
                .delay(3000)
                .animate({ opacity: 0 }, 500);
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error(
              "AJAX Error:",
              textStatus,
              errorThrown,
              jqXHR.responseText
            );
          },
        });
      });
    })(jQuery);
  },

  // This is a method of an object that handles the form submission.
  handleFormSubmission: function () {
    // This is an Immediately Invoked Function Expression (IIFE) with jQuery as the argument.
    // It provides an isolated scope for the jQuery code, preventing conflicts with other libraries or scripts.
    (function ($) {
      // Here, we're binding an event handler to the 'submit' event of the form with the ID 'aiForm'.
      // This event is fired when the user submits the form.
      $("#aiForm").on("submit", function (e) {
        // This line prevents the default form submission, which would cause a page reload.
        // We don't want this because we're handling the form submission asynchronously with AJAX.
        e.preventDefault();

        // Store the current state before making the AJAX request
        var checkboxes = document.querySelectorAll(
          ".wppd-ui-toggle, .wppd-ui-toggle-opposite"
        );
        UIManager.lastSentState = Array.from(checkboxes)
          .map((checkbox) => checkbox.checked)
          .join();

        // Make an AJAX request.
        // This is a POST request to the URL stored in the variable 'ajaxurl'.
        $.ajax({
          type: "POST",
          url: ajaxurl,
          data: {
            action: "handle_ai_form", // Changed the action to match the PHP hook
            form: $(this).serialize(),
            _wpnonce: $("#ai_nonce").val(), // Use the ID "ai_nonce"
          },
          success: function (response) {
            // The code in this function will execute if the server responds successfully.
            // The server's response is available in the 'response' variable.

            // If the response status indicates an error, log the message to the console.
            if (response.status === "error") {
              console.error(response.message);
            } else {
              $(".create-show").hide();
              $(".create-hidden").show();
              $("#ai-update-button").prop("disabled", true);
              $("#notification")
                .text("Ai.txt updated successfully!")
                .css("opacity", "1")
                .delay(3000)
                .animate({ opacity: 0 }, 500);
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error(
              "AJAX Error:",
              textStatus,
              errorThrown,
              jqXHR.responseText
            );
          },
        });
      });
    })(jQuery);
  },
};
