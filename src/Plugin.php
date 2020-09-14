<?php
namespace SiteMaster\Plugins\Metric_Sensitive_Terms;

use SiteMaster\Core\Plugin\PluginInterface;
use SiteMaster\Core\Events\RoutesCompile;
use SiteMaster\Core\Events\RegisterTheme;

class Plugin extends PluginInterface
{
    /**
     * @return bool|mixed
     */
    public function onInstall()
    {
        return true;
    }

    /**
     * @return bool|mixed
     */
    public function onUninstall()
    {
        return true;
    }

    /**
     * @return mixed|string
     */
    public function getName()
    {
        return 'Sensitive Terms Metric';
    }

    /**
     * @return mixed|string
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Called when the plugin is updated (a newer version exists).
     *
     * @param $previousVersion int The previous installed version
     * @return mixed
     */
    public function onUpdate($previousVersion)
    {
        return true;
    }

    /**
     * Returns the version of this plugin
     * Follow a mmddyyyyxx syntax.
     *
     * for example 1118201301
     * would be 11/18/2013 - increment 1
     *
     * @return mixed
     */
    public function getVersion()
    {
        return true;
    }

    /**
     * Get an array of event listeners
     *
     * @return array
     */
    function getEventListeners()
    {
        $listeners = array();

        return $listeners;
    }
}