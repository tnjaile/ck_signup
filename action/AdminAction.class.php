<?php
/**
 * 管理員控制器(後台首頁)
 **/
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;

class AdminAction extends Action
{
    private $_action = null;
    public function __construct()
    {
        parent::__construct();
        $this->_action = new ActionsModel();
    }

    //載入資訊
    public function main()
    {
        $_AllAction = $this->_action->findAll();

        $sweet_alert = new SweetAlert();
        $sweet_alert->render('delete_action',
            "{$_SERVER['PHP_SELF']}?op=delete&action_id=", "action_id");
        $this->_tpl->assign('AllAction', $_AllAction);
        $this->_tpl->assign('now_op', 'action_list');
        $this->_tpl->assign('action', $_SERVER["PHP_SELF"]);
    }

    // 新增、編輯
    public function actions_form()
    {

        if (isset($_POST['send'])) {
            //XOOPS表單安全檢查
            if (!$GLOBALS['xoopsSecurity']->check()) {
                $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
                redirect_header($_SERVER['PHP_SELF'], 3, $error);
            }
            if (isset($_POST['next_op'])) {
                if ($_POST['next_op'] == "update") {

                    if ($this->_action->actions_update()) {
                        $_message = "修改成功!";
                    } else {
                        $_message = "修改失敗!";
                    }

                }

                if ($_POST['next_op'] == "add") {
                    if ($this->_action->actions_add()) {
                        $_message = "新增成功!";
                    } else {
                        $_message = "新增失敗!";
                    }
                }
            }

            redirect_header($_SERVER['PHP_SELF'], 3, $_message);
        }

        if (isset($_GET['action_id'])) {
            $_OneAction = $this->_action->findOne();
            $this->_tpl->assign('next_op', "update");
        } else {
            $_OneAction['enable'] = 1;
            $this->_tpl->assign('next_op', "add");
        }

        // 引入ckeditor
        $_content = (empty($_OneAction['content'])) ? "" : $_OneAction['content'];
        $ck       = new CkEditor("ck_signup", "content", $_content);
        $ck->setHeight(200);
        $this->_tpl->assign('content_editor', $ck->render());
        //套用formValidator驗證機制
        $formValidator      = new FormValidator("#myForm", true);
        $formValidator_code = $formValidator->render();
        //加入Token安全機制
        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        $token      = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $this->_tpl->assign("token_form", $token_form);
        $this->_tpl->assign('now_op', "actions_form");
        $this->_tpl->assign('action', $_SERVER['PHP_SELF']);
        $this->_tpl->assign("OneAction", $_OneAction);

    }

}
