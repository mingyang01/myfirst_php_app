{/include file="layouts/header.tpl"/}
<script src="/assets/js/jquery.nestable.js"></script>
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<style>
/**
 * Nestable
 */

.dd { position: relative; display: block; margin: 0; padding: 0; max-width: 800px; list-style: none; font-size: 13px; line-height: 20px; }

.dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
.dd-list .dd-list { padding-left: 30px; }
.dd-collapsed .dd-list { display: none; }

.dd-item,
.dd-empty,
.dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }

.dd-handle { display: block; height: 30px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd-handle:hover { color: #2ea8e5; background: #fff; }

.dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
.dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
.dd-item > button[data-action="collapse"]:before { content: '-'; }

.dd-placeholder,
.dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
.dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
    background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                      -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                         -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                              linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-size: 60px 60px;
    background-position: 0 0, 30px 30px;
}

.dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
.dd-dragel > .dd-item .dd-handle { margin-top: 0; }
.dd-dragel .dd-handle {
    -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
            box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
}

/**
 * Nestable Extras
 */

.nestable-lists { display: block; clear: both; padding: 30px 0; width: 100%; border: 0; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd; }


#nestable-output,
#nestable2-output { width: 100%; height: 7em; font-size: 0.75em; line-height: 1.333333em; font-family: Consolas, monospace; padding: 5px; box-sizing: border-box; -moz-box-sizing: border-box; }

#nestable2 .dd-handle {
    color: #fff;
    border: 1px solid #999;
    background: #bbb;
    background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
    background:    -moz-linear-gradient(top, #bbb 0%, #999 100%);
    background:         linear-gradient(top, #bbb 0%, #999 100%);
}
#nestable2 .dd-handle:hover { background: #bbb; }
#nestable2 .dd-item > button:before { color: #fff; }

@media only screen and (min-width: 700px) {

    .dd { float: left; width: 48%; }
    .dd + .dd { margin-left: 2%; }

}

.dd-hover > .dd-handle { background: #2ea8e5 !important; }

/**
 * Nestable Draggable Handles
 */

.dd3-content { display: block; height: 30px; margin: 5px 0; padding: 5px 10px 5px 40px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd3-content:hover { color: #2ea8e5; background: #fff; }

.dd-dragel > .dd3-item > .dd3-content { margin: 0; }

.dd3-item > button { margin-left: 30px; }

.dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; text-indent: 100%; white-space: nowrap; overflow: hidden;
    border: 1px solid #aaa;
    background: #ddd;
    background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:         linear-gradient(top, #ddd 0%, #bbb 100%);
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.dd3-handle:before { content: '≡'; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
.dd3-handle:hover { background: #ddd; }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">菜单的排序</li>
            </ol>
        </div>
        <div class="col-md-12">
            <div class="alert alert-danger">
                该功能目前只支持菜单的排序，暂不支持菜单的结构更改！
            </div>
        </div>
        <div class="col-md-12" style="margin-bottom:20px;">
            <div class="col-md-3">
                <select id='business' name="businesses" class="js-example-basic-single form-control" >
                    {/foreach from=$project item=item key=key/}
                        <option {/if $business == $key /} selected {//if/} value="{/$key/}">{/$key/}--{/$project[$key].cname/}</option>
                    {//foreach/}
                </select>
            </div>
            <div class="col-md-9" id="nestable-menu">
                <button class="btn btn-default" type="button" data-action="expand-all">展开全部</button>
                <button class="btn btn-default" type="button" data-action="collapse-all">折叠全部</button>
            </div>
        </div>
        <div class="col-md-12">
            
            <div class="cf nestable-lists clearfix">
                <div class="dd" id="nestable3">
                    <ol class="dd-list">
                        {/foreach from=$menu item=item key=key /}
                            <li class="dd-item dd3-item" data-id="{/$item.id/}">
                                <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">{/$item.name/}</div>
                                {/if $item.child/}
                                    <ol class="dd-list">
                                        {/foreach from=$item.child item=secondItem key=secondKey/}
                                            <li class="dd-item dd3-item" data-id="{/$secondItem.id/}">
                                                <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">{/$secondItem.name/}</div>
                                                {/if $secondItem.child/}
                                                <ol class="dd-list">
                                                {/foreach from=$secondItem.child item=thirdItem key=thirdKey/}
                                                    <li class="dd-item dd3-item" data-id="{/$thirdItem.id/}">
                                                        <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">{/$thirdItem.name/}</div>
                                                    </li>
                                                {//foreach/}
                                                </ol>
                                                {//if/}
                                            </li>
                                        {//foreach/}
                                    </ol>
                                {//if/}
                            </li>
                        {//foreach/}
                    </ol>
                </div>
                <div class="col-md-12" style="padding:0px;margin-top:50px;">
                    <button id="save-btn" class="btn btn-success btn-block"> 保存</button>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script>
$(document).ready(function()
{
    var jsonList = '';
    var list = $('#nestable3').data('output', null)
    jsonList = window.JSON.stringify(list.nestable('serialize'))
    $('#nestable3').nestable().on('change',function(){
        var list = $('#nestable3').data('output', null)
        jsonList = window.JSON.stringify(list.nestable('serialize'));
    });

    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    $('#save-btn').click(function(){
        $.post('/DragMenu/saveRank', {'list':jsonList} , function(data) {
            window.location.reload();
        },'json');
    })
    $('#business').change(function(){
        var business = $(this).val()
        window.location="/DragMenu/index?business="+business;
    })

});
</script>