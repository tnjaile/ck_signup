<?php
class ActionsModel extends Model
{
    public function __construct()
    {
        parent::__construct();

        // 要顯示的欄位及欄位類型
        $this->_fields = array('action_id' => 'int', 'title' => 'string', 'content' => 'ckeditor', 'action_date' => 'date', 'end_date' => 'date', 'uid' => 'int', 'enable' => 'int');
        // 要查詢的表
        $this->_tables = array(DB_PREFIX . "ck_actions");
        // 欄位檢查
        // $this->_check = new ActionsCheck();
        // 過濾參數
        list($this->_R['action_id']) = $this->getRequest()->getParam(array(
            isset($_REQUEST['action_id']) ? Tool::setFormString($_REQUEST['action_id'], "int") : null));
    }

    public function actions_delete($_whereData = array())
    {
        $_where = (empty($_whereData)) ? array("action_id='{$this->_R['action_id']}'") : $_whereData;

        return parent::delete($_where);
    }
    public function actions_add()
    {
        global $xoopsUser;
        if (!$this->_check->addCheck($this)) {
            return;
        }

        // 過濾POST
        $_addData = $this->getRequest()->filter($this->_fields);
        // 去除自動遞增
        unset($_addData['action_id']);
        $_addData['uid'] = $xoopsUser->uid();
        return parent::add($_addData);
    }

    public function actions_update($_selectData = array())
    {
        $_where = array("action_id='{$this->_R['action_id']}'");

        if (!$this->_check->oneCheck($this, $_where)) {
            return;
        }

        if (!$this->_check->addCheck($this)) {
            return;
        }

        $_selectData = empty($_selectData) ? $this->_fields : $_selectData;
        $_updateData = $this->getRequest()->filter($_selectData);

        parent::update($_where, $_updateData);
        return $this->_R['action_id'];
    }

    public function findOne($_whereData = array(), $_selectData = array())
    {
        $_where = (empty($_whereData)) ? array("action_id='{$this->_R['action_id']}'") : $_whereData;
        // 先驗證是否有此編號的資料
        if (!$this->_check->oneCheck($this, $_where)) {
            return;
        }

        $_selectData = empty($_selectData) ? $this->_fields : $_selectData;

        // 秀出此編號的詳細資訊
        $_One = parent::select($_selectData, array('where' => $_where, 'limit' => '1'));
        if (!empty($_One)) {
            return $_One[0];
        }
    }

    public function findAll($_whereData = array(), $_selectData = array())
    {
        $_where = (empty($_whereData)) ? array() : $_whereData;

        $_selectData = empty($_selectData) ? $this->_fields : $_selectData;
        $_All        = parent::select($_selectData, array('where' => $_where, 'limit' => $this->_limit, 'order' => 'action_id'));
        if (!empty($_All)) {
            return $_All;
        }
    }

    // 分頁用
    public function allNum()
    {
        return parent::total();
    }

}
