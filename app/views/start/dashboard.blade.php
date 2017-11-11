<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <script src="../assets/app/js/jquery-2.1.4.min.js"></script>
    <script src="../assets/app/js/myscript.js"></script>
</head>
<body>
<h1>Login As {{ $result[0]->username }}</h1>


<ul id="option">
    <li> <a style="cursor: pointer;text-decoration: underline" id="new_subject">Create Course</a> </li>
    <li> <a style="cursor: pointer;text-decoration: underline" id="add_question">Questions</a> </li>
    <li> <a style="cursor: pointer;text-decoration: underline" id="add_answer">Answers</a> </li>
    <li> <a style="cursor: pointer;text-decoration: underline" id="edit_course">Edit Course</a> </li>
    <li> <a style="cursor: pointer;text-decoration: underline" id="edit_question">Edit Question</a> </li>
</ul>


<form id="subject">
    <div class="container">
        <label><b>Subject Name</b></label>
        <input type="text" name="subjectname" placeholder="Enter Subject here" id="subjectname" required>
        <input type="hidden" name="call" value="upload"  required><br><br>
        <label><b>Select Icon</b></label>
        <input name="image" type="file" /><br><br>
        <div class="clearfix">
            <button type="submit" name="submit"  class="ques_add">Add</button>
            <button type="button"  class="ques_cancel">Cancel</button>
        </div>
    </div>
</form>

<form id="question">
    <div class="container">
        <label><b>Subject Name</b></label>
        <select  name="subject" id="subjectid">
            <option value="">Choose Subject</option>
        </select>
        <br><br>
        <label><b>Create Question</b></label>
        <input type="text" placeholder="Enter Question here" id="questionName" required>
        <br><br>
        <div class="clearfix">
            <button type="submit" name="submit"  class="ques_add">Add</button>
            <button type="button"  class="ques_cancel">Cancel</button>
        </div>
    </div>
</form>


<form id="first">
    <div class="container">
        <label><b>Select Course</b></label>
        <select  name="subject" id="subjectids">
            <option value="">Choose Subject</option>
        </select>
        <br><br>
        <label><b>Select Question</b></label>
        <select  name="question" id="questionid">
            <option value="">Choose option</option>
        </select>
        <br><br>
        <label><b>Answer</b></label>
        <input type="text" placeholder="Enter answer  here" id="answer" required>
        <br><br>
        <label><b>Status</b></label>
        <input type="radio" name="status"  class="ans_status" value="true">&nbsp;True
        <input type="radio" name="status"  class="ans_status" value="false">&nbsp;False
        <br><br>
        <div class="clearfix">
            <button type="submit"  class="ques_add">Add</button>
            <button type="button" class="ques_cancel">Cancel</button>
        </div>
    </div>
</form>

<form id="second">
    <div class="container">
        <label><b>Answer</b></label>
        <input type="text" id="ans" placeholder="Enter answer  here" name="ans" required>
        <br><br>
        <label><b>Status</b></label>
        <input type="radio" name="status" class="ans_status" value="true">&nbsp;True
        <input type="radio" name="status" class="ans_status" value="false">&nbsp;False
        <br><br>
        <div class="clearfix">
            <button type="submit" class="ques_add">Add</button>
            <button type="button" id="update" class="ques_add">Submit</button>
        </div>
    </div>
</form>

<div id="UpdateCourse">
    <label><b>Edit Course</b></label>
    <input type="text" id="courseupdate"  name="course" required>
    <br><br>
    <div class="clearfix">
        <button type="button" class="ques_cancel">Cancel</button>
        <button type="button" id="updatecourse">Update</button>
    </div>
</div>


<div id="EditCourse"></div>





<br><br><br>
<a href=<?php echo $GLOBALS['website_url']."/logout" ?> >Logout</a>

</body>
</html>