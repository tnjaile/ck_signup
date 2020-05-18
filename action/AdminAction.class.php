<?php
/**
 * 管理員控制器(後台首頁)
 **/
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

        $this->_tpl->assign('AllAction', $_AllAction);
        $this->_tpl->assign('now_op', 'action_list');
        $this->_tpl->assign('action', $_SERVER["PHP_SELF"]);
    }

    // 新增
    public function actions_form()
    {
        $this->_tpl->assign('now_op', "actions_form");
        $this->_tpl->assign('action', $_SERVER['PHP_SELF']);
    }
}
