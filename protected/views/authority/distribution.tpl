{/include file="layouts/header.tpl"/}

<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2({ placeholder:"请选择部门"});
    });
</script>
<div class="container">
  <div class="row">
    <div class="col-md-12">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#user" role="tab" data-toggle="tab">用户分配</a></li>
        <li role="presentation"><a href="#role" role="tab" data-toggle="tab">角色分配</a></li>
    </ul>

      <!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane  active well clearfix" style="margin:10px 0 10px 0;" id="user">
        <div class="col-md-12">
            <h4>给用户分配角色</h4>
            </div>
            <div class="col-md-6">
                <div class="col-sm-10" style="padding:0px;">
                    <div class="col-sm-6" style="padding:0px;">
                        <input class="form-control" id="username" name="username" placehodder="用户名"/>
                    </div>
                    <div class="col-sm-4">
                        <button id="btnsub" class="btn btn-success">查询</button>
                    </div>
                </div>
            <div class="col-sm-10" style="padding:0px;margin:10px 0 0 0;">
                <div id="usermsg" class="list-group">
                    <a href="#" class="list-group-item disabled">
                      用户信息
                    </a>
                    <a href="#" class="list-group-item">用&nbsp;&nbsp;户：</a>
                    <a href="#" class="list-group-item">邮&nbsp;&nbsp;箱：</a>
                    <a href="#" class="list-group-item ">
                    <h4 class="list-group-item-heading">头像</h4>
                    <p style="text-align:center" class="list-group-item-text">
                        <img height=200 width=200/>
                    </p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="userform">
                <input id="meusername" style="display:none;" name="meusername"/>
                <select id="userselect" multiple="multiple" size="17" name="userrole[]">
                </select>
                <br>
                <button id="usersubmit" class="btn btn-success">提交</button>
            </div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane well  clearfix" style="margin:10px 0 10px 0;" id="role">
      <h4>给角色分配任务</h4>
        <div class="col-md-6" style="padding-left:0px;" >
            <select name="" id="depart" class="js-example-basic-single" style="width:100%;">
                {/foreach from=$depart item=item key=key/}
                    <optgroup label="{/$key/}">
                        {/foreach from=$depart.$key item=it key=val/}
                            <option title="{/$depart.$key.$val.id/}" value="{/$depart.$key.$val.id/}">{/$depart.$key.$val.name/}</option>
                        {//foreach/}
                    </optgroup>
                {//foreach/}
            </select>
            <select name="rolelist" size="20"  class="form-control" id="rolelist" style="margin-top:10px;">
                {/if $data neq ""/}
                    {/foreach from=$data item=item key=key/}
                        <option title="{/$item/}" value="{/$item/}">{/$item/}</option>
                    {//foreach/}
                {//if/}
            </select>
        </div>
        <div class="col-md-6">
            <select id="tasklist"  multiple="multiple" style = "height:234px;" size="20" name="duallistbox_demo1[]">
            </select>
            <br>
            <button id="tasksubmit" class="btn btn-success">提交</button>
        </div>
    </div>
</div>
</div>
</div>
</div>
<script src="/assets/js/distribution.js"></script>
<style>
    #bootstrap-duallistbox-selected-list_userrole[]{
        margin-left: -1px;
    }
</style>