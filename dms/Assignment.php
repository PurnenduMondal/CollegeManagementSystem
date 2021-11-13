<?php
function Assignment($conn, $sidebarCategory, $userType) {

    $academicSessionName = explode("_", $sidebarCategory)[0];
    $semester = explode("_", $sidebarCategory)[1]; // s1 to s4
    $semesterNo = (int)str_replace('s', '', $semester); // replace s1 with 1 in $semester.result
    $courseName = strtoupper(explode("-", $academicSessionName)[0]);
    $batchYear = explode("-", $academicSessionName)[2];

    $batchsql = "SELECT `id` FROM `session` WHERE dept_name = '$courseName' AND `year` = $batchYear";
    $sessionTableRow = mysqli_fetch_assoc(mysqli_query($conn, $batchsql));

    $sessionId = $sessionTableRow['id'];


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

    <h4><?php echo $courseName; ?> Batch-<?php echo $batchYear ?></h4>
    <div style="display:flex;">
        <h5>All Assignments: </h5>
        <button style="box-shadow:none;border:none;background-color:white;" type="button" class="editCourseBtn btn btn-primary btn-xs" name="editCourseBtn"><img src="icons/edit.svg" alt="" class="courseEditImage" ></button>
        <button style="box-shadow:none;" class="btn btn-primary "> Add Assignment </button>
    </div>

    <table class="table">
        <thead>
            <th>Teacher</th>
            <th>Subject</th>
            <th>Upload Questions</th>
            <th>TotalMarks</th>
            <th>Date</th>
            <th>Deadline</th>
            <th>Delete</th>
        </thead>        
        <form method="post" enctype="multipart/form-data">
        <tbody style="height:100%">
        <?php 
        $res = mysqli_query($conn, "SELECT * FROM `assignment` WHERE `sessionId`=$sessionId");
        $count = mysqli_num_rows($res);
        $i = 1;
        if($count > 0)
        while($row=mysqli_fetch_assoc($res)) 
        { ?>
            <tr>
                <td>
                <div style="" class="selectTeacher" style="border:none;">
                    <select class="form-select" style="box-shadow:none; height:35px; " name="assignedTeacher">
                        <?php 
                        $tres = mysqli_query($conn, "SELECT `name` FROM `teacher`");
                        while($trow=mysqli_fetch_assoc($tres)){
                        ?>
                        <option value=<?php echo $trow['name'] ?>> <?php echo $trow['name'] ?> </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>    
                </td>

                <td>
                <div style="" class="selectTeacher" style="border:none;">
                    <select class="form-select" style="box-shadow:none;height:35px" name="assignedTeacher">
                        <?php 
                        $sres = mysqli_query($conn, "SELECT `subject` FROM `subject`");
                        while($sRow=mysqli_fetch_assoc($sres)){
                        ?>
                        <option value=<?php echo $sRow['subject'] ?>> <?php echo $sRow['subject'] ?> </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                </td>

                <td style="display:flex;"  >
                    <input style="box-shadow:none;border:none;background-color:white;width:150px" type="text" class="form-control courseInput" name="syllabus-row<?php echo $i?>" value="<?php echo $row['questions']?>" readonly>
                    <input class="form-control" accept="application/pdf" type="hidden" style="width:205px;" class="fileInput" name="syllabusPDF-row<?php echo $i?>">
                    <a href="pdfs/<?php echo $row['questions']?>"><img src="icons/download2.svg" alt=""></a>
                </td>

                <td><input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="tuitionFees-row<?php echo $i?>" value="<?php echo $row['totalMarks']?>" readonly></td>
                <td><input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="hostelFees-row<?php echo $i?>" value="<?php echo $row['assignmentDate']?>" readonly></td>
                <td><input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="otherFees-row<?php echo $i?>" value="<?php echo $row['assignmentDeadline']?>" readonly></td>
                <td class="text-center">
                    <form method="post">
                        <input type="hidden" name="rowId" value=<?php echo $row['id']; ?>>
                        <button name="deleteItem" type="submit" class="btn btn-sm"><img src="icons/delete2.svg" alt=""></button>
                    </form>
                </td>
            </tr>
        <?php 
        $i++;
        } ?>    
        </tbody>
        </form>
    </table>

    <?php
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

    <div style="display:flex;">
        <h5>Student Responses: </h5>
        <button style="box-shadow:none;border:none;background-color:white;" type="button" class="editCourseBtn btn btn-primary btn-xs" name="editCourseBtn"><img src="icons/edit.svg" alt="" class="courseEditImage" ></button>
    </div>

    <table class="table">
        <thead>
            <th>StudentId</th>
            <th>Student</th>
            <th>Subject</th>
            <th>Answers</th>
            <th>Marks</th>
        </thead>        
        <form method="post" enctype="multipart/form-data">
        <tbody style="height:100%">
        <?php 
        $resultRes = mysqli_query($conn, "SELECT * FROM `assignmentresult` WHERE `sessionId`=$sessionId ORDER BY `studentId` ASC");
        $count1 = mysqli_num_rows($res);
        
        $i = 1;
        while($resultRow=mysqli_fetch_assoc($resultRes)) 
        { 
            ?>
            <tr>
                <td>
                <input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="examDate-row<?php echo $i?>" value="<?php echo $resultRow['studentId']?>" readonly>    
                </td>

                <td>
                <div style="" class="selectTeacher" style="border:none;">
                    <?php 
                    $studentId = $resultRow['studentId'];
                    $query = "SELECT `name` FROM `student` WHERE `id`=$studentId";
                    $sRow=mysqli_fetch_assoc(mysqli_query($conn, $query))

                    ?>
                    <input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="examDate-row<?php echo $i?>" value="<?php echo $sRow['name']?>" readonly>  
                </div>
                </td>

                <td>
                    <div style="" class="selectTeacher" style="border:none;">
                        <?php 
                        $subjectId = $resultRow['subjectId'];
                        $query = "SELECT `subject` FROM `subject` WHERE `id`= $subjectId ";
                        $sRow=mysqli_fetch_assoc(mysqli_query($conn, $query));
                        ?>
                        <input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="examDate-row<?php echo $i?>" value="<?php echo $sRow['subject']?>" readonly>  
                    </div>
                </td>

                <td style="display:flex;">
                    <input style="box-shadow:none;border:none;background-color:white;width:150px" type="text" class="form-control courseInput" name="syllabus-row<?php echo $i?>" value="<?php echo $resultRow['answers']?>" readonly>
                    <input class="form-control" accept="application/pdf" type="hidden" style="width:205px;" class="fileInput" name="syllabusPDF-row<?php echo $i?>">
                    <a href="pdfs/<?php echo $resultRow['answers']?>"><img src="icons/download2.svg" alt=""></a>
                </td>

                <td>
                    <input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="tuitionFees-row<?php echo $i?>" value="<?php echo $resultRow['marks']?>" readonly>
                </td>

                <!-- <td><input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="hostelFees-row<?php //echo $i?>" value="<?php //echo $resultRow['assignmentDate']?>" readonly></td>
                <td><input style="box-shadow:none;border:none;background-color:white" type="text" class="form-control courseInput" name="otherFees-row<?php //echo $i?>" value="<?php //echo $resultRow['assignmentDeadline']?>" readonly></td> -->
            </tr>
        <?php 
        $i++;
        } ?>    
        </tbody>
        </form>
    </table>
<?php
}
?>