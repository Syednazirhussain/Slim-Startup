$(document).ready(function(){

    var websiteUrl =  $( "#website_info" ).data( "website-url" );
    var token;
    $('#submit').click(function () {

        var html = '';
        var jsObj = {};
        var data = $('#login').serializeArray();
        $.each(data,function(index,feild){
            jsObj[feild.name] = feild.value;
            console.log(feild.name+" -- "+feild.value);
        });
        $.post('/login',jsObj,function (response) {
            console.log(response);
            
            // @todo This is Session Based Authentication Code

            var data = JSON.parse(response);
            $('#email').text('');
            $('#pass').text('');

            if ( data['status'] == 'success' && data['rowsAffected'] == 1 ){
                    window.location = websiteUrl+"/dashboard";
            }else{
                $.each(data,function (key,value) {
                    if (key == 'username' || key == 'Email'){
                        $('#email').append(value+"  / ");
                    }else if(key == 'password'){
                        $('#pass').text(value);
                    }else if (key == 'status' && value != 'ok'){
                        $('#status').text(value);
                    }
                });
            }


            // @TODO This is Token Based Authentication Code
            /*
            // var data = JSON.parse(response);
            // alert(data['jwt']);
            // $.ajax({
            //     url: '/resource',
            //     beforeSend: function(request){
            //         request.setRequestHeader('Authorization',data['jwt']);
            //     },
            //     type: 'GET',
            //     success: function(data) {
            //         alert(data);
            //         // Decode and show the returned data nicely.
            //     },
            //     error: function() {
            //         alert('error');
            //     }
            // });
            */

            /*
            // if (data['status'] === 'success'){
            //     alert(response);
            //     window.location = websiteUrl+"/dashboard";
            // }else{
            //     alert(response);
            // }
            // token = response;
            // document.cookie = token;
            // alert(token);
            // if (token != null){
            //     window.location = websiteUrl+"/dashboard";
            // }
            // alert(token+" website url "+websiteUrl);
            */
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

