<?php
/**
 * @license    tnjaile
 * @author     tnjaile
 * @version    1.0
 * 自動加載類
 **/
define('CK_SIGNUP_DIR', XOOPS_ROOT_PATH . "/modules/" . basename(dirname(__DIR__)));

class CkSignupAuto
{
    public static $_map = [
        'Action'   => XOOPS_ROOT_PATH . "/modules/jillbase/lib/Action.class.php",
        'Model'    => XOOPS_ROOT_PATH . "/modules/jillbase/lib/Model.class.php",
        'Check'    => XOOPS_ROOT_PATH . "/modules/jillbase/lib/Check.class.php",
        'DB'       => XOOPS_ROOT_PATH . "/modules/jillbase/public/DB.class.php",
        'Redirect' => XOOPS_ROOT_PATH . "/modules/jillbase/public/Redirect.class.php",
        'Request'  => XOOPS_ROOT_PATH . "/modules/jillbase/public/Request.class.php",
        'Tool'     => XOOPS_ROOT_PATH . "/modules/jillbase/public/Tool.class.php",
        'Validate' => XOOPS_ROOT_PATH . "/modules/jillbase/public/Validate.class.php",
        'Group'    => XOOPS_ROOT_PATH . "/modules/jillbase/public/Group.class.php",
    ];
    public static function auto($_className)
    {
        $vendor_path = dirname(__DIR__);
        if (isset(self::$_map[$_className])) {
            include_once self::$_map[$_className];
        } elseif (substr($_className, -6) == 'Action') {
            //控制類
            $path = $vendor_path . '/action/' . $_className . '.class.php';
            if (file_exists($path)) {
                include_once $path;
            }
        } elseif (substr($_className, -5) == 'Model') {
            //model
            $path = $vendor_path . '/model/' . $_className . '.class.php';
            if (file_exists($path)) {
                include_once $path;
            }
        } elseif (substr($_className, -5) == 'Check') {
            //Check(驗證類)
            $path = $vendor_path . '/check/' . $_className . '.class.php';
            if (file_exists($path)) {
                include_once $path;
            }
        } else {
            $path = $vendor_path . '/public/' . $_className . '.class.php';
            if (file_exists($path)) {
                include_once $path;
            }

        }
    }
}
spl_autoload_register('CkSignupAuto::auto');
