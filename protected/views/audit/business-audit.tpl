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
                <li class="active">平台审计</li>
            </ol>
        </div>
    </div>

    <div class="row" style="margin-bottom:30px;">
        <div class="col-md-12">
            <ul class="nav nav-pills">
                <li role="presentation" ><a href="/audit/BusinessDetail">平台审计</a></li>
                <li role="presentation" class="active"><a href="#">审计详情</a></li>
                <li role="presentation"><a href="/audit/UvOfBusiness">用户审计</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <div class="form-inline">
                    <div class="form-group">
                        <label for=""></label>
                        <select id='business' name="businesses" class="js-example-basic-single form-control" >
                            {/foreach from=$project item=item key=key/}
                                <option {/if $business == $key /} selected {//if/} value="{/$key/}">{/$key/}--{/$project[$key].cname/}</option>
                            {//foreach/}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">用户名</label>
                        <input id="user" type="text" value="{/$user/}" class="form-control" placeholder="邮箱前缀(选填)">
                    </div>
                    <div class="form-group">
                        <label for="">起止时间</label>
                        <input id="start_time" type="text" class="picker form-control" value="{/$start_time/}" />
                    </div>
                     <div class="form-group">
                        <label for="">结束时间</label>
                        <input id="end_time" type="text" class="picker form-control" value="{/$end_time/}"/>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success submitBtn">查看</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- show data -->
    {/if $data /}
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr class="active">
                        <th>
                            <i class="glyphicon glyphicon-th-list"></i> 平台 <i class="glyphicon glyphicon-arrow-up sortBtn" style="float:right;"></i>
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-globe"></i> 访问的url
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-user"></i> 用户 <i {/if $condition=='audit_user-desc'/} data-condition="audit_user-asc" class="glyphicon glyphicon-arrow-down sortBtn"{/else/}data-condition="audit_user-desc" class="glyphicon glyphicon-arrow-up sortBtn"{//if/}  style="float:right;"></i>
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-modal-window"></i> 用户的ip
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-star"></i> 状态 <i {/if $condition=='audit_flag-desc'/} data-condition="audit_flag-asc" class="glyphicon glyphicon-arrow-down sortBtn" {/else/} data-condition="audit_flag-desc" class="glyphicon glyphicon-arrow-up sortBtn"{//if/} style="float:right;"></i>
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-time"></i> 访问的时间 <i {/if $condition=='unix-desc'/} data-condition="unix-asc" class="glyphicon glyphicon-arrow-down sortBtn"{/else/} data-condition="unix-desc" class="glyphicon glyphicon-arrow-up sortBtn" {//if/} style="float:right;"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {/foreach from=$data item=item key=key/}
                        <tr>
                            <td>{/$item.business/}</td><td><a href="{/$item.audit_url/}" target= "_blank">{/$item.audit_url/}</a></td><td>{/$item.audit_user/}</td><td>{/$item.audit_ip/}</td><td>{/$item.audit_flag/}</td><td>{/$item.unix/}</td>
                        </tr>
                    {//foreach/}
                </tbody>
            </table>
        </div>
    </div>
    {/else/}
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                该平台暂时没有相关的数据，切换别的平台,选择别的时间试试？
            </div>
        </div>
    </div>
    {//if/}
    {/if $totalNum > 10/}
        <div class="row">
            <div class="panel-body">
                <div style="text-align:center">
                    <ul class="pagination">
                        <li><a>共{/$totalNum/}条记录</a></li>
                        <li><a>总共{/$pageInfo.totalPage/}页</a></li>
                        <li><a href="{/$pageInfo.firstLink/}">首页</a></li>
                        {/if $pageInfo.prevPage >= 1/}
                        <li><a href="{/$pageInfo.prevLink/}">上一页</a></li>
                        {//if/}
                        {/foreach from=$pageInfo.pageNumList item=item key=key/}
                            {/if $pageInfo.nowPage && $pageInfo.nowPage==$key/}
                                <li class="active"><a href="javascript:return false;" class="cur">{/$key/}</a></li>
                            {/else/}
                                <li><a href="{/$item/}">{/$key/}</a></li>
                            {//if/}
                        {//foreach/}
                        {/if $pageInfo.nextPage <= $pageInfo.totalPage/}
                        <li><a href="{/$pageInfo.nextLink/}">下一页</a></li>
                        {//if/}
                        <li><a href="{/$pageInfo.lastLink/}">最后一页</a></li>
                    </ul>
                </div>
            </div>
        </div>
    {//if/}
</div>
<script>
$(function(){
    $('.submitBtn').click(function(){
        var business = $("#business").val();
        var user = $("#user").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        window.location = "/audit/businessaudit?business="+business+"&user="+user+"&start_time="+start_time+"&end_time="+end_time;
    })
    document.onkeydown = function(e){ 
        var ev = document.all ? window.event : e;
        var business = $("#business").val();
        var user = $("#user").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        if(ev.keyCode==13) {
            window.location = "/audit/businessaudit?business="+business+"&user="+user+"&start_time="+start_time+"&end_time="+end_time
        }
    }
    $('.sortBtn').click(function(){
        var business = $("#business").val();
        var user = $("#user").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var condition = $(this).attr('data-condition')
        window.location = "/audit/businessaudit?business="+business+"&user="+user+"&start_time="+start_time+"&end_time="+end_time+"&condition="+condition;
    })
    //日期插件
    $('.picker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
})
</script>