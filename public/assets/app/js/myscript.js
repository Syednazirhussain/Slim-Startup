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

/*            var data = JSON.parse(response);
            alert(data['jwt']);
            $.ajax({
                url: '/resource',
                beforeSend: function(request){
                    request.setRequestHeader('Authorization',data['jwt']);
                },
                type: 'GET',
                success: function(data) {
                    alert(data);
                    // Decode and show the returned data nicely.
                },
                error: function() {
                    alert('error');
                }
            });*/

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

    $('#subject').hide();
    $('#question').hide();
    $('#first').hide();
    $('#second').hide();

    $('.ques_cancel').click(function () {
        $('#ViewCourse').hide();
        $('#option').show();
        $('#first').hide();
        $('#subject').hide();
        $('#getSubject').hide();
        $('#question').hide();
        $('#table').hide();
    });

    $('#new_subject').click(function () {
        $('#ViewCourse').hide();
        $('#subject').show();
        $('#option').hide();
    });
    
    $("form#subject").submit(function(){

        var formData = new FormData(this);

        $.ajax({
            url: '/create/course',
            type: 'POST',
            data: formData,
            async: false,
            success: function (data) {
                $(':input', '#subject')
                    .not(':button, :submit, :reset, :hidden')
                    .val('')
                    .removeAttr('checked')
                    .removeAttr('selected');
                $("#subject").trigger('reset');
                alert(data)
            },
            cache: false,
            contentType: false,
            processData: false
        });

        return false;
    });

    $('#add_question').click(function () {
        $('#ViewCourse').hide();
        $('#option').hide();
        $('#first').hide();
        $('#second').hide();
        $('#subject').hide();
        $('#question').show();
        $.post( "/create/questions", function( data ) {
            html = "";
            var jsObj = JSON.parse(data);
            for(var i=0;i<jsObj['result'].length;i++){
                html += "<option value='"+jsObj['result'][i]['id']+"'>"+jsObj['result'][i]['subject_name']+"</option>";
            }
            $('#subjectid').html(html);
        });

    });

    $("#question" ).submit(function(event) {
        var formData = {
            'subjectid'       : $('#subjectid').val(),
            'question'       : $('#questionName').val(),
            'call'           : 'AddQuestions'
        };
        $.ajax({
                type        : 'POST',
                url         : '/add/questions',
                data        : formData,
                dataType    : 'json',
                encode      : true,
            })
            .done(function(data) {
                var jsObj = JSON.parse(data);
                if (jsObj['rowsAffected'] == 1 && jsObj['status'] ==  'success'){
                    $(':input', '#question')
                        .not(':button, :submit, :reset, :hidden')
                        .val('')
                        .removeAttr('checked')
                        .removeAttr('selected');
                    $("#addUser").trigger('reset');
                    document.getElementById("question").reset();
                    alert('Record added successfully..');
                    console.log(jsObj);
                }else{
                    alert('Un Authorized user');
                }
            });
        event.preventDefault();
    });
    
    $('#add_answer').click(function () {
        $('#subject').hide();
        $('#question').hide();
        $('#option').hide();
        $('#first').show();
        $.post( "/create/questions", function( data ) {
            html = "";
            var jsObj = JSON.parse(data);
            for(var i=0;i<jsObj['result'].length;i++){
                html += "<option value='"+jsObj['result'][i]['id']+"'>"+jsObj['result'][i]['subject_name']+"</option>";
            }
            $('#subjectids').html(html);
        });
    });

    $('#subjectids').change(function () {
        //alert($(this).val());
        var jsobj = {
            'subjectid' : $(this).val()
        }
        $.post( "/course/questions",jsobj, function( data ) {
            html = "";
            var jsObj = JSON.parse(data);
            for(var i=0;i<jsObj['result'].length;i++){
                html += "<option value='"+jsObj['result'][i]['id']+"'>"+jsObj['result'][i]['question']+"</option>";
            }
            $('#questionid').html(html);
        });
        
    });

    var data = {};

    $("#first" ).submit(function(event) {

        data[1] = {
            'i'     :   1,
            'questionid' : $('#questionid').val(),
            'answer' : $('#answer').val(),
            'status' : $('.ans_status').val()
        }
        $('input[type="radio"]:not(:checked)');
        $(':input', '#first')
         .not(':button, :submit, :reset, :hidden')
         .val('')
         .removeAttr('checked')
         .removeAttr('selected');
        $('#ViewCourse').hide();
        $('#first').hide();
        $('#second').show();
        console.log(data);
        event.preventDefault();
    });

    var i = 2;

    $("#second" ).submit(function(event) {

        alert( $("input[name='status']:checked"). val() );

        $('#ViewCourse').hide();
        $('#first').hide();
        $('#second').show();
        data[i] = {
            'i'     :   i,
            'answer' : $('#ans').val(),
            'status' : $("input[name='status']:checked"). val()
        }
        i++;
        $('input[type="radio"]:not(:checked)');
        $("#addUser").trigger('reset');
        document.getElementById("second").reset();
        console.log(data);
        event.preventDefault();
    });

    $('#update').click(function () {
        $('#ViewCourse').hide();
        $('#first').hide();
        $('#second').hide();
        $('#option').show();
        data[i] = {
            'i'     :   i,
            'answer' : $('#ans').val(),
            'status' : $("input[name='status']:checked"). val()
        }
        $.post("/add/answers",data,function (data) {
            alert(data);
            document.getElementById("first").reset();
            document.getElementById("second").reset();
            // setInterval(function(){
            //  window.location.href = "login.php";
            //  }, 5000);
        });
    });



/*
    // $('#check').click(function () {
    //     var jsObj = {};
    //     var data = $('#answer').serializeArray();
    //     $.each(data,function(index,feild){
    //         jsObj[feild.name] = feild.value;
    //         console.log(feild.name+" --> "+feild.value);
    //     });
    //     alert(document.cookie);
    //     jsObj['token'] = document.cookie;
    //     console.log(jsObj['username']+" "+jsObj['password']+" "+jsObj['token']);
    //     $.post('/postdata',jsObj,function (response) {
    //          alert(response);
    //     });
    // });
*/
    


});

