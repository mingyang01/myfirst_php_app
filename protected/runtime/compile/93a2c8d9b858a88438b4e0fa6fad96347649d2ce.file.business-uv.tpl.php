<?php /* Smarty version Smarty-3.1.18, created on 2015-09-16 17:57:30
         compiled from "/home/work/websites/auth/protected/views/audit/business-uv.tpl" */ ?>
<?php /*%%SmartyHeaderCode:50467824955f92357386f98-29411940%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '93a2c8d9b858a88438b4e0fa6fad96347649d2ce' => 
    array (
      0 => '/home/work/websites/auth/protected/views/audit/business-uv.tpl',
      1 => 1442397441,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '50467824955f92357386f98-29411940',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55f923573d2863_27553488',
  'variables' => 
  array (
    'project' => 0,
    'business' => 0,
    'key' => 0,
    'start_time' => 0,
    'end_time' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f923573d2863_27553488')) {function content_55f923573d2863_27553488($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
                <li role="presentation"><a href="/audit/BusinessDetail">平台审计</a></li>
                <li role="presentation"><a href="/audit/BusinessAudit">审计详情</a></li>
                <li role="presentation" class="active"><a href="#">用户审计</a></li>
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
                            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['project']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                                <option <?php if ($_smarty_tpl->tpl_vars['business']->value==$_smarty_tpl->tpl_vars['key']->value) {?> selected <?php }?> value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
--<?php echo $_smarty_tpl->tpl_vars['project']->value[$_smarty_tpl->tpl_vars['key']->value]['cname'];?>
</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">起止时间</label>
                        <input id="start_time" type="text" class="picker form-control" value="<?php echo $_smarty_tpl->tpl_vars['start_time']->value;?>
" />
                    </div>
                     <div class="form-group">
                        <label for="">结束时间</label>
                        <input id="end_time" type="text" class="picker form-control" value="<?php echo $_smarty_tpl->tpl_vars['end_time']->value;?>
"/>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success submitBtn">查看</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$_smarty_tpl->tpl_vars['data']->value) {?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                该平台暂时没有相关的数据，切换别的平台,选择别的时间试试？
            </div>
        </div>
    </div>
    <?php }?>

    <div class="row">
        <div class="col-md-12" style="margin-bottom:50px; ">
            <div id="bar-chart" class="col-md-6" style="width:1100px;height:400px;">   
            </div>
        </div>
    </div>
</div>
<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<script>
    var data=<?php echo json_encode($_smarty_tpl->tpl_vars['data']->value);?>
;
    var business = []
    var uv = []
    for (item in data) {
        business.push(data[item]["audit_user"])
        var uvVal = parseInt(data[item]["uv"]);
        uv.push({value:uvVal,name:data[item]["audit_user"]})
    };
</script>
<script type="text/javascript">
    // 路径配置
    require.config({
        paths: {
            echarts: 'http://echarts.baidu.com/build/dist'
        }
    });
    
    // 使用
    require(
        [
            'echarts',
            'echarts/chart/pie',
            'echarts/chart/funnel',
            'echarts/chart/bar',
            'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var barChart = ec.init(document.getElementById('bar-chart'));
            var barOption = {
                title : {
                    text: '平台的uv'
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:['uv']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {show: true, type: ['line', 'bar']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                dataZoom : {
                    show : true,
                    realtime: true,
                    start : 0,
                    end : 100
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : business
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'uv',
                        type:'bar',
                        data:uv,
                        markPoint : {
                            data : [
                                {type : 'max', name: '最大值'},
                                {type : 'min', name: '最小值'}
                            ]
                        }
                    }
                ]
            };
    
            // 为echarts对象加载数据 barOption
            if(uv.length>0){
                barChart.setOption(barOption);
            }
        }
    );
</script>
<script>
$(function(){
    $('.submitBtn').click(function(){
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var business = $("#business").val();
        window.location = "/audit/UvOfBusiness?business="+business+"&start_time="+start_time+"&end_time="+end_time;
    })
    document.onkeydown = function(e){ 
        var ev = document.all ? window.event : e;
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var business = $("#business").val();
        if(ev.keyCode==13) {
            window.location = "/audit/UvOfBusiness?business="+business+"&start_time="+start_time+"&end_time="+end_time;
        }
    }
    //日期插件
    $('.picker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
})
</script><?php }} ?>
