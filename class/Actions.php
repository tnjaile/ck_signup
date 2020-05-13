<?php
namespace XoopsModules\Ck_signup;

use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Ck_signup\Actions;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\FormValidator;

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


class Actions
{
    //列出所有 actions 資料
    public static function index()
    {
        global $xoopsDB, $xoopsTpl;

        $myts = \MyTextSanitizer::getInstance();
        
        $sql = "select * from `" . $xoopsDB->prefix("actions") . "` ";

        //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = Utility::getPageBar($sql, 20, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];
        $xoopsTpl->assign('bar', $bar);
        $xoopsTpl->assign('total', $total);

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        
        $all_actions = [];
        while($all = $xoopsDB->fetchArray($result))
        {
            //過濾讀出的變數值
            $all['action_id'] = (int) $all['action_id'];
            $all['title'] = $myts->htmlSpecialChars($all['title']);
            $all['content'] = $myts->displayTarea($all['content'], 1, 1, 0, 1, 0);
            $all['action_date'] = $myts->htmlSpecialChars($all['action_date']);
            $all['end_date'] = $myts->htmlSpecialChars($all['end_date']);
            $all['uid'] = (int) $all['uid'];
            $all['enable'] = (int) $all['enable'];
    
            
            //將 uid 編號轉換成使用者姓名（或帳號）
            $all['uid_name'] = \XoopsUser::getUnameFromId($all['uid'], 1);
            if(empty($all['uid_name']))$all['uid_name'] = \XoopsUser::getUnameFromId($all['uid'], 0);
            //將是否選項轉換為圖示
            $all['enable_pic'] = $all['enable']==1 ? '<img src="'.XOOPS_URL.'/modules/ck_signup/images/yes.gif" alt="'._YES.'" title="'._YES.'">' : '<img src="'.XOOPS_URL.'/modules/ck_signup/images/no.gif" alt="'._NO.'" title="'._NO.'">';
    
            $all_actions[] = $all;
        }

        //刪除確認的JS
        $SweetAlert   = new SweetAlert();
        $SweetAlert->render('actions_destroy_func',
        "{$_SERVER['PHP_SELF']}?op=actions_destroy&action_id=", "action_id");

        
        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
        $xoopsTpl->assign('all_actions', $all_actions);
    }


    //actions編輯表單
    public static function create($action_id = '' )
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser;
        chk_is_adm();

        //抓取預設值
        $DBV = !empty($action_id)? self::get($action_id) : [];

        //預設值設定
        
