{/include file="layouts/header.tpl"/}
<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
<script>
    console.log({/json_encode($selected)/});
</script>
<div class="container">
    <div class="row" style="padding-top:20px;">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/auth/role">角色管理</a></li>
                <li class="active">角色成员</li>
            </ol>
        </div>
    </div>

    <div>
        <select multiple="multiple" size="10" name="duallistbox" class="listBox" style="display: none;">
            {/foreach from=$whole key=i item=u/}
                <option {/if in_array($u['id'], $selected)/}selected="selected"{//if/} value="{/$u['id']/}">{/$u['name']/}</option>
            {//foreach/}
        </select>
    </div>

    <div class="row" style="margin-top:20px">
        <div class="col-md-12">
            <button id="submit" class="col-md-12 btn btn-default">确定</button>
        </div>
    </div>

<script>
    var depart = "{/$depart/}";
    var role = "{/$role/}";

    var listBox = $('.listBox').bootstrapDualListbox({
      nonSelectedListLabel: '未分配的人员',
      selectedListLabel: '已分配的人员',
      preserveSelectionOnMove: 'moved',
      moveOnSelect: true,
      nonSelectedFilter: ''
    });

    $('#submit').click(function(e){
        var selected = $('.listBox').val();
        var url = '/auth/roleUserAdd';
        $.post(url, {"selected": selected, "role": role,"departid":depart}, function(data){
        	alert(data.msg);
            window.location.reload();
        }, 'json')
    });
</script>
<style>
    #bootstrap-duallistbox-selected-list_duallistbox, #bootstrap-duallistbox-nonselected-list_duallistbox {
        height: 300px !important;
    }
</style>