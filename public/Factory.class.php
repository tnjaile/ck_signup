<?php
/**
 * @license    tnjaile
 * @author     tnjaile
 * @version    1.0
 * 單入口變簡單工廠模式
 **/

final class Factory
{

    private static $_obj = null;

    public static function setModel()
    {
        $_class = substr(basename($_SERVER["SCRIPT_NAME"]), 0, -4);

        if (file_exists(模組_DIR . '/model/' . ucfirst($_class) . 'Model.class.php')) {
            eval('self::$_obj = new ' . ucfirst($_class) . 'Model();');
        }

        return self::$_obj;
    }

}
