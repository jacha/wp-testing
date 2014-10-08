<?php

/**
 * Facade into wordpress
 */
class WpTesting_WordPressFacade
{

    /**
     * Plugin filename (required for hooks)
     * @var string
     */
    private $pluginFile = null;

    /**
     * @param string $pluginFile Plugin filename (required for hooks)
     */
    public function __construct($pluginFile)
    {
        $this->pluginFile = $pluginFile;
    }

    public function getDbHost()
    {
        return DB_HOST;
    }

    public function getDbName()
    {
        return DB_NAME;
    }

    public function getDbUser()
    {
        return DB_USER;
    }

    public function getDbPassword()
    {
        return DB_PASSWORD;
    }

    public function getTablePrefix()
    {
        return $GLOBALS['table_prefix'];
    }

    public function getDbCharset()
    {
        return DB_CHARSET;
    }

    /**
     * Absolute path to the WordPress directory.
     */
    public function getAbsPath()
    {
        return ABSPATH;
    }

    /**
     * Allows for the plugins directory to be moved from the default location.
     *
     * @since 2.6.0
     */
    public function getPluginDir()
    {
        return WP_PLUGIN_DIR;
    }

    /**
     * Determines a writable directory for temporary files (with trailing slahs added).
     *
     * @since 2.5.0
     *
     * @return string Writable temporary directory
     */
    public function getTempDir()
    {
        return get_temp_dir();
    }

    /**
     * Holds the WordPress Rewrite object for creating pretty URLs
     *
     * @since 1.5.0
     * @return WP_Rewrite
     */
    public function getRewrite()
    {
        return $GLOBALS['wp_rewrite'];
    }

    /**
     * WordPress Query API
     *
     * @since 1.5.0
     *
     * @return WP_Query
     */
    public function getQuery()
    {
        return $GLOBALS['wp_query'];
    }

    /**
     * Enqueue a CSS stylesheet related to plugin path.
     *
     * @since 2.6.0
     *
     * @param string $name Name of the stylesheet.
     * @param string $pluginRelatedPath
     * @return WpTesting_WordPressFacade
     */
    public function enqueuePluginStyle($name, $pluginRelatedPath)
    {
        wp_enqueue_style($name, plugins_url($pluginRelatedPath, $this->pluginFile));
        return $this;
    }

    /**
     * Enqueue an JS script related to plugin path.
     *
     * @since 2.6.0
     *
     * @param string $name Name of the script.
     * @param string $pluginRelatedPath
     * @param array $dependencies Optional. An array of registered handles this script depends on. Default empty array.
     * @param string $version Optional. String specifying the script version number, if it has one. This parameter is used to ensure that the correct version is sent to the client regardless of caching, and so should be included if a version number is available and makes sense for the script.
     * @param string $isInFooter Optional. Whether to enqueue the script before or before . Default 'false'. Accepts 'false' or 'true'.
     * @return WpTesting_WordPressFacade
     */
    public function enqueuePluginScript($name, $pluginRelatedPath, array $dependencies = array(), $version = false, $isInFooter = false)
    {
        $path = plugins_url($pluginRelatedPath, $this->pluginFile);
        wp_enqueue_script($name, $path, $dependencies, $version, $isInFooter);
        return $this;
    }


    /**
     * Register an JS script related to plugin path.
     *
     * Mainly to use in later dependencies.
     *
     * @since 2.6.0
     *
     * @param string $name Name of the script.
     * @param string $pluginRelatedPath
     * @param array $dependencies Optional. An array of registered handles this script depends on. Default empty array.
     * @param string $version Optional. String specifying the script version number, if it has one. This parameter is used to ensure that the correct version is sent to the client regardless of caching, and so should be included if a version number is available and makes sense for the script.
     * @param string $isInFooter Optional. Whether to enqueue the script before or before . Default 'false'. Accepts 'false' or 'true'.
     * @return WpTesting_WordPressFacade
     */
    public function registerPluginScript($name, $pluginRelatedPath, array $dependencies = array(), $version = false, $isInFooter = false)
    {
        $path = plugins_url($pluginRelatedPath, $this->pluginFile);
        wp_register_script($name, $path, $dependencies, $version, $isInFooter);
        return $this;
    }

    /**
     * Retrieve the name of the highest priority template file that exists.
     *
     * @since 2.7.0
     *
     * @param string|array $templateNames Template file(s) to search for, in order.
     * @param string $isLoad  If true the template file will be loaded if it is found.
     * @param string $isRequireOnce Whether to require_once or require. Default true. Has no effect if $isLoad is false.
     * @return string The template filename if one is located.
     */
    public function locateTemplate($templateNames, $isLoad = false, $isRequireOnce = true )
    {
        return locate_template($templateNames, $isLoad, $isRequireOnce);
    }

    /**
     * Retrieve full permalink for post by ID or current post
     *
     * @since 1.0.0
     *
     * @param number|WP_Post $id
     * @param string $isLeaveName
     * @return string|bool
     */
    public function getPermalink($id = 0, $isLeaveName = false)
    {
        return get_permalink($id, $isLeaveName);
    }

