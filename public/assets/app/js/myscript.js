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
    $('#EditCourse').hide();
    $('#updatecourse').hide();
    $('#UpdateCourse').hide();



    $('.ques_cancel').click(function () {

        $('#UpdateCourse').hide();
        $('#EditCourse').hide();
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
            url: '/course',
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
        html = "";
        $('#ViewCourse').hide();
        $('#option').hide();
        $('#first').hide();
        $('#second').hide();
        $('#subject').hide();
        $('#question').show();
        $.ajax({
            url: '/course',
            type: 'GET',
            dataType    : 'json',
            async: false,
            success: function (data) {
                    for(var i=0;i<data['result'].length;i++){
                        html += "<option value='"+data['result'][i]['id']+"'>"+data['result'][i]['subject_name']+"</option>";
                    }
                    $('#subjectid').html(html);
            }
        });
    });

    $("#question" ).submit(function(event) {
        var formData = {
            'subjectid'       : $('#subjectid').val(),
            'question'       : $('#questionName').val()
        };
        $.ajax({
                type        : 'POST',
                url         : '/question',
                data        : formData,
                dataType    : 'json',
                encode      : true,
            })
            .done(function(data) {
                //var jsObj = JSON.parse(data);
                if (data['rowsAffected'] == 1 && data['status'] ==  'success'){
                    $(':input', '#question')
                        .not(':button, :submit, :reset, :hidden')
                        .val('')
                        .removeAttr('checked')
                        .removeAttr('selected');
                    $("#addUser").trigger('reset');
                    document.getElementById("question").reset();
                    alert('Record added successfully..');
                    console.log(data);
                }else{
                    alert('Un Authorized user');
                }
            });
        event.preventDefault();
    });
    
    $('#add_answer').click(function () {
        var html = "";
        $('#subject').hide();
        $('#question').hide();
        $('#option').hide();
        $('#first').show();
        $.ajax({
            url: '/course',
            type: 'GET',
            dataType    : 'json',
            async: false,
            success: function (data) {
                for(var i=0;i<data['result'].length;i++){
                    html += "<option value='"+data['result'][i]['id']+"'>"+data['result'][i]['subject_name']+"</option>";
                }
                $('#subjectids').html(html);
            }
        });
    });

    $('#subjectids').change(function () {
        var html = "";
        var subjectId = $(this).val();

        $.ajax({
            url: '/question/'+subjectId,
            type: 'GET',
            dataType    : 'json',
            async: false,
            success: function (data) {
                    for(var i=0;i<data['result'].length;i++){
                        html += "<option value='"+data['result'][i]['id']+"'>"+data['result'][i]['question']+"</option>";
                    }
                    $('#questionid').html(html);
            }
        });
    });

    var data = {};

    $("#first" ).submit(function(event) {

        data[1] = {
            'i'     :   1,
            'questionid' : $('#questionid').val(),
            'answer' : $('#answer').val(),
            'status' : $("input[name='status']:checked"). val()
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
        $.post("/answer",data,function (data) {
            alert(data);
            document.getElementById("first").reset();
            document.getElementById("second").reset();
        });
    });
    
    $('#edit_course').click(function () {
        $('#option').hide();
        $('#first').hide();
        $('#second').hide();
        $('#subject').hide();
        var html = '';
        $.ajax({
                type        : 'GET',
                url         : 'course',
                dataType    : 'json',
                encode      : true,
            })
            .done(function(data) {
                if (data['rowsAffected'] >= 1 && data['status'] ==  'success'){
                    html += '<table>';
                    html += '<thead>';
                    html += '<th></th>';
                    html += '<th>Course title</th>';
                    html += '<th>Action</th>';
                    html += '</thead>';
                    html += '<tbody>';
                    for (var i = 0 ; i<data['result'].length ; i++){
                        html += '<tr>';
                        html += '<td> <img src='+data["result"][i].picPath+'  height="42" width="42"> </td>';
                        html += '<td>'+data['result'][i].subject_name+'</td>';
                        html += '<td><a style="cursor: pointer;color: #0000E6" class="EditC" id="'+data['result'][i].id+'">Edit</a>&nbsp;<a style="cursor: pointer;color: #0000E6" class="DeleteC" id="'+data['result'][i].id+'">Delete</a></td>';
                        html += '</tr>';
                    }
                    html += '</tbody>';
                    html += '</table>';
                    html += '<button type="button" class="ques_cancel">Cancel</button>';
                    $('#EditCourse').html(html);
                    $('#EditCourse').show();
                }else{
                    alert('Un Authorized user');
                }
            });
    });

    $("body").delegate( ".ques_cancel", "click", function() {
        $('#option').show();
        $('#edit_course').show();
        $('#first').hide();
        $('#second').hide();
        $('#subject').hide();
        $('#updatecourse').hide();
        $('#EditCourse').hide();
    });

    var courseId;

    $("body").delegate( ".EditC", "click", function() {
        $('#option').hide();
        $('#first').hide();
        $('#second').hide();
        $('#subject').hide();
        $('#EditCourse').hide();
        $('#edit_course').hide();
        $('#UpdateCourse').show();

        courseId = $(this).attr('id');
        //alert($(this).attr('id'));
        $.ajax({
                type        : 'GET',
                url         : 'course/'+$(this).attr('id'),
                dataType    : 'json',
                encode      : true,
            })
            .done(function(data) {
                $('#courseupdate').val(data['result'][0].subject_name);
            });
        $('#updatecourse').show();
    });

    $("#updatecourse").click(function(){

        var jsobj = {
            'course_name' : $('#courseupdate').val()
        }
        $.ajax({
            url: '/course/'+courseId,
            type: 'PUT',
            data: jsobj,
            dataType    : 'json',
            async: false,
            success: function (data) {
                alert(data['status']);
                $('#option').show();
                $('#edit_course').show();
                $('#first').hide();
                $('#second').hide();
                $('#subject').hide();
                $('#updatecourse').hide();
                $('#EditCourse').hide();
                $('#UpdateCourse').hide();
            }
        });

    });
    
    $('#edit_question').click(function () {

        $('#subject').hide();
        $('#question').hide();
        $('#first').hide();
        $('#second').hide();
        $('#EditCourse').hide();
        $('#updatecourse').hide();
        $('#UpdateCourse').hide();

        var html = '';
        $.ajax({
            url: '/course',
            type: 'GET',
            dataType    : 'json',
            async: false,
            success: function (data) {
                html += '<lable>Select Course&nbsp;</lable>';
                html += '<select name="courses" id="courseid">';
                for(var i=0;i<data['result'].length;i++){
                    html += "<option value='"+data['result'][i]['id']+"'>"+data['result'][i]['subject_name']+"</option>";
                }
                html += '</select>';
                $('#EditQuestion').html(html);
                $('#EditQuestion').show();
            }
        });
    });

    $("body").delegate( "#courseid", "change", function(){
        //alert($(this).val());
        var html = '<br><br>';
        var courseId = $(this).val();
        var count = 0;
        $.ajax({
            url: '/question/'+courseId,
            type: 'GET',
            dataType    : 'json',
            async: false,
            success: function (data) {
                count = data['rowsAffected'];
                for (var i =0 ; i<count ; i++){
                    html += '<lable>Edit Question&nbsp;</lable>';
                    html += '<input type="text" id="'+data['result'][i]['id']+'" class="value'+data['result'][i]['id']+'" name="ques'+i+'" value="'+data['result'][i]['question']+'">';
                    html += '&nbsp;&nbsp;<a style="cursor: pointer;text-decoration: underline;color: #0000E6" class="update" id="'+data['result'][i]['id']+'">Update</a><br><br>';
                }
                html += '<br><br><input type="button" value="Back" class="back">';
                $('#UpdateQuestion').html(html);
                $('#UpdateQuestion').show();
            }
        });
    });

    $("body").delegate( ".update", "click", function(){

        var questionId = $(this).attr('id');
        var jsobj = {
            'questionId' : questionId,
            'question'   : $(".value"+questionId).val()
        }
        $.ajax({
            url: '/question/'+questionId,
            type: 'PUT',
            data : jsobj,
            dataType    : 'json',
            async: false,
            success: function (data) {
                alert("Status : "+data['status']);
            }
        });

    });

    $("body").delegate( ".back", "click", function(){
        $('#EditAnswer').hide();
        $('#UpdateAnswer').hide();
        $('#EditQuestion').hide();
        $('#UpdateQuestion').hide();
        $('#UpdateCourse').hide();
        $('#EditCourse').hide();
        $('#ViewCourse').hide();
        $('#option').show();
        $('#first').hide();
        $('#subject').hide();
        $('#getSubject').hide();
        $('#question').hide();
        $('#table').hide();
        $('#edit_question').hide();
        $('#edit_question').show();
    });
    
    $('#edit_answer').click(function () {

        $('#subject').hide();
        $('#question').hide();
        $('#first').hide();
        $('#second').hide();
        $('#EditCourse').hide();
        $('#updatecourse').hide();
        $('#UpdateCourse').hide();

        var html = '';
        $.ajax({
            url: '/question',
            type: 'GET',
            dataType    : 'json',
            success: function (data) {
                html += '<lable>Select Question&nbsp;</lable>';
                html += '<select name="question" id="questionid">';
                for(var i=0;i<data['result'].length;i++){
                    html += "<option value='"+data['result'][i]['id']+"'>"+data['result'][i]['question']+"</option>";
                }
                html += '</select>';
                $('#EditAnswer').html(html);
                $('#EditAnswer').show();
            }
        });


    });
    
    $("body").delegate( "#questionid", "change", function(){
        //alert($(this).val());
        var html = '<br><br>';
        var questionId = $(this).val();
        var count = 0;
        $.ajax({
            url: '/answers/'+questionId,
            type: 'GET',
            dataType    : 'json',
            success: function (data) {
                count = data['rowsAffected'];
                for (var i =0 ; i<count ; i++){
                    html += '<lable>Edit Answer&nbsp;</lable>';
                    html += '<input type="text" id="'+data['result'][i]['id']+'" class="value'+data['result'][i]['id']+'" name="ques'+i+'" value="'+data['result'][i]['ans']+'">';
                    html += '&nbsp;&nbsp;<a style="cursor: pointer;text-decoration: underline;color: #0000E6" class="aupdate" id="'+data['result'][i]['id']+'">Update</a>&nbsp;/&nbsp;';
                    html += '&nbsp;&nbsp;<a style="cursor: pointer;text-decoration: underline;color: #0000E6" class="adelete" id="'+data['result'][i]['id']+'">Delete</a><br><br>';
                }
                html += '<br><br><input type="button" value="Back" class="back">';
                $('#UpdateAnswer').html(html);
                $('#UpdateAnswer').show();
            }
        });
    });

    $("body").delegate( ".aupdate", "click", function(){

         var Id = $(this).attr('id');


        var jsobj = {
            'answerId' : Id,
            'value'   : $(".value"+Id).val()
        }
        $.ajax({
            url: '/answer/'+Id,
            type: 'PUT',
            data : jsobj,
            dataType    : 'json',
            async: false,
            success: function (data) {
                alert("Status : "+data['status']);
            }
        });

    });

    $("body").delegate( ".adelete", "click", function(){
       // alert($(this).attr('id'));
        var Id = $(this).attr('id');
        $.ajax({
            url: '/answer/'+Id,
            type: 'DELETE',
            dataType    : 'json',
            success: function (data) {
                alert("Status : "+data['status']);
            }
        });
    });

    $('#send_mail').click(function () {


        $.ajax({
            url : '/SendMail',
            type : 'GET',
            dataType : 'json',
            success : function (data) {
                alert(data['massage']);
            }
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
    // setInterval(function(){
    //  window.location.href = "login.php";
    //  }, 5000);


});

