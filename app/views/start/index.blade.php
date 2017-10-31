<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $root; ?></title>
    <script src="../assets/site/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript">
        //alert('working..');
        $(document).ready(function () {

            $('#submit').click(function () {

                var jsObj = {};
                var data = $('#login').serializeArray();
                $.each(data,function(index,feild){
                    jsObj[feild.name] = feild.value;
                    console.log(feild.name+" --> "+feild.value);
                });
                $.post('/login',jsObj,function (response) {
//                    alert(response);
//                    console.log(response);

                      var data = JSON.parse(response);
                    //alert( data['error']['invaid_input'].length);
                      if (data['error']){
                          if (data['error']['invaid_input'].length != 0){
                              var html = '';
                              $('#status').html(html);
                              html += "<ul>";
                              for(var i = 0 ; i < data['error']['invaid_input'].length ; i++) {
                                  //alert( data['error']['invaid_input'][i]);
                                  html += '<li>'+data['error']['invaid_input'][i]+'</li>';
                                  //$('#status').text(data['error']['invaid_input'][i]+"\n");
                              }
                          }
                          if (data['error']['missing_feild'].length > 0){
                              for(var i = 0 ; i < data['error']['missing_feild'].length ; i++) {
                                  html += '<li>'+data['error']['missing_feild'][i]+'</li>';
                                  //$('#status').text(data['error']['missing_feild'][i]+"\n");
                              }
                          }
                          html += "</ul>";
                          $('#status').html(html);
                      }else {
                          $(':input', '#login')
                                  .not(':button, :submit, :reset, :hidden')
                                  .val('')
                                  .removeAttr('checked')
                                  .removeAttr('selected');
                          $("#addUser").trigger('reset');
                          document.getElementById("login").reset();
                          $('#status').css( "color", "green" );
                          $('#status').text('Login Successfull..');
                          console.log(data['result']['username']+" "+data['result']['password'])
                      }
;
                });
            });
        });

    </script>
</head>
<body>

<form id="login">
    <p>User Name :<br>
    <input type="text" id="username" name="username" required>
    <span id="email"></span>
    </p>
    <p>
    Password :<br>
    <input type="password" id="password" name="password" required>
    <span id="pass"></span>
    </p>
    <br><br>
    <input id="submit" type="button" value="Submit">
</form>
<p id="status"></p>

</body>
</html>
