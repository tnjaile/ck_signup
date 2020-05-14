<?php
/**
 * 管理員控制器(後台首頁)
 **/
class AdminAction extends Action
{
    // private $_物件 = null;
    public function __construct()
    {
        parent::__construct();
        // $this->_物件 = new SetModel();--用到的物件
    }

    //載入資訊
    public function main()
    {

        $this->_tpl->assign('now_op', 'action_list');
        $this->_tpl->assign('action', $_SERVER["PHP_SELF"]);
    }

}
