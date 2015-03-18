<?php
namespace Netwerven\GoogleDrive;

/**
 * Handles the GoogleDrive browse process for GoogleDrive Api v2
 *
 * @author Alberto Godino Martinez
 * @link www.netwerven.nl
 *
 */
class GoogleDrive
{

    /**
     * Holds the instance of the GoogleDrive plugin
     *
     * @var \Wprf\GoogleDrive\GoogleDrive
     */
    protected static $instance;

    /**
     * Holds the instance of the javascript register class
     *
     * @var \Wprf\GoogleDrive\ScriptRegister
     */
    protected $script_register;

    /**
     * Initializes the GoogleDrive plugin with dependecy the Script Register
     *
     * @param ScriptRegister $script_register The class that registers the javascripts of the plugin
     */
    protected function __construct(ScriptRegister $script_register)
    {
        $this->script_register = $script_register;
    }

    /**
     * Returns the singleton instance of the class, instanciating it if not already
     * and passing the dependencies
     *
     * @return \Wprf\GoogleDrive\GoogleDrive
     */
    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            $script_register = new ScriptRegister();
            $script_register->register();
            self::$instance = new self($script_register);
        }
        return self::$instance;
    }
}
