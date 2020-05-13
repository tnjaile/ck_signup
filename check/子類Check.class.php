<?php
//分類驗證類
class 資料表ClassCheck extends Check
{
    // 新增時要做檢查(範例)
    public function addCheck(Model &$_model)
    {
        if (self::isNullString($_POST['欄位1'])) {
            $this->_message[] = 'XX上限不得為空！';
            return false;
        }
        if (self::checkStrLength($_POST['欄位1'], 1, 'min')) {
            $this->_message[] = 'XX上限不得小於1！';
            return false;
        }
        if (self::checkStrLength($_POST['欄位1'], 3, 'max')) {
            $this->_message[] = 'XX上限不得大於3位！';
            return false;
        }
        if (self::isNullString($_POST['欄位2'])) {
            $this->_message[] = 'XX下限不得為空！';
            return false;
        }
        if (self::checkStrLength($_POST['欄位2'], 1, 'min')) {
            $this->_message[] = 'XX下限不得小於1！';
            return false;
        }
        if (self::checkStrLength($_POST['欄位2'], 3, 'max')) {
            $this->_message[] = 'XX下限不得大於3位！';
            return false;
        }
        return true;
    }
    // 更新檢查(視需要)
    // public function updateCheck(Model &$_model) {
    //     return $this->_flag;
    // }
    // Ajax檢查(視需要)
    // public function ajax(Model &$_model, Array $_param) {
    //     echo $_model->isOne($_param) ? 1 : 2;
    // }

}
