<?php
namespace Wprf\GoogleDrive;

/**
 * Handles the display/submit of settings in wp-admin for the googleDrive plugin
 * The settings are found under WPR_setttings, GoogleDrive tab
 *
 * @author 
 * @link 
 *
 */
class AdminSettings
{

    /**
     * The name of the settings as will be stored in wp options table
     * @var string
     */
    protected $option_name = 'wprf_googleDrive_general_options';

    /**
     * The name of the settings group
     * @var string
     */
    protected $settings_group = 'wprf_googleDrive_settings';

    /**
     * Initializes the class and settings by adding the action to admin_init hook
     */
    public function __construct()
    {

        // Initialize theme options when in WP admin
        add_action('admin_init', [$this, 'initSettings']);
    }

    /**
     * Builds the settings tab and plugin options
     *
     * @return void
     */
    public function initSettings()
    {

        // Add tab to settings page
        add_action('wprf_settings_tabs', function ()
        {
            echo '<a href="?page=wprf_settings&tab=wprf_googleDrive" class="nav-tab'
                .(isset($_GET['tab']) && $_GET['tab'] == 'wprf_googleDrive' ? ' nav-tab-active' : '')
                . '">' . __('GoogleDrive', 'wprf') . '</a>';
        });

        // Display our settings section when our tab is active
        if (isset($_GET['tab']) && $_GET['tab'] == "wprf_googleDrive") {
            add_action('wprf_display_settings_sections', function ()
            {
                // /Output nonce, action, and option_page fields for a settings page
                settings_fields($this->settings_group);
                //Prints out all settings sections added to a particular settings page
                do_settings_sections($this->settings_group);
            });
        }

        // Register all these settings to the right option
        register_setting($this->settings_group, $this->option_name);

        // Initiate the section for main settings
        add_settings_section('wprf_googleDrive_settings_main', __('Google Drive Settings', 'wprf'), function (){}, $this->settings_group);

        // Add all settings fields
        $this->addSettingsfields();
    }

    /**
     * Adds the settings fields containing the googleDrive api key,
     */
    public function addSettingsfields()
    {

        // get the options
        $options = get_option($this->option_name);


        // show the googleDrive api key input
        $googleDrive_api_key = isset($options['googleDrive_api_key']) ? $options['googleDrive_api_key'] : null;
        $developerKey = isset($options['developerKey']) ? $options['developerKey'] : null;
        $appId = isset($options['appId']) ? $options['appId'] : null;

        add_settings_field(
            'googleDrive_api_key', 'GoogleDrive API Key',
            [
                $this,
                '_inputSetting'
            ],
            $this->settings_group, 'wprf_googleDrive_settings_main',
            [
                'id' => 'googleDrive_api_key',
                'value' => $googleDrive_api_key
            ]
        );

        add_settings_field(
            'developerKey', 'DeveloperKey',
            [
                $this,
                '_inputSetting'
            ],
            $this->settings_group, 'wprf_googleDrive_settings_main',
            [
                'id' => 'developerKey',
                'value' => $developerKey
            ]
        );

        add_settings_field(
            'appId', 'AppId',
            [
                $this,
                '_inputSetting'
            ],
            $this->settings_group, 'wprf_googleDrive_settings_main',
            [
                'id' => 'appId',
                'value' => $appId
            ]
        );
    }

    /**
     * Creates text input setting element
     * @param array $arguments Holds the properties of the input (id, value)
     */
    public function _inputSetting($arguments)
    {
        $id = $this->option_name . '-' . $arguments['id'];
        $name = $this->option_name . '[' . $arguments['id'] . ']';
        $value = $arguments['value'];
        echo '<input type="text" id="' . $id . '" name="' . $name . '" value="' . $value . '">';
    }

}
