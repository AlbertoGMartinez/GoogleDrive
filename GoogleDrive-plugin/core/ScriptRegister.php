<?php
namespace Netwerven\GoogleDrive;

/**
 * Registers the required javascript files for the googleDrive api to work
 *
 * @author Alberto Godino Martinez
 * @link www.netwerven.nl
 *
 */
class ScriptRegister
{
    /**
     * The googleDrive keys as supplied in wp-admin settings
     * @var string
     */
    protected $googleDriveApiKey;
    protected $developerKey;
    protected $appId;

    /**
     * Holds the wp-admin settings for the googleDrive plugin
     * @var array
     */
    protected $options = [];
    
    /**
     * Registers the javascript files to wordpress
     * Immediately returns if no settings found
     * @return boolean
     */
    public function register()
    {

        // add scripts to the head
        add_action('wp_head', [$this,'_registerGoogleDriveApiScript']);

        add_action('wp_enqueue_scripts', [$this, '_registerGoogleDriveHandlerScript' ]);
    }

    /**
     * Adds the googleDrive api to head only for the selected page templates
     */
    public function _registerGoogleDriveApiScript()
    {
        //register nothing if no configuration is set
        if (!$this->_getConfiguration()) {
            return false;
        }

        //do not load the scripts in admin pages
        if (is_admin()) {
            return false;
        }

        //handle the page not found errors
        global $post;
        if(!$post){
            return false;
        }
        echo '<script id=googleDrivejs type="text/javascript" src="https://apis.google.com/js/client.js" data-google_drive_api_key =' . $this->googleDriveApiKey .' data-developer_key =' . $this->developerKey .' data-app_id =' . $this->appId .'></script>';
        echo '<script type="text/javascript" src="https://apis.google.com/js/api.js?onload=onApiLoad"></script>'; 
        echo '<script src="https://apis.google.com/js/client.js?onload=initPicker"></script>'; 
    }

        /**
        * Fetches the settings of the googleDrive plugin, returning false if no settings are found, true otherwise
        *@return boolean
        */
    protected function _getConfiguration()
    {
        if ($options = get_option('wprf_googleDrive_general_options')) {

            $this->options = $options;
            $this->googleDriveApiKey = isset($this->options['googleDrive_api_key']) ? $this->options['googleDrive_api_key'] : null;
            $this->developerKey = isset($this->options['developerKey']) ? $this->options['developerKey'] : null;
            $this->appId = isset($this->options['appId']) ? $this->options['appId'] : null;

            return true;
        }

        return false;
    }

    /**
     * Registers the googleDrive upload cv script
     */
    public function _registerGoogleDriveHandlerScript()
    {

        wp_register_script('googleDrive', plugins_url() . '/GoogleDrive-plugin/' . '/public/js/GoogleDrive.js', ['jquery']);

        //do not load the scripts in admin pages
        if ( is_admin() ) {
            return false;
        }

        //handle the page not found errors
        global $post;
        if(!$post){
            return false;
        }

        wp_enqueue_script('googleDrive');
    

    }
}
