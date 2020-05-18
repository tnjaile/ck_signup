<?php
/**
 * @copyright  The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license    tnjaile
 * @package
 * @since      2.5
 * @author     tnjaile
 * @version    1.0
 * 前台首頁
 **/
/*-----------引入檔案區--------------*/
use XoopsModules\Tadtools\Utility;
include_once "configs/run.inc.php";
$_obj = new SignUpIndexAction();
$_obj->run();
// /*-----------秀出結果區--------------*/
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
include_once XOOPS_ROOT_PATH . '/footer.php';
