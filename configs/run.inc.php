<?php
/**
 * @license    tnjaile
 * @author     tnjaile
 * @version    1.0
 * 模組首先執行的檔案
 **/
//載入檔頭
if (strpos($_SERVER["SCRIPT_NAME"], '/admin/') !== false) {
    $xoopsOption['template_main'] = basename(dirname(__DIR__)) . '_admin_main.tpl';
    include_once "header.php";
} else {
    $xoopsOption['template_main'] = basename(dirname(__DIR__)) . '_index.tpl';
    include_once '../../mainfile.php';
    include_once XOOPS_ROOT_PATH . "/header.php";
}
//引入系统配置文件
include_once XOOPS_ROOT_PATH . "/modules/jillbase/profile.inc.php";
//自動加載類
include_once XOOPS_ROOT_PATH . "/modules/ck_signup/configs/autoload.inc.php";

include_once CK_SIGNUP_DIR . "/header.php";
