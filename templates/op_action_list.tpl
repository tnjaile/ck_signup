<{if $AllAction}>
  <h2>活動列表<small>（共 <{$AllAction|@count}> 個活動）</small></h2>
  <table class="table table-bordered table-hover table-striped">
    <tr class="info">
      <th>活動日期</th>
      <th>活動名稱</th>
      <th>截止日期</th>
      <th>功能</th>
    </tr>
    <{foreach from=$AllAction item=data}>
      <tr>
        <td><{$data.action_date}></td>
        <td>
          <{if $data.enable!=1}>
            <span class="label label-danger">已關閉</span>
          <{/if}>
          <a href="<{$xoops_url}>/modules/ck_signup/index.php?action_id=<{$data.action_id}>"><{$data.title}></a>
        </td>
        <td><{$data.end_date}></td>
        <td>
          <{if $xoops_isadmin}>
            <a href="javascript:delete_action(<{$data.action_id}>);" class="btn btn-danger btn-xs">刪除</a>
            <a href="<{$action}>?op=actions_form&action_id=<{$data.action_id}>" class="btn btn-warning btn-xs">編輯</a>
          <{/if}>
        </td>
      </tr>
    <{/foreach}>
  </table>

  <div class="text-right">
    <a href="<{$action}>?op=actions_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._TAD_ADD}>
    </a>
  </div>
<{else}>
    <div class="jumbotron text-center">
        <{if $smarty.session.ck_signup_adm}>
            <a href="<{$action}>?op=actions_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._TAD_ADD}>
            </a>
        <{else}>
            <h3><{$smarty.const._TAD_EMPTY}></h3>
        <{/if}>
    </div>
<{/if}>