<?php
namespace XoopsModules\Ck_signup;

use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Ck_signup\Signups;
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


class Signups
{
    //列出所有 signups 資料
    public static function index($action_id = '')
    {
        global $xoopsDB, $xoopsTpl;

        $myts = \MyTextSanitizer::getInstance();
        
        $where_action_id = empty($action_id) ? '' : "where `action_id` = '$action_id'";
        $sql = "select * from `" . $xoopsDB->prefix("signups") . "` {$where_action_id} ";

        //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = Utility::getPageBar($sql, 20, 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];
        $xoopsTpl->assign('bar', $bar);
        $xoopsTpl->assign('total', $total);

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        
        //取得分類所有資料陣列
        $actions_arr = Actions::get_all();
        $xoopsTpl->assign('actions_arr', $actions_arr);
    
        $all_signups = [];
        while($all = $xoopsDB->fetchArray($result))
        {
            //過濾讀出的變數值
            $all['uid'] = (int) $all['uid'];
            $all['action_id'] = (int) $all['action_id'];
            $all['signup_date'] = $myts->htmlSpecialChars($all['signup_date']);
    
            
            //取得分類標題
            $all['action_id_title'] = $actions_arr[$all['action_id']]['title'];
            $all_signups[] = $all;
        }

        //刪除確認的JS
        $SweetAlert   = new SweetAlert();
        $SweetAlert->render('signups_destroy_func',
        "{$_SERVER['PHP_SELF']}?op=signups_destroy&uid_action_id=", "uid_action_id");

        
        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
        $xoopsTpl->assign('all_signups', $all_signups);
    }


    //signups編輯表單
    public static function create($uid_action_id = '' , $action_id = '')
    {
        global $xoopsDB, $xoopsTpl, $xoopsUser;
        chk_is_adm();

        //抓取預設值
        $DBV = !empty($uid_action_id)? self::get($uid_action_id) : [];

        //預設值設定
        
        //設定 uid 欄位的預設值
        $uid = !isset($DBV['uid']) ? $uid : $DBV['uid'];
        $xoopsTpl->assign('uid', $uid);
        //設定 action_id 欄位的預設值
        $action_id = !isset($DBV['action_id']) ? $action_id : $DBV['action_id'];
        $xoopsTpl->assign('action_id', $action_id);
        //設定 signup_date 欄位的預設值
        $signup_date = !isset($DBV['signup_date']) ? date("Y-m-d H:i:s") : $DBV['signup_date'];
        $xoopsTpl->assign('signup_date', $signup_date);

        $op = empty($uid_action_id) ? "signups_store" : "signups_update";

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        
        //活動編號
        $sql = "select `action_id`, `title` from `".$xoopsDB->prefix("actions")."` ";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $i=0;
        $action_id_options_array = array();
        while(list($action_id,$title) = $xoopsDB->fetchRow($result)){
            $action_id_options_array[$i]['action_id']=$action_id;
            $action_id_options_array[$i]['title']=$title;
            $i++;
        }
        $xoopsTpl->assign("action_id_options", $action_id_options_array);
    
    
        //加入Token安全機制
        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);
        $xoopsTpl->assign('action', $_SERVER["PHP_SELF"]);
        $xoopsTpl->assign('next_op', $op);
    }


    //新增資料到signups中
    public static function store()
    {
        global $xoopsDB, $xoopsUser;
        chk_is_adm();

        
        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();
        
        $uid = (int) $_POST['uid'];
        $action_id = (int) $_POST['action_id'];
        $signup_date = date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));

        $sql = "insert into `" . $xoopsDB->prefix("signups") . "` (
        `uid`, 
        `action_id`, 
        `signup_date`
        ) values(
        '{$uid}', 
        '{$action_id}', 
        '{$signup_date}'
        )";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $uid_action_id = $xoopsDB->getInsertId();
        
        return $uid_action_id;
    }


    //以流水號秀出某筆signups資料內容
    public static function show($uid_action_id = '')
    {
        global $xoopsDB, $xoopsTpl;

        if(empty($uid_action_id))
        {
            return;
        }

        $uid_action_id = (int) $uid_action_id;
        $all = self::get($uid_action_id);

        $myts = \MyTextSanitizer::getInstance();
        //過濾讀出的變數值
        $all['uid'] = (int) $all['uid'];
        $all['action_id'] = (int) $all['action_id'];
        $all['signup_date'] = $myts->htmlSpecialChars($all['signup_date']);
    
        //以下會產生這些變數： $uid, $action_id, $signup_date
        foreach($all as $k => $v)
        {
            $$k = $v;
            $xoopsTpl->assign($k, $v);
        }

        
        //取得分類資料(actions)
        $actions_arr = Actions::get($action_id);
        $xoopsTpl->assign('actions_arr', $actions_arr);
        $xoopsTpl->assign('action_id_title', $actions_arr['title']);
    

        $SweetAlert   = new SweetAlert();
        $SweetAlert->render('signups_destroy_func', "{$_SERVER['PHP_SELF']}?op=signups_destroy&uid_action_id=", "uid_action_id");

        $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    }


    //更新signups某一筆資料
    public static function update($uid_action_id = '')
    {
        global $xoopsDB, $xoopsUser;
        chk_is_adm();

        
        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();
        
        $uid = (int) $_POST['uid'];
        $action_id = (int) $_POST['action_id'];
        $signup_date = date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));

        $sql = "update `" . $xoopsDB->prefix("signups") . "` set 
        `uid` = '{$uid}', 
        `action_id` = '{$action_id}', 
        `signup_date` = '{$signup_date}'
        where `uid_action_id` = '$uid_action_id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        
        return $uid_action_id;
    }


    //刪除signups某筆資料資料
    public static function destroy($uid_action_id = '')
    {
        global $xoopsDB;
        chk_is_adm();

        if(empty($uid_action_id))
        {
            return;
        }

        $sql = "delete from `" . $xoopsDB->prefix("signups") . "`
        where `uid_action_id` = '{$uid_action_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        
    }


    //以流水號取得某筆signups資料
    public static function get($uid_action_id = '')
    {
        global $xoopsDB;

        if(empty($uid_action_id))
        {
            return;
        }

        $sql = "select * from `" . $xoopsDB->prefix("signups") . "`
        where `uid_action_id` = '{$uid_action_id}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }


    //取得signups所有資料陣列
    public static function get_all()
    {
        global $xoopsDB;
        $sql = "select * from `" . $xoopsDB->prefix("signups") . "`";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        while($data = $xoopsDB->fetchArray($result))
        {
            $uid_action_id = $data['uid_action_id'];
            $data_arr[$uid_action_id] = $data;
        }
        return $data_arr;
    }


    //新增signups計數器
    public static function add_counter($uid_action_id = '')
    {
        global $xoopsDB;

        if(empty($uid_action_id))
        {
            return;
        }

        $sql = "update `" . $xoopsDB->prefix("signups") . "` set `` = `` + 1
        where `uid_action_id` = '{$uid_action_id}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }


    //自動取得signups的最新排序
    public static function max_sort()
    {
        global $xoopsDB;
        $sql = "select max(``) from `" . $xoopsDB->prefix("signups") . "`";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }

}
