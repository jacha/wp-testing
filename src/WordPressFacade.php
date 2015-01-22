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
     * WordPress Object
     * @global object $wp
     * @since 2.0.0
     * @return WP
     */
    public function getWP()
    {
       return $GLOBALS['wp'];
    }

    /**
     * Main WordPress Query
     *
     * @since 1.5.0
     *
     * @return WP_Query
     */
    public function getMainQuery()
    {
        return $GLOBALS['wp_the_query'];
    }

    /**
     * Is passed query the main query?
     *
     * Backward compatible check instead of WP_Query::is_main_query, which is available since 3.3
     *
     * @since 1.5.0
     *
     * @param WP_Query $query
     * @return boolean
     */
    public function isQueryMain($query)
    {
        return $query === $this->getMainQuery();
    }

    /**
     * Current WordPress Query
     *
     * Usually it's a link to "Main WordPress Query"
     *
     * @since 1.5.0
     * @see WpTesting_WordPressFacade::getMainQuery
     *
     * @return WP_Query
     */
    public function getQuery()
    {
        return $GLOBALS['wp_query'];
    }

    /**
     * Retrieve the ID of the current post
     *
     * @since 2.1.0
     * @return integer
     */
    public function getCurrentPostId()
    {
        return $GLOBALS['post']->ID;
    }

    /**
     * Retrieve post meta field for a post.
     *
     * @since 1.5.0
     * @uses $wpdb
     * @link http://codex.wordpress.org/Function_Reference/get_post_meta
     *
     * @param int $post_id Post ID.
     * @param string $key The meta key to retrieve.
     * @param bool $single Whether to return a single value.
     * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
     *  is true.
     */
    public function getCurrentPostMeta($key)
    {
        return $this->getPostMeta($this->getCurrentPostId(), $key, true);
    }

    /**
     * Retrieve post meta field for a post.
     *
     * @since 1.5.0
     * @uses $wpdb
     * @link http://codex.wordpress.org/Function_Reference/get_post_meta
     *
     * @param int $postId Post ID.
     * @param string $key The meta key to retrieve.
     * @param bool $isSingle Whether to return a single value.
     * @return mixed Will be an array if $single is false.
     *               Will be value of meta data field if $single is true.
     */
    public function getPostMeta($postId, $key, $isSingle)
    {
        return get_post_meta($postId, $key, $isSingle);
    }

    /**
     * Update post meta field based on post ID.
     *
     * Use the $prev_value parameter to differentiate between meta fields with the
     * same key and post ID.
     *
     * If the meta field for the post does not exist, it will be added.
     *
     * @since 1.5.0
     * @uses $wpdb
     * @link http://codex.wordpress.org/Function_Reference/update_post_meta
     *
     * @param int $postId Post ID.
     * @param string $key Metadata key.
     * @param mixed $value Metadata value.
     * @param mixed $previousValue Optional. Previous value to check before removing.
     * @return bool False on failure, true if success.
     */
    public function updatePostMeta($postId, $key, $value, $previousValue = '')
    {
        return update_post_meta($postId, $key, $value, $previousValue);
    }

    /**
     * The WordPress version string
     *
     * @return string
     */
    public function getVersion()
    {
        return $GLOBALS['wp_version'];
    }

    /**
     * Retrieve a URL within the plugin
     *
     * @since 2.6.0
     *
     * @param  string $pluginRelatedPath Optional. Extra path appended to the end of the URL, including
     *                                    the relative directory. Default empty.
     * @return string Plugin URL link with optional paths appended.
     */
    public function getPluginUrl($pluginRelatedPath = '')
    {
        return plugins_url($pluginRelatedPath, $this->pluginFile);
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
        wp_enqueue_style($name, $this->getPluginUrl($pluginRelatedPath));
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
        $path = $this->getPluginUrl($pluginRelatedPath);
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
        $path = $this->getPluginUrl($pluginRelatedPath);
        wp_register_script($name, $path, $dependencies, $version, $isInFooter);
        return $this;
    }

    /**
     * Loads the plugin's translated strings.
     *
     * If the path is not given then it will be the root of the plugin directory.
     * The .mo file should be named based on the domain with a dash, and then the locale exactly.
     *
     * @since 1.5.0
     *
     * @param string $domain Unique identifier for retrieving translated strings
     * @param string $absoluteRelativePath Optional. Relative path to ABSPATH of a folder,
     * 	where the .mo file resides. Deprecated, but still functional until 2.7
     * @param string $pluginsRelativePath Optional. Relative path to WP_PLUGIN_DIR. This is the preferred argument to use. It takes precendence over $abs_rel_path
     * @return boolean
     */
    public function loadPluginTextdomain($domain, $absoluteRelativePath = false, $pluginsRelativePath = false)
    {
        return load_plugin_textdomain($domain, $absoluteRelativePath, $pluginsRelativePath);
    }

    /**
     * Localize a script.
     *
     * Works only if the script has already been added.
     *
     * Accepts an associative array $l10n and creates a JavaScript object:
     *
     *     "$object_name" = {
     *         key: value,
     *         key: value,
     *         ...
     *     }
     *
     * @since 2.6.0
     *
     * @param string $handle      Script handle the data will be attached to.
     * @param string $objectName  Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
     *                            Example: '/[a-zA-Z0-9_]+/'.
     * @param array $l10n         The data itself. The data can be either a single or multi-dimensional array.
     * @return bool True if the script was successfully localized, false otherwise.
     */
    public function localizeScript($handle, $objectName, $l10n)
    {
        return wp_localize_script($handle, $objectName, $l10n);
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
     * Retrieve the permalink for a post with a custom post type.
     *
     * @since 3.0.0
     *
     * @param int $id Optional. Post ID.
     * @param bool $isLeavename Optional, defaults to false. Whether to keep post name.
     * @param bool $isSample Optional, defaults to false. Is it a sample permalink.
     * @return string The post permalink.
     */
    public function getPostPermalink($id = 0, $isLeavename = false, $isSample = false)
    {
        return get_post_permalink($id, $isLeavename, $isSample);
    }

    /**
     * Redirects to another page.
     *
     * @since 1.5.1
     *
     * @param string $location The path to redirect to.
     * @param int $status Status code to use.
     * @return bool False if $location is not provided, true otherwise.
     */
    function redirect($location, $status = 302)
    {
        return wp_redirect($location, $status);
    }

    /**
     * Get salt to add to hashes.
     *
     * Salts are created using secret keys. Secret keys are located in two places:
     * in the database and in the wp-config.php file. The secret key in the database
     * is randomly generated and will be appended to the secret keys in wp-config.php.
     *
     * The secret keys in wp-config.php should be updated to strong, random keys to maximize
     * security. Below is an example of how the secret key constants are defined.
     * Do not paste this example directly into wp-config.php. Instead, have a
     * {@link https://api.wordpress.org/secret-key/1.1/salt/ secret key created} just
     * for you.
     *
     *     define('AUTH_KEY',         ' Xakm<o xQy rw4EMsLKM-?!T+,PFF})H4lzcW57AF0U@N@< >M%G4Yt>f`z]MON');
     *     define('SECURE_AUTH_KEY',  'LzJ}op]mr|6+![P}Ak:uNdJCJZd>(Hx.-Mh#Tz)pCIU#uGEnfFz|f ;;eU%/U^O~');
     *     define('LOGGED_IN_KEY',    '|i|Ux`9<p-h$aFf(qnT:sDO:D1P^wZ$$/Ra@miTJi9G;ddp_<q}6H1)o|a +&JCM');
     *     define('NONCE_KEY',        '%:R{[P|,s.KuMltH5}cI;/k<Gx~j!f0I)m_sIyu+&NJZ)-iO>z7X>QYR0Z_XnZ@|');
     *     define('AUTH_SALT',        'eZyT)-Naw]F8CwA*VaW#q*|.)g@o}||wf~@C-YSt}(dh_r6EbI#A,y|nU2{B#JBW');
     *     define('SECURE_AUTH_SALT', '!=oLUTXh,QW=H `}`L|9/^4-3 STz},T(w}W<I`.JjPi)<Bmf1v,HpGe}T1:Xt7n');
     *     define('LOGGED_IN_SALT',   '+XSqHc;@Q*K_b|Z?NC[3H!!EONbh.n<+=uKR:>*c(u`g~EJBf#8u#R{mUEZrozmm');
     *     define('NONCE_SALT',       'h`GXHhD>SLWVfg1(1(N{;.V!MoE(SfbA_ksP@&`+AycHcAV$+?@3q+rxV{%^VyKT');
     *
     * Salting passwords helps against tools which has stored hashed values of
     * common dictionary strings. The added values makes it harder to crack.
     *
     * @since 2.5.0
     *
     * @link https://api.wordpress.org/secret-key/1.1/salt/ Create secrets for wp-config.php
     *
     * @param string $scheme Authentication scheme (auth, secure_auth, logged_in, nonce)
     * @return string Salt value
     */
    public function getSalt($scheme = 'auth')
    {
        return wp_salt($scheme);
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
    public function addAction($tag, $function, $priority = 10, $functionArgsCount = 1)
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
    public function addFilter($tag, $function, $priority = 10, $functionArgsCount = 1)
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
    public function addShortcode($tag, $function)
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
    public function addMetaBox($id, $title, $function, $screen = null, $context = 'advanced', $priority = 'default', $functionArgs = null)
    {
        add_meta_box($id, $title, $function, $screen, $context, $priority, $functionArgs);
        return $this;
    }

    /**
     * Get metaboxes by provided screen, context and priority
     *
     * @param string $screen
     * @param string $context
     * @param string $priority
     *
     * @return array
     */
    public function getMetaBoxes($screen = null, $context = 'advanced', $priority = 'default')
    {
        return $this->processMetaBoxes($screen, $context, $priority, __FUNCTION__, null);
    }

    /**
     * Set metaboxes by provided screen, context and priority to values
     *
     * @param array $values
     * @param string $screen
     * @param string $context
     * @param string $priority
     *
     * @return WpTesting_WordPressFacade
     */
    public function setMetaBoxes($values, $screen = null, $context = 'advanced', $priority = 'default')
    {
        return $this->processMetaBoxes($screen, $context, $priority, __FUNCTION__, $values);
    }

    protected function processMetaBoxes($screen, $context, $priority, $action, $values)
    {
        global $wp_meta_boxes;

        if (empty($screen)) {
            $screen = get_current_screen();
        } elseif (is_string($screen)) {
            $screen = convert_to_screen($screen);
        }

        $page = $screen->id;

        if (empty($wp_meta_boxes[$page][$context][$priority])) {
            $wp_meta_boxes[$page][$context][$priority] = array();
        }

        if ('getMetaBoxes' == $action) {
            return $wp_meta_boxes[$page][$context][$priority];
        }
        if ('setMetaBoxes' == $action) {
            $wp_meta_boxes[$page][$context][$priority] = $values;
            return $this;
        }
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
     * Whether the current request is for a network or blog admin page
     *
     * Does not inform on whether the user is an admin! Use capability checks to
     * tell if the user should be accessing a section or not.
     *
     * @since 1.5.1
     *
     * @return bool True if inside WordPress administration pages.
     */
    public function isAdministrationPage()
    {
        return is_admin();
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
    public function registerActivationHook($function)
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
    public function registerDeactivationHook($function)
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
    public function registerUninstallHook($function)
    {
        register_uninstall_hook($this->pluginFile, $function);
        return $this;
    }

    /**
     * Kill WordPress execution and display HTML message with error message.
     *
     *
     * @since 2.0.4
     *
     * @param string $message Error message.
     * @param string $title Error title.
     * @param string|array $arguments Optional arguments to control behavior.
     *
     * <p>Arguments:</p>
     * <ul>
     *     <li><strong>boolean 'back_link'</strong>      Do we need to display localized "Back link" link?</li>
     *     <li><strong>integer 'response'</strong>       HTTP status code: 200, 500, 404, etc.</li>
     *     <li><strong>string  'text_direction'</strong> Text direction: ltr, rtl</li>
     * </ul>
     */
    public function dieMessage($message='', $title='', $arguments=array())
    {
        wp_die($message, $title, $arguments);
    }
}