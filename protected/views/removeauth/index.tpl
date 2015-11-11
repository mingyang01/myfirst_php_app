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
                <li role="presentation" class="active"><a href="#">解除权限</a></li>
                <li role="presentation"><a href="/removeAuth/removeAll">解除所有权限</a></li>
                <li role="presentation"><a href="/removeAuth/recover">恢复权限</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <form class="form-inline">
                    <div class="form-group">
                        <label for="">平台：</label>
                        <select name="business" class="form-control">
                            {/foreach from = $project item = item key = key/}
                                <option {/if $business==$item.business/} selected {//if/}value="{/$item.business/}">{/$item.business/}--{/$item.cname/}</option>
                            {//foreach/}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">功能名称</label>
                        <input name="functioname" type="text" class="picker form-control" value="{/$functioname/}" />
                    </div>
                     <div class="form-group">
                        <label for="">解除人</label>
                        <input name="user" type="text" class="picker form-control" value="{/$user/}"/>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success submitBtn">提交</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col-md-12">
                {/if $msg/}
                <div class="alert alert-danger">
                    {/$msg/}
                </div>
                {//if/}
            </div>
        </div>
</div>
