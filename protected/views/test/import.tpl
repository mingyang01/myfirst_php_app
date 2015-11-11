{/include file="layouts/header.tpl"/}
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<div class="container">
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/publish/index">内部功能</a></li>
            <li class="active">批量导入</li>
        </ol>
        <div id="well" class="well">
            <div class="form-inline" role="form" id="form">
                <div class="form-group">
                    <label for="exampleInputName2">功能</label>
                    <input type="text" class="form-control" id="exampleInputName2" placeholder="262" value="{/$id/}">
                </div>
                <div class="form-group">
                    <label for="exampleInputName2">角色</label>
                    <input type="text" class="form-control" id="exampleInputName2" placeholder="608" value="{/$role/}">
                </div>
                
                <button data-action="create" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">查找</button>
                <button data-action="update" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">导入</button>
            </div>
        </div>
    </div>
    <div class="col-md-12">
<table class="table table-bordered">
    <thead>
        <tr>
            <td>username</td>
            <td>realname</td>
            <td>speed</td>
            <td>depart</td>
            <td>status</td>
        </tr>
    </thead>
    <tboby>
        {/foreach from=$users key=i item=u/}
            <tr>
                <td>{/$u['realname']/}</td>
                <td>{/$u['username']/}</td>
                <td>{/$u['id']/}</td>
                <td>{/$u['depart']/}</td>
                <td>{/$u['status']/}</td>
            </tr>
        {//foreach/}
    </tbody>
</table>
    </div>
</div>
</div>

<script>

$('button').eq(0).click(function(e){
    if (!$('input').eq(0).val() || !$('input').eq(1).val()) {
        alert('请输入功能id 与 角色 id');
        return false
    };

    window.location = '/test/import/id/' + $('input').eq(0).val() + '/role/' + $('input').eq(1).val()
})

$('button').eq(1).click(function(e){
    if (!$('input').eq(0).val() || !$('input').eq(1).val()) {
        alert('请输入功能id 与 角色 id');
        return false
    };

    window.location = '/test/import/id/' + $('input').eq(0).val() + '/role/' + $('input').eq(1).val() + "/import/1"
})


if ('{/$count/}') {
    alert("为{/$count/}个用户赋予了权限!");
    console.log("为{/$count/}个用户赋予了权限!")
};

</script>