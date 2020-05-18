<?php
/**
 * 前台首頁
 **/
class SignUpIndexAction extends Action
{
    private $_物件 = null;
    public function __construct()
    {
        parent::__construct();
        $this->_物件 = new 物件();
    }

    //
    public function main()
    {
        $this->_tpl->assign('main', "前台");
    }

}
