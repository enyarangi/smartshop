<?php

/**
 * Set the ajax action and call for wordpress
 */
class HMU_Classes_Action extends HMU_Classes_FrontController {

    /** @var array with all form and ajax actions  */
    var $actions = array();

    /** @var array from core config */
    private static $config;

    /**
     * The hookAjax is loaded as custom hook in hookController class
     *
     * @return void
     */
    function hookInit() {
        /* Only if ajax */
        if (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], '/admin-ajax.php') !== false) {
            $this->actions = array();
            $this->getActions(((isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : ''))));
        }
    }


    /**
     * The hookSubmit is loaded when action si posted
     *
     * @return void
     */
    function hookMenu() {

        /* Only if post */
        if (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], '/admin-ajax.php') !== false) {
            return;
        }

        $this->actions = array();
        $this->getActions(((isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : ''))));
    }

    function hookFrontinit() {
        /* Only if post */
        if (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], '/admin-ajax.php') !== false) {
            return;
        }

        $this->actions = array();
        $this->getActions(((isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : ''))));
    }


    /**
     * Get all actions from config.xml in core directory and add them in the WP
     *
     * @param $cur_action
     */
    public function getActions($cur_action) {
        /* if config allready in cache */
        if (!isset(self::$config)) {
            $config_file = _HMU_ROOT_DIR_ . '/config.xml';
            if (!file_exists($config_file))
                return;

            /* load configuration blocks data from core config files */
            $data = file_get_contents($config_file);
            self::$config = json_decode(json_encode((array) simplexml_load_string($data)), 1);
        }

        if (is_array(self::$config))
            foreach (self::$config['block'] as $block) {
                if (isset($block['active']) && $block['active'] == 1)
                    if (isset($block['admin']) &&
                            (($block['admin'] == 1 && (is_admin() || is_network_admin())) ||
                            $block['admin'] == 0)
                    ) {
                        /* if there is a single action */
                        if (isset($block['actions']['action']))

                        /* if there are more actions for the current block */
                            if (!is_array($block['actions']['action'])) {
                                /* add the action in the actions array */
                                if ($block['actions']['action'] == $cur_action)
                                    $this->actions[] = array('class' => $block['name']);
                            }else {
                                /* if there are more actions for the current block */
                                foreach ($block['actions']['action'] as $action) {
                                    /* add the actions in the actions array */
                                    if ($action == $cur_action)
                                        $this->actions[] = array('class' => $block['name']);
                                }
                            }
                    }
            }

        /* add the actions in WP */
        foreach ($this->actions as $actions) {
            HMU_Classes_ObjController::getClass($actions['class'])->action();
        }
    }

}
