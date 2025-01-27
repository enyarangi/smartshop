<?php

/**
 * The class handles the theme part in WP
 */
class HMU_Classes_DisplayController {

    private static $cache;

    /**
     * echo the css link from theme css directory
     *
     * @param string $uri The name of the css file or the entire uri path of the css file
     * @param string $media
     *
     * @return string
     */
    public static function loadMedia($uri = '', $media = 'all') {
        $css_uri = '';
        $js_uri = '';

        if (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], '/admin-ajax.php') !== false)
            return;

        if (isset(self::$cache[$uri]))
            return;

        self::$cache[$uri] = true;

        /* if is a custom css file */
        if (strpos($uri, '//') === false) {
            $name = strtolower($uri);
            if (file_exists(_HMU_THEME_DIR_ . 'css/' . $name . '.css')) {
                $css_uri = _HMU_THEME_URL_ . 'css/' . $name . '.css?ver=' . HMU_VERSION_ID;
            }
            if (file_exists(_HMU_THEME_DIR_ . 'js/' . $name . '.js')) {
                $js_uri = _HMU_THEME_URL_ . 'js/' . $name . '.js?ver=' . HMU_VERSION_ID;
            }
        } else {
            $name = strtolower(basename($uri));
            if (strpos($uri, '.css') !== FALSE)
                $css_uri = $uri;
            elseif (strpos($uri, '.js') !== FALSE) {
                $js_uri = $uri;
            }
        }

        if ($css_uri <> '') {

            if (!wp_style_is($name)) {
                wp_enqueue_style($name, $css_uri, null, HMU_VERSION_ID, $media);
            }

            wp_print_styles(array($name));
        }

        if ($js_uri <> '') {

            if (!wp_script_is($name)) {
                wp_enqueue_script($name, $js_uri, array('jquery'), HMU_VERSION_ID, true);
            }

            wp_print_scripts(array($name));
        }
    }

    /**
     * return the block content from theme directory
     *
     * @return string
     */
    public function getView($block, $view) {
        $output = null;
        if (file_exists(_HMU_THEME_DIR_ . $block . '.php')) {

            ob_start();
            include(_HMU_THEME_DIR_ . $block . '.php');
            $output .= ob_get_clean();
        }

        return $output;
    }

}