<?php
/**
 * 管理員控制器(後台首頁)
 **/
class AdminAction extends Action
{
    private $_物件 = null;
    public function __construct()
    {
        parent::__construct();
        // $this->_物件 = new SetModel();--用到的物件
    }

    //載入資訊
    public function main()
    {
        if (isset($_GET['編號'])) {
            /****此處插入要做的事件 */
            $this->_tpl->assign('action', $_SERVER["PHP_SELF"]);
        }
        $this->_tpl->assign('now_op', '引入樣板');
    }

    public function add()
    {
        if (isset($_GET['編號'])) {
            if (isset($_POST['send'])) {
                /***表單接收 */
                $this->_model->物件_add();
            }

            //套用formValidator驗證機制
            $formValidator      = new FormValidator("#myForm", true);
            $formValidator_code = $formValidator->render();

            //加入Token安全機制
            include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
            $token      = new \XoopsFormHiddenToken();
            $token_form = $token->render();
            $xoopsTpl->assign("token_form", $token_form);
            $xoopsTpl->assign('formValidator_code', $formValidator_code);
        }
        $this->_tpl->assign('now_op', '引入樣板_form');

    }

}