        //設定 action_id 欄位的預設值
        $action_id = !isset($DBV['action_id']) ? $action_id : $DBV['action_id'];
        $xoopsTpl->assign('action_id', $action_id);
        //設定 title 欄位的預設值
        $title = !isset($DBV['title']) ? '' : $DBV['title'];
        $xoopsTpl->assign('title', $title);
        //設定 content 欄位的預設值
        $content = !isset($DBV['content']) ? '' : $DBV['content'];
        $xoopsTpl->assign('content', $content);
        //設定 action_date 欄位的預設值
        $action_date = !isset($DBV['action_date']) ? date("Y-m-d") : $DBV['action_date'];
        $xoopsTpl->assign('action_date', $action_date);
        //設定 end_date 欄位的預設值
        $end_date = !isset($DBV['end_date']) ? date("Y-m-d H:i") : $DBV['end_date'];
        $xoopsTpl->assign('end_date', $end_date);
        //設定 uid 欄位的預設值
        $user_uid = $xoopsUser ? $xoopsUser->uid() : "";
        $uid = !isset($DBV['uid']) ? $user_uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);
        //設定 enable 欄位的預設值
        $enable = !isset($DBV['enable']) ? '0' : $DBV['enable'];
        $xoopsTpl->assign('enable', $enable);

        $op = empty($action_id) ? "actions_store" : "actions_update";

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        //活動說明
        $ck = new CkEditor("ck_signup","content",$content);
        $ck->setHeight(200);
        $editor = $ck->render();
        $xoopsTpl->assign('content_editor', $editor);
    
    
        //加入Token安全機制
        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);
        $xoopsTpl->assign('action', $_SERVER["PHP_SELF"]);
        $xoopsTpl->assign('next_op', $op);
    }


    //新增資料到actions中
    public static function store()
    {
        global $xoopsDB, $xoopsUser;
        chk_is_adm();

        
        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();
        
        $action_id = (int) $_POST['action_id'];
        $title = $myts->addSlashes($_POST['title']);
        $content = $myts->addSlashes($_POST['content']);
        $action_date = $myts->addSlashes($_POST['action_date']);
        $end_date = $myts->addSlashes($_POST['end_date']);
        $uid = (int) $_POST['uid'];
        $enable = (int) $_POST['enable'];

        $sql = "insert into `" . $xoopsDB->prefix("actions") . "` (
        `title`, 
        `content`, 
        `action_date`, 
        `end_date`, 
        `uid`, 
        `enable`
        ) values(
        '{$title}', 
        '{$content}', 
        '{$action_date}', 
        '{$end_date}', 
        '{$uid}', 
        '{$enable}'
        )";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $action_id = $xoopsDB->getInsertId();
        
        return $action_id;
    }


    //以流水號秀出某筆actions資料內容
    public static function show($action_id = '')
    {
        global $xoopsDB, $xoopsTpl;

        if(empty($action_id))
        {
            return;
        }

        $action_id = (int) $action_id;
        $all = self::get($action_id);

        $myts = \MyTextSanitizer::getInstance();
        //過濾讀出的變數值
        $all['action_id'] = (int) $all['action_id'];
        $all['title'] = $myts->htmlSpecialChars($all['title']);
        $all['content'] = $myts->displayTarea($all['content'], 1, 1, 0, 1, 0);
        $all['action_date'] = $myts->htmlSpecialChars($all['action_date']);
        $all['end_date'] = $myts->htmlSpecialChars($all['end_date']);
        $all['uid'] = (int) $all['uid'];
        $all['enable'] = (int) $all['enable'];
    
        //以下會產生這些變數： $title, $content, $action_date, $end_date, $uid, $enable
        foreach($all as $k => $v)
        {
            $$k = $v;
            $xoopsTpl->assign($k, $v);
        }

        
        //將 uid 編號轉換成使用者姓名（或帳號）
        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        if(empty($uid_name)) $uid_name = \XoopsUser::getUnameFromId($uid , 0);
        $xoopsTpl->assign('uid_name', $uid_name);
    
        //將是否選項轉換為圖示
        $enable_pic = ($enable==1)? '<img src="'.XOOPS_URL.'/modules/ck_signup/images/yes.gif" alt="'._YES.'" title="'._YES.'">' : '<img src="'.XOOPS_URL.'/modules/ck_signup/images/no.gif" alt="'._NO.'" title="'._NO.'">';
        $xoopsTpl->assign('enable_pic', $enable_pic);
    

        $SweetAlert   = new SweetAlert();
        $SweetAlert->render('actions_destroy_func', "{$_SERVER['PHP_SELF']}?op=actions_destroy&action_id=", "action_id");

        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    }


    //更新actions某一筆資料
    public static function update($action_id = '')
    {
        global $xoopsDB, $xoopsUser;
        chk_is_adm();

        
        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();
        
        $action_id = (int) $_POST['action_id'];
        $title = $myts->addSlashes($_POST['title']);
        $content = $myts->addSlashes($_POST['content']);
        $action_date = $myts->addSlashes($_POST['action_date']);
        $end_date = $myts->addSlashes($_POST['end_date']);
        $uid = (int) $_POST['uid'];
        $enable = (int) $_POST['enable'];

        $sql = "update `" . $xoopsDB->prefix("actions") . "` set 
        `title` = '{$title}', 
        `content` = '{$content}', 
        `action_date` = '{$action_date}', 
        `end_date` = '{$end_date}', 
        `uid` = '{$uid}', 
        `enable` = '{$enable}'
        where `action_id` = '$action_id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        
        return $action_id;
    }


    //刪除actions某筆資料資料
    public static function destroy($action_id = '')
    {
        global $xoopsDB;
        chk_is_adm();

        if(empty($action_id))
        {
            return;
        }

        $sql = "delete from `" . $xoopsDB->prefix("actions") . "`
        where `action_id` = '{$action_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        
    }


    //以流水號取得某筆actions資料
    public static function get($action_id = '')
    {
        global $xoopsDB;

        if(empty($action_id))
        {
            return;
        }

        $sql = "select * from `" . $xoopsDB->prefix("actions") . "`
        where `action_id` = '{$action_id}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }


    //取得actions所有資料陣列
    public static function get_all()
    {
        global $xoopsDB;
        $sql = "select * from `" . $xoopsDB->prefix("actions") . "`";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        while($data = $xoopsDB->fetchArray($result))
        {
            $action_id = $data['action_id'];
            $data_arr[$action_id] = $data;
        }
        return $data_arr;
    }


    //新增actions計數器
    public static function add_counter($action_id = '')
    {
        global $xoopsDB;

        if(empty($action_id))
        {
            return;
        }

        $sql = "update `" . $xoopsDB->prefix("actions") . "` set `` = `` + 1
        where `action_id` = '{$action_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }


    //自動取得actions的最新排序
    public static function max_sort()
    {
        global $xoopsDB;
        $sql = "select max(``) from `" . $xoopsDB->prefix("actions") . "`";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }

}
