<?php
//驗證類
class ActionsCheck extends Check
{
    // 新增時要做檢查(範例)
    public function addCheck(Model &$_model)
    {
        if (self::isNullString($_POST['title'])) {
            $this->_message[] = '活動名稱不得為空！';
            return false;
        }
        return true;
    }

}
