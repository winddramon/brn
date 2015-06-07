function general_ajax(url, formname){
    $.ajax({
        type: 'POST',
        url: url ,
        data: $(formname).serialize() ,
        success: function(data){
            for (var sdi in data){
                if($(sdi).length > 0){
                    var sd = data[sdi];
                    for (var sdj in sd){
                        if(sdj == 'innerHTML') {
                            $(sdi)[0].innerHTML = sd[sdj];
                        }
                    }
                }
            }
        },
        dataType: "json"
    });
}

function openShutManager(oSourceObj,oTargetObj,shutAble,oOpenTip,oShutTip){
    var sourceObj = typeof oSourceObj == "string" ? $("#"+oSourceObj) : oSourceObj;
    var targetObj = typeof oTargetObj == "string" ? $("#"+oTargetObj) : oTargetObj;
    var openTip = oOpenTip || "";
    var shutTip = oShutTip || "";
    if(targetObj.style.display!="none"){
        if(shutAble) return;
        targetObj.style.display="none";
        if(openTip  &&  shutTip){
            sourceObj.innerHTML = shutTip;
        }
    } else {
        targetObj.style.display="block";
        if(openTip  &&  shutTip){
            sourceObj.innerHTML = openTip;
        }
    }
}

function change_avatar(sel_img,sel_input ,gender, icon, avatar_dir){
    var avatar_dir = typeof(avatar_dir) == "undefined" ? "" : avatar_dir + '/';
    uri = 'img/' + avatar_dir + gender + "_" + icon + (icon == 0 ? ".gif" : ".png");
    $(sel_img).attr("src", uri);
    $(sel_input).val(uri);
}
