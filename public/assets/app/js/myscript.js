$(document).ready(function(){
    var websiteUrl =  $( "#website_info" ).data( "website-url" );
    var token;
    $('#submit').click(function () {

        var html = '';
        var jsObj = {};
        var data = $('#login').serializeArray();
        $.each(data,function(index,feild){
            jsObj[feild.name] = feild.value;
            console.log(feild.name+" --> "+feild.value);
        });
        $.post('/login',jsObj,function (response) {
            var data = JSON.parse(response);
            if (data['status'] === 'success'){
                alert(response);
                window.location = websiteUrl+"/dashboard";
            }else{
                console.log(response);
            }
            // token = response;
            // document.cookie = token;
            // alert(token);
            // if (token != null){
            //     window.location = websiteUrl+"/dashboard";
            // }
            // alert(token+" website url "+websiteUrl);
        });
    });

    $('#check').click(function () {
        var jsObj = {};
        var data = $('#answer').serializeArray();
        $.each(data,function(index,feild){
            jsObj[feild.name] = feild.value;
            console.log(feild.name+" --> "+feild.value);
        });
        alert(document.cookie);
        jsObj['token'] = document.cookie;
        console.log(jsObj['username']+" "+jsObj['password']+" "+jsObj['token']);
        $.post('/postdata',jsObj,function (response) {
             alert(response);
        });
    });


});

