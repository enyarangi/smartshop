<?php

/**
 * Handles the parameters and url
 *
 * @author StarBox
 */
class HMU_Classes_Tools extends HMU_Classes_FrontController {

    /** @var array Saved options in database */
    public static $default = array();
    public static $lite = array();
    public static $options = array();
    public static $debug = array();

    /** @var integer Count the errors in site */
    static $errors_count = 0;

    /**
     * @var bool If the current site is WP MU
     */
    static $is_multisite = false;

    public function __construct() {
        parent::__construct();

        self::$options = $this->getOptions();
    }

    /**
     * Load the Options from user option table in DB
     *
     * @param boolean $safe | the database paramenter
     * @return array
     */
    public static function getOptions($safe = false) {
        $keymeta = HMU_OPTION;

        if ($safe) {
            $keymeta = HMU_OPTION_SAFE;
        }

        $init = array(
            'hmu_ver' => 0,
            'hmu_disable' => mt_rand(111111, 999999),
            'logout' => false,
            'error' => false,
            'admin_notice' => array()
        );
        self::$default = array(
            'hmu_mode' => 'default',
            'hmu_admin_url' => 'wp-admin',
            'hmu_login_url' => 'wp-login.php',
            'hmu_plugin_url' => 'wp-content/plugins',
            'hmu_themes_url' => 'wp-content/themes',
            'hmu_admin-ajax_url' => 'admin-ajax.php',
            'hmu_category_url' => 'category',
            'hmu_tags_url' => 'tag',
            'hmu_hide_admin' => 0,
            'hmu_hide_login' => 0,
            'hmu_send_email' => 1
        );

        self::$lite = array(
            'hmu_mode' => 'lite',
            'hmu_admin_url' => 'wp-admin',
            'hmu_login_url' => 'login',
            'hmu_hide_admin' => 1,
            'hmu_hide_login' => 1,
        );

        if (is_multisite()) {
            $options = json_decode(get_blog_option(BLOG_ID_CURRENT_SITE, $keymeta), true);
        } else {
            $options = json_decode(get_option($keymeta), true);
        }

        if (is_array($options)) {
            $options = @array_merge($init, self::$default, $options);
        } else {
            $options = @array_merge($init, self::$default);
        }

        return $options;
    }

    /**
     * Get the option from database
     * @param $key
     * @return mixed
     */
    public static function getOption($key) {
        if (!isset(self::$options[$key])) {
            self::$options = self::getOptions();
        }

        return self::$options[$key];
    }

    /**
     * Save the Options in user option table in DB
     * @param string $key database key
     * @param string $value
     * @param boolean $safe
     * @return void
     */
    public static function saveOptions($key = null, $value = '', $safe = false) {
        $keymeta = HMU_OPTION;

        if ($safe) {
            $keymeta = HMU_OPTION_SAFE;
        }

        if (isset($key)) {
            self::$options[$key] = $value;
        }

        if (is_multisite()) {
            update_blog_option(BLOG_ID_CURRENT_SITE, $keymeta, json_encode(self::$options));
        } else {
            update_option($keymeta, json_encode(self::$options));
        }
    }

    /**
     * This hook will save the current version in database
     *
     * @return void
     */
    function hookInit() {
        //Activate when we have translation
        //$this->loadMultilanguage();

        //add setting link in plugin
        add_filter('plugin_action_links', array($this, 'hookActionlink'), 5, 2);
    }

    /**
     * Add a link to settings in the plugin list
     *
     * @param array $links
     * @param string $file
     * @return array
     */
    public function hookActionlink($links, $file) {
        if ($file == _HMU_PLUGIN_NAME_ . '/index.php') {
            $link = '<a href="' . admin_url('admin.php?page=hmu_settings') . '">' . __('Settings', _HMU_PLUGIN_NAME_) . '</a>';
            array_unshift($links, $link);
        }

        return $links;
    }

    /**
     * Load the multilanguage support from .mo
     */
    private function loadMultilanguage() {
        if (!defined('WP_PLUGIN_DIR')) {
            load_plugin_textdomain(_HMU_PLUGIN_NAME_, _HMU_PLUGIN_NAME_ . '/languages/');
        } else {
            load_plugin_textdomain(_HMU_PLUGIN_NAME_, null, _HMU_PLUGIN_NAME_ . '/languages/');
        }
    }

    /**
     * Set the header type
     * @param string $type
     */
    public static function setHeader($type) {
        switch ($type) {
            case 'json':
                header('Content-Type: application/json');
        }
    }

