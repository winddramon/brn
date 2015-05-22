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