# spawning-wp
`spawning-wp` is a WordPress plugin which allows users to decide which types of content on their site is permitted for use with AI training. The plugin provides users with simple toggle switches to opt-out specific content types which may be used to train AI, and also generates a file at the appropriate location which follows the selected rules. Information on ai.txt can be found [here](https://site.spawning.ai/spawning-ai-txt). 

## Build
To build a ZIP file from source, run the following commands:
```
cd spawning-wp
npm install
npm run build
```
The output will be a ZIP file named `spawning_ai.zip`, and located in the current working directory.

## Installation
Take the generated ZIP file from the build process, and follow the below steps:
1. Log into your WordPress site.
2. Navigate to the tab. If you don't see this, please verify your user permissions.
3. Click the **Add New** button.
4. Click the **Upload Plugin** button.
5. Select the **Choose File** prompt. Find the `spawning_ai.zip` file and select it.
6. Clcik the **Install Now** button.
7. Once the installation completes, you will see a buttin **Activate Plugin**. 
8. You should now have a new tab in your WordPress installation labeled **Spawning AI**.
9. Click this tab to see the ai.txt generation options. The `ai.txt` file will automatically be built and saved to your root path (e.g. `https://www.example.com/ai.txt`).

The steps are also available in this video:
https://streamable.com/ac00xc

## Testing
It's easiest to test with a temporary WordPress installation. You could use something like Docker, though [this](https://app.instawp.io/onboard) makes it simple to have a temporary testing environment.
