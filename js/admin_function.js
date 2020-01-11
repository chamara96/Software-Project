$(document).ready(function () {

    $('#submitAddStudent').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doAddStudent();
    });

    function doAddStudent() {
        $("#ModalFormsAddStudent").modal('hide');
        var studentId = $('#studentId').val();
        var studentName = $('#studentName').val();
        var departmentId = $('#departmentId').val();
        var semester = $('#semester').val();

        $.ajax({
            url: 'admin_function.php',
            data: 'action=addstudent&studentId=' + studentId + '&studentName=' + studentName + '&departmentId=' + departmentId + '&semester=' + semester,
            type: "POST",
            success: function (json) {
                // console.log(json);
                $('#modelTitleStatus').text("New Student Added Successfully");
                $("#exampleModalSmall01").modal('show');

            },
            error: function () {
                $('#modelTitleStatus').text("error");
                $("#exampleModalSmall01").modal('show');
            }
        });
    }



    $('#submitAddLecturer').on('click', function (e) {
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doAddLecturer();
    });

    function doAddLecturer() {
        $("#ModalFormsAddLecturer").modal('hide');
        var lecturerId = $('#lecturerId').val();
        var lecturerName = $('#lecturerName').val();
        var departmentId = $('#departmentId').val();
        var email = $('#email').val();

        $.ajax({
            url: 'admin_function.php',
            data: 'action=addlecturer&lecturerId=' + lecturerId + '&lecturerName=' + lecturerName + '&departmentId=' + departmentId + '&email=' + email,
            type: "POST",
            success: function (json) {
                // console.log(json);
                $('#modelTitleStatus').text("New Lecturer Added Successfully");
                $("#exampleModalSmall01").modal('show');

            },
            error: function () {
                $('#modelTitleStatus').text("error");
                $("#exampleModalSmall01").modal('show');
            }
        });
    }



});