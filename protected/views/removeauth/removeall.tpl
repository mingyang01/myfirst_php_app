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
                <li role="presentation"><a href="#">解除权限</a></li>
                <li role="presentation" class="active"><a href="/removeAuth/removeAll">解除所有权限</a></li>
                <li role="presentation"><a href="/removeAuth/recover">恢复权限</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
        	<div class="alert alert-danger">
        		一旦解除某人的所有权限，将无法恢复，请慎重操作！
        	</div>
            <div class="well clearfix">
                <div class="col-md-4">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="">被解除人</label>
                            <input name="user" type="text" placeholder="填写正确的邮箱前缀" class="picker form-control" value="{/$user/}"/>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success submitBtn">提交</button>
                        </div>
                    </form>
                </div>
                {/if $userAuth/}
                <div class="col-md-8">
                    <button class="btn btn-info sure-remove-btn">一键解除该人所有权限</button>
                </div>
                {//if/}
            </div>
        </div>
        {/if $userAuth/}
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th>被解除人</th><th>被解除项</th><th>类型</th>
                </tr>
                {/foreach from = $userAuth item =item key = key/}
                 <tr>
                    <td>{/$user/}</td><td>{/$item.name/}</td>
                    {/if $item.type==1/} <td>功能</td>{/else/} <td>角色</td>{//if/}
                 </tr>
                {//foreach/}
            </table>
        </div>
        {//if/}
    </div>
</div>
<script>
    var user = '{/$user/}';
    $('.sure-remove-btn').click(function(){
        window.location = "/removeAuth/removeAll?user="+user+"&remove=true"
    })
</script>