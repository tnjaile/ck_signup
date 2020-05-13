<?php
/**
 * Ck Signup module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package    Ck Signup
 * @since      2.5
 * @author     ck2工作室
 * @version    $Id $
 **/
//判斷是否對該模組有管理權限
$interface_menu[_TAD_TO_MOD] = "index.php";
$interface_icon[_TAD_TO_MOD] = "fa-chevron-right";
if ($xoopsUser) {
    $modhandler  = xoops_gethandler('module');
    $xoopsModule = $modhandler->getByDirname("ck_signup");
    $module_id   = $xoopsModule->getVar('mid');
    if (!isset($_SESSION['ck_signup_adm'])) {
        $_SESSION['ck_signup_adm'] = $xoopsUser->isAdmin($module_id);
    }
    if ($_SESSION['ck_signup_adm']) {
        $interface_menu[_TAD_TO_ADMIN] = "admin/main.php";
        $interface_icon[_TAD_TO_ADMIN] = "fa-sign-in";
    }
}
