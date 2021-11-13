<?php 
function CreateCourseData($conn, $sidebarCategory)
{
    $academicSessionName = explode("_", $sidebarCategory)[0];
    $courseName = strtoupper(explode("-", $academicSessionName)[0]);
    $batchYear = explode("-", $academicSessionName)[2];

    $batchsql = "SELECT `id` FROM `session` WHERE dept_name = '$courseName' AND `year` = $batchYear";
    $sessionTableRow = mysqli_fetch_assoc(mysqli_query($conn, $batchsql));

    $sessionId = $sessionTableRow['id'];
    //mysqli_select_db($conn, $academicSessionName);

    if(isset($_POST['editCourseBtn'])){
        for($i = 1; $i <= 4; $i++){

            $syllabusPDFName = $_POST["syllabus-row$i"];
            $examDate = $_POST["examDate-row$i"];
            $tuitionFees = $_POST["tuitionFees-row$i"];
            $hostelFees = $_POST["hostelFees-row$i"];
            $otherFees = $_POST["otherFees-row$i"];

            if(isset($_FILES["syllabusPDF-row$i"])){ 
                
                $syllabusPDFName = $_FILES["syllabusPDF-row$i"]["name"];
                $syllabusPDFTmporaryName = $_FILES["syllabusPDF-row$i"]["tmp_name"];
                $pdfpath = "pdfs/".$syllabusPDFName;
            }

            if($syllabusPDFName != null){ 
                $res = mysqli_query($conn, "UPDATE `semester` SET `syllabus`='$syllabusPDFName',`examDate`='$examDate',`tuitionFees`=$tuitionFees,`hostelFees`=$hostelFees,`otherFees`=$otherFees WHERE `semesterNo`=$i");
            }
            else {
                $res = mysqli_query($conn, "UPDATE `semester` SET `examDate`='$examDate',`tuitionFees`=$tuitionFees,`hostelFees`=$hostelFees,`otherFees`=$otherFees WHERE `semesterNo`=$i");
            }

            if($res != false && isset($_FILES["syllabusPDF-row$i"]))
            {
                move_uploaded_file($syllabusPDFTmporaryName, $pdfpath);
            }
        }
    }
    ?>
    <style>
        th, td, input{
            text-align: center;
        }
    </style>
    <h5><?php echo $courseName; ?> Batch-<?php echo $batchYear ?></h5>
    <table class="table">
        <thead>
            <th>Semester</th>
            <th>Syllabus</th>
            <th>Exam Date</th>
            <th>Tuition Fees(Rs)</th>
            <th>Hostel Fees(Rs)</th>
            <th>Other Fees(Rs)</th>
        </thead>        
        <form method="post" enctype="multipart/form-data">
        <tbody style="height:100%">
            <button style="font-size:21px;box-shadow:none;border:none;background-color:white;width:100%" type="button" class="editCourseBtn" name="editCourseBtn">
                <img src="icons/edit.svg" alt="" class="courseEditImage" >
            </button>
        <?php 
        $query = "SELECT * FROM `semester` WHERE `sessionId`=$sessionId";
        $res = mysqli_query($conn, $query);
        $count = mysqli_num_rows($res);
        $i = 1;
        if($count > 0)
        while($row=mysqli_fetch_assoc($res)) 
        { ?>
            <tr>
                <td><?php echo $row['semesterNo']?></td>
                <td style="display:flex">
                    <input style="box-shadow:none;border:none;background-color:white;width:150px" type="text" class="form-control courseInput" name="syllabus-row<?php echo $i?>" value="<?php echo $row['syllabus']?>" readonly>
                    <a href="pdfs/<?php echo $row['syllabus']?>"><img src="icons/download2.svg" alt=""></a>
                    <input accept="application/pdf" type="hidden" style="width:205px" class="fileInput" name="syllabusPDF-row<?php echo $i?>">
                </td>
                <td><input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="examDate-row<?php echo $i?>" value="<?php echo $row['examDate']?>" readonly></td>
                <td><input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="tuitionFees-row<?php echo $i?>" value="<?php echo $row['tuitionFees']?>" readonly></td>
                <td><input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="hostelFees-row<?php echo $i?>" value="<?php echo $row['hostelFees']?>" readonly></td>
                <td><input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="otherFees-row<?php echo $i?>" value="<?php echo $row['otherFees']?>" readonly></td>
            </tr>
        <?php 
        $i++;
        } ?>    
        </tbody>
        </form>
    </table>
    <script>
        $(".editCourseBtn").on( "click", function()
        {
            if ($(".courseInput").attr("readonly")){
                $(".courseInput").attr("readonly", false);
                $(".courseInput").css("border", "2px solid #000000");
                $(".courseEditImage").attr("src","icons/save.svg");
                $(".fileInput").attr("type", "file");
            }
            else {
                $(this).attr("type", "submit");
            }
        });
    </script>

    <!-- Set Semester Form -->
    <h5>
        <?php
            $currentSemester = 0;
            $semres = mysqli_query($conn, "SELECT `currentSemester` FROM `session` WHERE `id` = $sessionId");

            if( $semres == false) $currentSemester = 1;
            else $currentSemester = mysqli_fetch_assoc($semres)['currentSemester'];

            if(isset($_POST['selectedSemesterBtn']))
            {
                $currentSemester = $_POST['selectedSemesterBtn'];

                mysqli_query($conn, "UPDATE `session` SET `currentSemester` = $currentSemester WHERE `id` = $sessionId");

            }
            $semCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) FROM `semester` WHERE `sessionId` = $sessionId"))['COUNT(*)'];
        ?>
        <div class="dropdown" style="border:none;">
            Set Current Semester:
        <button style="font-size:21px;box-shadow:none;border:none;background-color:white" class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <strong> <?php echo $currentSemester; ?> </strong>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" >
            <?php 
            for($i=1; $i<=$semCount; $i++){
            ?>
            <li>
                <form method="post">
                    <button type="submit" name="selectedSemesterBtn" value=<?php echo $i ?> class='dropdown-item'><?php echo $i ?></button>
                </form>
            </li>
            <?php
            }
            ?>
        </ul>
        </div>
    </h5>
    
    <!-- add Subject with teacher form -->
    <?php
    if(isset($_POST['addRemoveSubjectBtn'])){
        
        $subject = $_POST['subject'];
        $assignedTeacher = $_POST['assignedTeacher'];

        $res = mysqli_query($conn,"INSERT INTO `subject` (`subject`) VALUES ('$subject')");

        $subjectId = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `id` FROM `subject` WHERE `subject` = '$subject'"))['id'];
        $teacherId = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `id` FROM `teacher` WHERE `name` = '$assignedTeacher'"))['id'];

        if($res == false) {
            echo "$subject Removed";

            mysqli_query($conn, "DELETE FROM `subject` WHERE `subject`='$subject'");
            mysqli_query($conn, "DELETE FROM `tagging` WHERE `subjectId`='$subjectId'");
            mysqli_query($conn, "DELETE FROM `result` WHERE `subjectId`= $subjectId ");

        }
        else{
            echo "$subject Added";

            mysqli_query($conn, "INSERT INTO `tagging`( `teacherId`, `sessionId`, `semesterNo`, `subjectId`) VALUES ($teacherId,$sessionId, $currentSemester, $subjectId)");

            $insertAResultRow = "INSERT INTO `result`( `studentId`, `sessionId`,   `subjectId`, `theory`, `practical`) SELECT DISTINCT `studentId`, $sessionId, $subjectId,0,0 FROM `result`";

            mysqli_query($conn, $insertAResultRow);
        }
    }
    ?>
    <form method="post">
        <div style="display:flex; ">

            <input style="margin: 5px;" name = "subject" type="text" placeholder="Enter Subject">
            Select Teacher:
            <div style="margin: 5px;" class="selectTeacher" style="border:none;">
                
                <select class="form-select" style="box-shadow:none" name="assignedTeacher">
                    <?php 

                    $query = "SELECT `name` FROM `teacher`";
                    $res = mysqli_query($conn, $query);
                    while($row=mysqli_fetch_assoc($res)){
                    ?>
                    <option value=<?php echo $row['name'] ?>> <?php echo $row['name'] ?> </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <button style="margin: 5px;" type="submit" name="addRemoveSubjectBtn" class="btn btn-primary">Add / Remove Subject</button>
        </div>
    </form>
    <!-- All Subjects Table -->
    <table class="table">
        <thead>
            <th>Subject</th>
            <!-- <th>TheoryMax</th>
            <th>PracticalMax</th> -->
            <th>Assigned Teacher</th>
        </thead>
        <tbody style="height:100%">
        <?php 

        $query = "SELECT a.name as `teacher`, c.subject FROM (SELECT `id`, `name` FROM `teacher`) a INNER JOIN (SELECT `teacherId`, `subjectId`, `semesterNo` FROM `tagging`) b ON a.id = b.teacherId INNER JOIN (SELECT * FROM `subject`) c ON b.subjectId = c.id WHERE b.semesterNo = $currentSemester";

        
        $res = mysqli_query($conn, $query);
        while($row=mysqli_fetch_assoc($res)){ ?>
            <tr>
                <td><?php echo $row['subject']?></td>
                <!-- <td><?php //echo $row['theoryMax']?></td>
                <td><?php //echo $row['practicalMax']?></td> -->
                <td><?php echo $row['teacher']?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
                
<?php    
}
?>