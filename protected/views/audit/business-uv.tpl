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
                            {/foreach from=$project item=item key=key/}
                                <option {/if $business == $key /} selected {//if/} value="{/$key/}">{/$key/}--{/$project[$key].cname/}</option>
                            {//foreach/}
                        </select>
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

    {/if !$data/}
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                该平台暂时没有相关的数据，切换别的平台,选择别的时间试试？
            </div>
        </div>
    </div>
    {//if/}

    <div class="row">
        <div class="col-md-12" style="margin-bottom:50px; ">
            <div id="bar-chart" class="col-md-6" style="width:1100px;height:400px;">   
            </div>
        </div>
    </div>
</div>
<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<script>
    var data={/json_encode($data)/};
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
</script>