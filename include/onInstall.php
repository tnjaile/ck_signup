<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

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


function xoops_module_install_ck_signup(&$module)
{

    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/ck_signup");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/ck_signup/file");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/ck_signup/image");
    Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/ck_signup/image/.thumbs");

    return true;
}
