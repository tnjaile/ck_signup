<?php
/**
 * @license    tnjaile
 * @author     tnjaile
 * @version    1.0
 * 後台頁面
 **/

/*-----------引入檔案區--------------*/
include_once "../configs/run.inc.php";
$_SESSION['ck_signup_adm'] = true;
$_obj                      = new AdminAction();
$_obj->run();
/*-----------秀出結果區--------------*/
$xoTheme->addStylesheet('/modules/tadtools/css/font-awesome/css/font-awesome.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/xoops_adm3.css');
include_once 'footer.php';
