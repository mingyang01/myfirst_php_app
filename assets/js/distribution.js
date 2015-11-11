$(function(){
$('#myTab a:first').tab('show');
});
$(function(){
//innit select 
$("#userform").bootstrapDualListbox({
    nonSelectedListLabel: '未分配的角色',
    selectedListLabel: '已分配的角色',
    preserveSelectionOnMove: 'moved',
    moveOnSelect: true,
    nonSelectedFilter: ''
  });
$("#tasklist").bootstrapDualListbox({
    nonSelectedListLabel: '未分配的任务',
    selectedListLabel: '已分配的任务',
    preserveSelectionOnMove: 'moved',
    moveOnSelect: true,
    nonSelectedFilter: ''
    
  });
//获取用户是否在职
function getUserStatus(status){
    if(status==1){
        return "在职";
    }else if(status==2){
        return "离职";
    }else{
        return "其他";
    }
}

$('#btnsub').click(function() {
    $("#namesupply").hide();
    $.post('searchuser', {username:$('#username').val()}, function(data) {
        if(data) {
            var result=eval('(' + data + ')');
            console.log(result);
            if(result.code == 1) {
                var results = result.data;
                var img = '';
                if (results.avatar_origin) {
                	img = '<p style="text-align:center"><img src="'+results.avatar_origin+'" height=200 width=150/></p>';
                } else {
                	img = '<p>用户还没有上传头像</p>';
                }
                var rehtml =[
                    '<a href="javascript:;" class="list-group-item disabled">用户信息</a>',
                    '<a  href="javascript:;" id="roleview" class="list-group-item textright">用&nbsp;&nbsp;户：<span>'+results.depart+'-'+results.name+'</span></a>',
                    '<a href="javascript:;" id="user_mail" class="list-group-item textright">邮&nbsp;&nbsp;箱：<span>'+results.mail+'</span></a>',
                    '<a href="javascript:;" id="user_mail" class="list-group-item textright">状&nbsp;&nbsp;态：<span>'+getUserStatus(results.status)+'</span></a>',
                    '<a href="javascript:;" class="list-group-item ">',
                    '<h4>头像</h4>',
                    img,
                    '</a>'
                ].join(" ");
            $("#usermsg").html(rehtml);
                (function(){
                    $("#meusername").val($('#roleview span').html());
                    
                    $.get('roleview', {umail:$('#user_mail span').html()}, function(data) {
                        if(data) {
                            //console.log(data)
                            var selected = data['selected'];
                            var select = data['select'];
                            var flag = data['superflag'];
                            var rehtml = [];
                            if(selected) {
                              for (itemname in selected) {
                                if(flag) {
                                    rehtml.push('<option selected="selected"  title="'+selected[itemname]+'" value="'+selected[itemname]+'">'+selected[itemname]+'</option>');
                                } else {
                                    rehtml.push('<option selected="selected"  title="'+selected[itemname].substr(selected[itemname].indexOf('/')+1)+'" value="'+selected[itemname]+'">'+selected[itemname].substr(selected[itemname].indexOf('/')+1)+'</option>');
                                }
                            };
                            }
                            if(select) {
                              for (itemname in select) {
                              if(flag) {
                                rehtml.push('<option title="'+select[itemname]+'" value="'+select[itemname]+'">'+select[itemname]+'</option>');
                              } else {
                                rehtml.push('<option  title="'+select[itemname].substr(select[itemname].indexOf('/')+1)+' "value="'+select[itemname]+'">'+select[itemname].substr(select[itemname].indexOf('/')+1)+'</option>');
                              }
                            };
                            }
                            rehtml = rehtml.join(" ");
                            $("#userselect").html(rehtml);
                            $("#userselect").bootstrapDualListbox('refresh',true);
                        }

                    },'json');
            })();
        } else {
        	alert(result.msg);
        }
        }
    });
});

$("#usersubmit").click(function(){
    $.post('userform', {userrole:$('#userselect').val(),umail:$('#user_mail span').html()}, function(data) {
        if(data)
        {
            var selected = data['selected'];
            var select = data['select'];
            var flag = data['superflag'];
            var rehtml = [];
            if(selected)
            {
              for (itemname in selected) {
                if(flag)
                {
                    rehtml.push('<option selected="selected"  value="'+selected[itemname]+'">'+selected[itemname]+'</option>');
                }
                else
                {
                    rehtml.push('<option selected="selected"  value="'+selected[itemname]+'">'+selected[itemname].substr(selected[itemname].indexOf('/')+1)+'</option>');
                }
            };
            }
            if(select)
            {
              for (itemname in select) {
                if(flag)
                {
                    rehtml.push('<option  value="'+select[itemname]+'">'+select[itemname]+'</option>');
                }
                else
                {
                    rehtml.push('<option  value="'+select[itemname]+'">'+select[itemname].substr(select[itemname].indexOf('/')+1)+'</option>');
                }
            };
            }
            rehtml = rehtml.join(" ");
            $("#userselect").html(rehtml);
            $("#userselect").bootstrapDualListbox('refresh',true);
            alert("分配成功！");
        }
    
    },'json');
    return false;
})
var departid = "";
$('#depart').change(function(){
    departid = $(this).val();
    $.get('rolelist',{'depart':$(this).val()},function(data) {
        if(data)
        {
            var rehtml = [];
            for (var i = 0; i < data.length; i++) {
            rehtml.push('<option  value="'+data[i].name+'">'+data[i].name+'</option>');
            }
            
        }
        rehtml = rehtml.join(" ");
        $("#rolelist").html(rehtml);
    },'json');

});

$(function(){
    departid = $('#depart').val()
    $.get('rolelist',{'depart':$('#depart').val()},function(data) {
        if(data)
        {
            console.log(data)
            var rehtml = [];
            for (var i = 0; i < data.length; i++) {
            rehtml.push('<option  value="'+data[i].name+'">'+data[i].name+'</option>');
            }
            
        }
        rehtml = rehtml.join(" ");
        $("#rolelist").html(rehtml);
    },'json');
})
    

var rolename = "";
 $("#rolelist").change(function(){
    rolename = $(this).val();
    $.post('showtasklist', {departid:departid,rolename:$(this).val()}, function(data) {
            if(data)
            {
                var selected=data.selected;
                var select=data.select;
                var rehtml = [];
            if(selected)
            {
                for (var i = 0; i < selected.length; i++) {
                    rehtml.push('<option selected="selected"  value="'+selected[i]+'">'+selected[i]+'</option>');
                };
            }
            if(select)
            {
                for (var i = 0; i < select.length; i++) {
                rehtml.push('<option  value="'+select[i]+'">'+select[i]+'</option>');
            };
            }
            rehtml = rehtml.join(" ");
            $("#tasklist").html(rehtml);
            $("#tasklist").bootstrapDualListbox('refresh',true);
            
        }

        },'json');
        
    });
 
   $("#tasksubmit").click(function(){
        $.post('taskdist',{departid:departid,rolename:rolename,tasklist:$("#tasklist").val()}, function(data) {
        	var data = eval('('+data+')');
        	alert(data.msg);
        });
                
    });
})