    /**
     * Get a value from $_POST / $_GET
     * if unavailable, take a default value
     *
     * @param string $key Value key
     * @param mixed $defaultValue (optional)
     * @param boolean $withcode Set it to true if HTML is allowed
     * @return mixed Value
     */
    public static function getValue($key, $defaultValue = false, $withcode = false) {
        if (!isset($key) OR empty($key) OR !is_string($key))
            return false;
        $ret = (isset($_POST[$key]) ? (is_string($_POST[$key]) ? urldecode($_POST[$key]) : $_POST[$key]) : (isset($_GET[$key]) ? (is_string($_GET[$key]) ? urldecode($_GET[$key]) : $_GET[$key]) : $defaultValue));

        if (is_string($ret) === true && $withcode === false) {
            $ret = sanitize_text_field($ret);
        }

        return !is_string($ret) ? $ret : stripslashes($ret);
    }

    public static function setValue($key, $value) {
        $_POST[$key] = $value;
        $_GET[$key] = $value;
    }

    /**
     * Check if the parameter is set
     *
     * @param string $key
     * @return boolean
     */
    public static function getIsset($key) {
        if (!isset($key) OR empty($key) OR !is_string($key))
            return false;
        return isset($_POST[$key]) ? true : (isset($_GET[$key]) ? true : false);
    }

    /**
     * Show the notices to WP
     *
     * @param $message
     * @param string $type
     * @return string
     */
    public static function showNotices($message, $type = 'hmu_notices') {
        if (file_exists(_HMU_THEME_DIR_ . 'Notices.php')) {
            ob_start();
            include(_HMU_THEME_DIR_ . 'Notices.php');
            $message = ob_get_contents();
            ob_end_clean();
        }

        return $message;
    }

    /**
     * Connect remote with CURL if exists
     *
     * @param $url
     * @param array $param
     * @param array $options
     * @return bool|string
     */
    public static function hmu_remote_get($url, $param = array(), $options = array()) {
        $parameters = '';

        if (isset($param)) {
            foreach ($param as $key => $value) {
                if (isset($key) && $key <> '' && $key <> 'timeout')
                    $parameters .= ($parameters == "" ? "" : "&") . $key . "=" . $value;
            }
        }

        if ($parameters <> '') {
            $url .= ((strpos($url, "?") === false) ? "?" : "&") . $parameters;
        }

        $options['timeout'] = (isset($options['timeout'])) ? $options['timeout'] : 30;
        $options['sslverify'] = false;

        if (!$response = self::hmu_wpcall($url, $options)) {
            if (function_exists('curl_init') && !ini_get('safe_mode') && !ini_get('open_basedir')) {
                $response = self::hmu_curl($url, $options);
            } else {
                return false;
            }
        }

        return $response;
    }

