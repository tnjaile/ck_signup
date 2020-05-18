<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<!--套用formValidator驗證機制-->
<form action="<{$smarty.server.PHP_SELF}>" method="post" id="myForm" enctype="multipart/form-data">


    <!--活動名稱-->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-md-right">
            <{$smarty.const._MD_CKSIGNUP_TITLE}>
        </label>
        <div class="col-sm-6">
            <input type="text" name="title" id="title" class="form-control validate[required]" value="<{$OneAction.title}>" placeholder="<{$smarty.const._MD_CKSIGNUP_TITLE}>">
        </div>
    </div>

    <!--活動說明-->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-md-right">
            <{$smarty.const._MD_CKSIGNUP_CONTENT}>
        </label>
        <div class="col-sm-6">
            <{$content_editor}>
        </div>
    </div>

    <!--活動日期 date-->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-md-right">
            <{$smarty.const._MD_CKSIGNUP_ACTION_DATE}>
        </label>
        <div class="col-sm-6">
            <input type="text" name="action_date" id="action_date" class="form-control validate[required]" value="<{$OneAction.action_date}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd', startDate:'%y-%M-%d'})" placeholder="<{$smarty.const._MD_CKSIGNUP_ACTION_DATE}>">
        </div>
    </div>

    <!--報名截止日 datetime-->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-md-right">
            <{$smarty.const._MD_CKSIGNUP_END_DATE}>
        </label>
        <div class="col-sm-6">
            <input type="text" name="end_date" id="end_date" class="form-control validate[required]" value="<{$OneAction.end_date}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm', startDate:'%y-%M-%d %H:%m'})" placeholder="<{$smarty.const._MD_CKSIGNUP_END_DATE}>">
        </div>
    </div>

    <!--是否啟用-->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label text-md-right">
            <{$smarty.const._MD_CKSIGNUP_ENABLE}>
        </label>
        <div class="col-sm-10">

            <div class="form-check form-check-inline">
                <input type="radio" name="enable" id="enable_1" class="form-check-input" value="1" <{if $OneAction.enable == "1"}>checked="checked"<{/if}>>
                <label class="form-check-label" for="enable_1">是</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="enable" id="enable_0" class="form-check-input" value="0" <{if $OneAction.enable == "0"}>checked="checked"<{/if}>>
                <label class="form-check-label" for="enable_0">否</label>
            </div>
        </div>
    </div>

    <div class="text-center">
        <{$token_form}>
        <input type="hidden" name="next_op" value="<{$next_op}>">
        <input type="hidden" name="action_id" value="<{$OneAction.action_id}>">
        <input type="hidden" name="op" value="<{$now_op}>">
        <input type="submit" name="send" value="<{$smarty.const._TAD_SAVE}>" class="btn btn-primary" />
    </div>
</form>