    /**
     * Hooks a function on to a specific action.
     *
     * @since 1.2.0
     * @link http://codex.wordpress.org/Function_Reference/add_action
     *
     * @param string $tag The name of the action to which the $function is hooked.
     * @param callback $function The name of the function you wish to be called.
     * @param int $priority optional. Used to specify the order in which the functions associated with a particular action are executed (default: 10). Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.
     * @param int $functionArgsCount optional. The number of arguments the function accept (default 1).
     * @return WpTesting_WordPressFacade
     */
    public function addAction($tag, callable $function, $priority = 10, $functionArgsCount = 1)
    {
        add_action($tag, $function, $priority, $functionArgsCount);
        return $this;
    }

    /**
     * Hooks a function or method to a specific filter action.
     *
     * @since 0.71
     *
     * @param string $tag The name of the action to which the $function is hooked.
     * @param callback $function The name of the function you wish to be called.
     * @param int $priority optional. Used to specify the order in which the functions associated with a particular action are executed (default: 10). Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.
     * @param int $functionArgsCount optional. The number of arguments the function accept (default 1).
     * @return WpTesting_WordPressFacade
     */
    public function addFilter($tag, callable $function, $priority = 10, $functionArgsCount = 1)
    {
        add_filter($tag, $function, $priority, $functionArgsCount);
        return $this;
    }

    /**
     * Add hook for shortcode tag.
     *
     * @since 2.5.0
     * @link http://codex.wordpress.org/Function_Reference/add_shortcode
     *
     * @param string $tag Shortcode tag to be searched in post content.
     * @param callable $function Hook to run when shortcode is found.
     * @return WpTesting_WordPressFacade
     */
    public function addShortcode($tag, callable $function)
    {
        add_shortcode($tag, $function);
        return $this;
    }

    /**
     * Add a meta box to an edit form.
     *
     * @since 2.5.0
     *
     * @param string           $id            String for use in the 'id' attribute of tags.
     * @param string           $title         Title of the meta box.
     * @param callable         $function      Function that fills the box with the desired content.
     *                                        The function should echo its output.
     * @param string|WP_Screen $screen        Optional. The screen on which to show the box (like a post
     *                                        type, 'link', or 'comment'). Default is the current screen.
     * @param string           $context       Optional. The context within the screen where the boxes
     *                                        should display. Available contexts vary from screen to
     *                                        screen. Post edit screen contexts include 'normal', 'side',
     *                                        and 'advanced'. Comments screen contexts include 'normal'
     *                                        and 'side'. Menus meta boxes (accordion sections) all use
     *                                        the 'side' context. Global default is 'advanced'.
     * @param string           $priority      Optional. The priority within the context where the boxes
     *                                        should show ('high', 'low'). Default 'default'.
     * @param array            $functionArgs  Optional. Data that should be set as the $args property
     *                                        of the box array (which is the second parameter passed
     *                                        to your callback). Default null.
     * @return WpTesting_WordPressFacade
     */
    public function addMetaBox($id, $title, callable $function, $screen = null, $context = 'advanced', $priority = 'default', $functionArgs = null)
    {
        add_meta_box($id, $title, $function, $screen, $context, $priority, $functionArgs);
        return $this;
    }

    /**
     * Register a post type. Do not use before init.
     *
     * @since 2.9.0
     * @link http://codex.wordpress.org/Function_Reference/register_post_type
     *
     * @param string $name Post type key, must not exceed 20 characters.
     * @param array $parameters
     * @return WpTesting_WordPressFacade
     */
    public function registerPostType($name, $parameters = array())
    {
        register_post_type($name, $parameters);
        return $this;
    }

    /**
     * Create or modify a taxonomy object. Do not use before init.
     *
     * @since 2.3.0
     * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
     *
     * @param string $name Taxonomy key, must not exceed 32 characters.
     * @param array|string $objectType Name of the object type(s) for the taxonomy object.
     * @param array|string $parameters
     * @return WpTesting_WordPressFacade
     */
    public function registerTaxonomy($name, $objectType, $parameters = array())
    {
        register_taxonomy($name, $objectType, $parameters);
        return $this;
    }

    /**
     * Set the activation hook for a plugin.
     *
     * @since 2.0.0
     * @link http://codex.wordpress.org/Function_Reference/register_activation_hook
     *
     * @param callback $function the function hooked for action.
     * @return WpTesting_WordPressFacade
     */
    public function registerActivationHook(callable $function)
    {
        register_activation_hook($this->pluginFile, $function);
        return $this;
    }

    /**
     * Set the deactivation hook for a plugin.
     *
     * @since 2.0.0
     * @link http://codex.wordpress.org/Function_Reference/register_deactivation_hook
     *
     * @param callback $function the function hooked for action.
     * @return WpTesting_WordPressFacade
     */
    public function registerDeactivationHook(callable $function)
    {
        register_deactivation_hook($this->pluginFile, $function);
        return $this;
    }

    /**
     * Set the uninstallation hook for a plugin.
     *
     * @since 2.7.0
     * @link http://codex.wordpress.org/Function_Reference/register_uninstall_hook
     *
     * @param callback $function the function hooked for action.
     * @return WpTesting_WordPressFacade
     */
    public function registerUninstallHook(callable $function)
    {
        register_uninstall_hook($this->pluginFile, $function);
        return $this;
    }
}