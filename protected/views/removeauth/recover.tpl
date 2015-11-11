{/include file="layouts/header.tpl"/}
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<style>
    .sortBtn{
        display:inline-block;
        width: 20px;
        padding-left: 5px;
        cursor:pointer;
    }
</style>
<div class="container">
    
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">解除权限</li>
            </ol>
        </div>
    </div>

    <div class="row" style="margin-bottom:30px;">
        <div class="col-md-12">
            <ul class="nav nav-pills">
                <li role="presentation" ><a href="/removeAuth/index">解除权限</a></li>
                <li role="presentation"><a href="/removeAuth/removeAll">解除所有权限</a></li>
                <li role="presentation" class="active"><a href="#">恢复权限</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <div class="form-group">
                    <button class="btn btn-success submitBtn">恢复权限</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom:50px;">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" ></th><th>用户</th><th>功能</th><th>操作人</th><th>操作时间</th>
                    </tr>
                </thead>
                <tbody>
                    {/foreach from=$data item=item key=key/}
                    <tr>
                        <th data-id="{/$item.id/}"><input type="checkbox" ></th><th>{/$item.user/}</th><th>{/$item.op_item/}</th><th>{/$item.op_user/}</th><th>{/$item.op_time/}</th>
                    </tr>
                    {//foreach/}
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$('.submitBtn').click(function(){
    var ids = '';
    $('input').each(function(){
        var flag = $(this).prop("checked");
        if(flag){
            ids += $(this).parent().attr('data-id')+','
        }
    })
    ids=ids.substring(0,ids.length-1);
    if(!ids){
        alert("请勾选");
    }
    $.post('/removeAuth/recoveRAuth', {ids:ids}, function(data) {
        if(data){
            alert(data)
        }
        window.location.reload();
    },'json');
})
</script>
