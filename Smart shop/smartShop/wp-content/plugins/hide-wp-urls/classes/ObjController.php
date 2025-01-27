<?php

/**
 * The class creates object for plugin classes
 */
class HMU_Classes_ObjController {

    /** @var array of instances */
    public static $instances;

    /** @var array from core config */
    public static $config;

    public static function getClass($className, $args = array()) {

        if ($class = self::getClassPath($className)) {
            if (!isset(self::$instances[$className])) {
                /* check if class is already defined */
                if (!class_exists($className) || $className == get_class()) {
                    self::includeClass($class['dir'], $class['name']);

                    //check if abstract
                    $check = new ReflectionClass($className);
                    $abstract = $check->isAbstract();
                    if (!$abstract) {
                        self::$instances[$className] = new $className();
                        if (!empty($args)) {
                            call_user_func_array(array(self::$instances[$className], '__construct'), $args);
                        }
                        return self::$instances[$className];
                    } else {
                        self::$instances[$className] = true;
                    }
                } else {

                }
            } else
                return self::$instances[$className];
        }
        return false;
    }

    private static function includeClass($classDir, $className) {

        if (file_exists($classDir . $className . '.php'))
            try {
                include_once($classDir . $className . '.php');
            } catch (Exception $e) {
                throw new Exception('Controller Error: ' . $e->getMessage());
            }
    }

    public static function getDomain($className, $args = array()) {
        if ($class = self::getClassPath($className)) {

            /* check if class is already defined */

            self::includeClass($class['dir'], $class['name']);
            return new $className($args);
        }
        throw new Exception('Could not create domain: ' . $className);
    }

    /**
     * Check if the class is correctly set
     *
     * @param string $className
     * @return boolean
     */
    private static function checkClassPath($className) {
        $path = preg_split('/[_]+/', $className);
        if (is_array($path) && count($path) > 1) {
            if (in_array(_HMU_NAMESPACE_, $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the path of the class and name of the class
     *
     * @param string $className
     * @return array | boolean
     * array(
     * dir - absolute path of the class
     * name - the name of the file
     * }
     */
    public static function getClassPath($className) {
        $dir = '';


        if (self::checkClassPath($className)) {
            $path = preg_split('/[_]+/', $className);
            for ($i = 1; $i < sizeof($path) - 1; $i++)
                $dir .= strtolower($path[$i]) . '/';

            $class = array('dir' => _HMU_ROOT_DIR_ . '/' . $dir,
                'name' => $path[sizeof($path) - 1]);

            if (file_exists($class['dir'] . $class['name'] . '.php')) {
                return $class;
            }
        }
        return false;
    }

}