    /**
     * Call remote UR with CURL
     * @param string $url
     * @param array $options
     * @return string
     */
    private static function hmu_curl($url, $options) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        //--
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //--
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);

        if (isset($options['followlocation'])) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
        }

        if ($options['cookie_string'] <> '') {
            curl_setopt($ch, CURLOPT_COOKIE, $options['cookie_string']);
        }

        if (isset($options['User-Agent']) && $options['User-Agent'] <> '') {
            curl_setopt($ch, CURLOPT_USERAGENT, $options['User-Agent']);
        }

        $response = curl_exec($ch);
        $response = self::cleanResponce($response);

        HMU_Debug::dump('CURL', $url, $options, $ch, $response); //output debug

        if (curl_errno($ch) == 1 || $response === false) { //if protocol not supported
            if (curl_errno($ch)) {
                HMU_Debug::dump(curl_getinfo($ch), curl_errno($ch), curl_error($ch));
            }
            curl_close($ch);
            $response = self::hmu_wpcall($url, $options); //use the wordpress call
        } else {
            curl_close($ch);
        }

        return $response;
    }

    /**
     * Use the WP remote call
     * @param string $url
     * @param array $options
     * @return string
     */
    private static function hmu_wpcall($url, $options) {
        $response = wp_remote_get($url, $options);
        if (is_wp_error($response)) {
            HMU_Debug::dump($response);
            return false;
        }

        $response = self::cleanResponce(wp_remote_retrieve_body($response)); //clear and get the body
        HMU_Debug::dump('wp_remote_get', $url, $options, $response); //output debug
        return $response;
    }

    /**
     * Connect remote with CURL if exists
     *
     * @param $url
     * @return array|bool|WP_Error
     */
    public static function hmu_remote_head($url) {
        $response = array();

        if (function_exists('curl_exec')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_exec($ch);

            $response['headers']['content-type'] = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $response['response']['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $response;
        } else {
            return wp_remote_head($url, array('timeout' => 30));
        }
    }

    /**
     * Get the Json from responce if any
     * @param string $response
     * @return string
     */
    private static function cleanResponce($response) {

        if (function_exists('substr_count'))
            if (substr_count($response, '(') > 1)
                return $response;

        if (strpos($response, '(') !== false && strpos($response, ')') !== false)
            $response = substr($response, (strpos($response, '(') + 1), (strpos($response, ')') - 1));

        return $response;
    }

    /**
     * Support for i18n with wpml, polyglot or qtrans
     *
     * @param string $in
     * @return string $in localized
     */
    public static function i18n($in) {
        if (function_exists('langswitch_filter_langs_with_message')) {
            $in = langswitch_filter_langs_with_message($in);
        }
        if (function_exists('polyglot_filter')) {
            $in = polyglot_filter($in);
        }
        if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
            $in = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($in);
        }
        $in = apply_filters('localization', $in);
        return $in;
    }

    /**
     * Convert integer on the locale format.
     *
     * @param int $number The number to convert based on locale.
     * @param int $decimals Precision of the number of decimal places.
     * @return string Converted number in string format.
     */
    public static function i18n_number_format($number, $decimals = 0) {
        global $wp_locale;
        $formatted = number_format($number, absint($decimals), $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep']);
        return apply_filters('number_format_i18n', $formatted);
    }


    /**
     * Returns true if server is Apache
     *
     * @return boolean
     */
    public static function isApache() {
        return (isset($_SERVER['SERVER_SOFTWARE']) && stristr($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false);
    }

    /**
     * Check whether server is LiteSpeed
     *
     * @return bool
     */
    public static function isLitespeed() {
        return (isset($_SERVER['SERVER_SOFTWARE']) && stristr($_SERVER['SERVER_SOFTWARE'], 'LiteSpeed') !== false);
    }

    /**
     * Check if multisites with path
     *
     * @return bool
     */
    public static function isMultisites() {
        if (!isset(self::$is_multisite)) {
            self::$is_multisite = (is_multisite() && ((defined('SUBDOMAIN_INSTALL') && !SUBDOMAIN_INSTALL) || (defined('VHOST') && VHOST == 'no')));
        }
        return self::$is_multisite;
    }

    /**
     * Returns true if server is nginx
     *
     * @return boolean
     */
    public static function isNginx() {
        return (isset($_SERVER['SERVER_SOFTWARE']) && stristr($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false);
    }


    public static function emptyCache() {
        if (function_exists('w3tc_pgcache_flush')) {
            w3tc_pgcache_flush();
        }
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }
    }

    public function hmu_activate() {
        set_transient('hmu_activate', true);
    }

    public function hmu_deactivate() {
        if (!self::getValue('force', false)) {
            self::$options = self::$default;
            self::saveOptions();

            if (HMU_Classes_ObjController::getClass('HMU_Models_Rewrite')->hmu_remove_config_cache()) {
                global $wp_rewrite;
                $wp_rewrite->flush_rules();

            } else {
                HMU_Classes_ObjController::getClass('HMU_Classes_Error')->hookNotices();
                echo sprintf(__("%sClick here%s when you're done.", _HMU_PLUGIN_NAME_), '<a href="javascript:void;" onclick="location.href=location.href+\'&force=true\'">', '</a>');
                exit();
            }
        }
    }

    public static function checkUpgrade() {

    }

    public static function getRelativePath($url) {
        $url = wp_make_link_relative($url);
        if ($url <> '') {
            if (is_multisite() && defined('PATH_CURRENT_SITE')) {
                $url = str_replace(rtrim(PATH_CURRENT_SITE, '/'), '', $url);
                $url = trim($url, '/');
                $url = $url . '/';
            } else {
                $url = str_replace(wp_make_link_relative(get_bloginfo('url')), '', $url);
                $url = trim($url, '/');
            }
        }

        if (strpos($url, '/') === false) {
            $url = '/' . $url;
        }

        return $url;
    }

    public static function sendEmail() {
        global $current_user;

        $line = "\n" . "________________________________________" . "\n";
        $to = $current_user->user_email;
        $from = 'no-reply@wpplugins.tips';
        $subject = __('Hide My URLs - New Login Data', _HMU_PLUGIN_NAME_);
        $message = "Thank you for using Hide My URLs!" . "\n";
        $message .= $line;
        $message .= "Your new site URLs are:" . "\n";
        $message .= "Admin URL: " . site_url(self::getOption('hmu_admin_url')) . "\n";
        $message .= "Login URL: " . site_url(self::getOption('hmu_login_url')) . "\n";
        $message .= $line;
        $message .= "Note: If you can't login to your site, just access this URL: \n";
        $message .= site_url() . "/wp-login.php?hmu_disable=" . self::getOption('hmu_disable') . "\n\n";
        $message .= "Need Advanced Security? Save up to $100 TODAY! Use coupon HMWUW16 for Unlimited Websites" . "\n";
        $message .= "http://wpplugins.tips/wordpress" . "\n\n";
        $message .= "Best regards," . "\n";
        $message .= "Wpplugins.tips Team" . "\n";
        $headers = array();
        $headers[] = 'From: Hide Wp URLs <' . $from . '>';
        $headers[] = 'Content-type: text/plain';

        if (@wp_mail($to, $subject, $message, $headers)) {
            return true;
        }

        return false;
    }

}