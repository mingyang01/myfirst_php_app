$(function(){
    $('.tree li:has(ul)').addClass('parent_li').find('> div > label').attr('title', 'Collapse this branch');
    $('.tree li.parent_li > div > label').on('click', function (e) {
    	var This = $(this).parent();
        var children = $(This).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass('glyphicon-plus-sign').removeClass('glyphicon-minus-sign');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass('glyphicon-minus-sign').removeClass('glyphicon-plus-sign');
        }
        e.stopPropagation();
    });
    $('.tree li > div > input').change(function(){
            var ob = $(this);
            changeStatus(ob);
        
    	
    })
    function changeStatus(This)
    {
        var _This = $(This).parent();
        var flag = $(This).attr('checked');
        var children = $(_This).parent('li').find(' > ul > li > div > input');

        if(!flag)
        {
            for (var i = 0; i < children.length; i++) {
                $(children[i]).prop('checked','true')
                $(children[i]).attr('checked','true')
                var childPrent = $(children[i]).parent()
                var hasChild = $(childPrent).parent('li').find(' > ul > li > div > input');
                if(hasChild.length>0)
                {
                   for (var j = 0; j < hasChild.length; j++) {
                        $(hasChild[j]).prop('checked','true')
                        $(hasChild[j]).attr('checked','true')
                    }
                }
                else
                {
                    continue;
                }
            };
            $(This).attr('checked','true')
        }
        else
        {
            for (var i = 0; i < children.length; i++) {
                $(children[i]).removeAttr('checked');
                var childPrent = $(children[i]).parent()
                var hasChild = $(childPrent).parent('li.parent_li').find(' > ul > li > div > input');
                if(hasChild.length>0)
                {
                    for (var j = 0; j < hasChild.length; j++) {
                        $(hasChild[j]).removeAttr('checked');
                    }
                }
                else
                {
                    continue;
                }
            };
            $(This).removeAttr('checked');
        }
    }
    var htmlnull = null;
    $('.add-firstmenu').click(function(){
        $('.first-group').children('input').removeAttr('readOnly');
        $('.second-group').hide()
        $('.third-group').hide()
        $('#first-input').val(htmlnull);
        $('#second-input').val(htmlnull);
        $('#third-input').val(htmlnull);
    })
    $('.add-secondmenu').click(function(){
        $('.third-group').hide();
        $('.second-group').show()
        $('.first-group').children('input').attr('readOnly', 'readOnly');
        $('#second-input').val(htmlnull);
        $('#third-input').val(htmlnull);
        $('.second-group').children('input').removeAttr('readOnly');
        var menuTile = $(this).parent().parent().parent().children('span').children('.menutitle').html()
        $('#first-input').val(menuTile.trim());
    })
    $('.add-thirdmenu').click(function(){
        $('.third-group').show();
        $('.second-group').show();
        $('.first-group').children('input').attr('readOnly', 'readOnly');
        var firstTile = $(this).parent().parent().parent().parent().parent().children('span').children('.menutitle').html()
        var secondTile = $(this).parent().parent().parent().children('span').children('.menutitle').html()
        $('#first-input').val(firstTile.trim());
        $('#second-input').val(secondTile.trim());
        $('.second-group').children('input').attr('readOnly', 'readOnly');
    })
    $("#submit").click(function (e){

        $('#fm').form('submit',{
            url: '/menutree/addmenutree',
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if (result){
                    if(result.flag)
                    {
                        $.messager.show({
                            title: 'message',
                            msg: result.msg
                        });
                        $('#dlg').modal('hide')
                        window.location.reload();
                    }
                    else
                    {
                        $.messager.show({
                            title: 'message',
                            msg: result.msg
                        });
                        $('#dlg').modal('hide')
                        //window.location.reload();
                    }
                    

                }
            }
        });
    });

    
});