<?php
require __DIR__.'/CreateSemester.php';
require __DIR__.'/CreateCourseData.php';
require __DIR__.'/Assignment.php';
function SidebarDetails($userType, $sidebarCategory, $res, $conn) {
    
    // switch ($userType) {
    //     case "admin":
    //       $sbitems = array("admin", "teacher", "student", "course", "fees", "exam");
    //       break;
    //     case "teacher":
    //       $sbitems = array("admin", "teacher", "student");
    //       break;
    //     case "student":
    //       $sbitems = array("teacher", "student", "course", "fees", "exam");
    //       break;
    //   }

    if(isset($_POST['searchbtn'])){
        $text = $_POST['searchtext'];
        $searchquery = null;
        if ( is_numeric($text) ) {
            $searchquery = "SELECT * FROM $sidebarCategory WHERE id=$text";
        } else {
            $searchquery = "SELECT * FROM $sidebarCategory WHERE name='$text'";
        }
        $res = mysqli_query($conn, $searchquery);
      }
    
    //   if(isset($_POST['deleteItem'])){
    //       $id = $_POST['rowId'];
    //       $query = "DELETE FROM $sidebarCategory WHERE id=$id";
    //       $query_run = mysqli_query($conn, $query);
    //   }
    if(isset($_POST['insertdata']))
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $query = "INSERT INTO $sidebarCategory (id, name,email,password) VALUES ($id,'$name','$name','$id')";
        $query_run = mysqli_query($conn, $query);
    }
    if(isset($_POST['createNewCourseBtn']))
    {
        $courseName = $_POST['courseName'];
        $batchYear = $_POST['batchYear'];
        $semCount = $_POST['semCount'];

        mysqli_query($conn, "INSERT INTO `session`(`dept_name`, `year`, `semesterCount`, `currentSemester`, `startingDate`, `endingDate`) VALUES ($courseName , $batchYear, $semCount, 1,null,null)");
    }
?>
    <div class="p-3" style="width: 100%;">
        <div id='search'>
        <form method="POST">
        <button type="submit" name="profileBtn" class="addbtn btn btn-primary" >Add <?php echo $sidebarCategory?></button>
        </form>
        <form method="post">
            <input type="text" name = "courseName" placeholder="Course Name">
            <input style="width:200px" type="number" min="2010" max="9999" name="batchYear" placeholder="Batch Year">
            <input style="width:200px" type="number" min="2" max="10" name="semCount" placeholder="Number Of Semesters">
            <button type="submit" name="createNewCourseBtn" class="addbtn btn btn-primary" >Add A New Course </button>
        </form>
        <form method="POST" style="display:flex;padding-top:10px">
            <div class="form-group"  >
            <input name="searchtext" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Search a <?php echo ucwords($sidebarCategory)?>">
            </div>
            <div style="bottom:0;padding-left:10px">
            <button name="searchbtn" type="submit" class="btn transparent-bg btn-primary">
                <img src="icons/search_white.svg" alt="">
            </button>
        </div>
        </form>
        </div>

        <?php
        if(($sidebarCategory == 'student' || $sidebarCategory == 'teacher' || $sidebarCategory == 'admin') && $userType != 'student')
        {
            ?>
            <table class="table">
            <thead>
                <tr>
                <th class="text-center" scope="col">ID</th>
                <th class="text-center"scope="col">Name</th>
                <th class="text-center" scope="col">ViewProfile</th>
                <th class="text-center" scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
            <?php
            mysqli_select_db($conn, 'dms');
            $sql = "SELECT * FROM $sidebarCategory";
            if(!$res)
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);
            if($count>0)
            {
            while($row=mysqli_fetch_assoc($res))
            {
                $name = $row['name'];
                $id = $row['id'];
                ?>
                <tr>
                <td class="text-center"><?php echo $id; ?></td>
                <td class="text-center"><?php echo $name; ?></td>
                <td class="text-center">
                    <form method="post">
                    <input type="hidden" name="rowId" value=<?php echo $id; ?>>
                    <button name="profileBtn" type="submit" class="btn btn-sm"><img src="icons/eye2.svg" alt=""></button>
                    </form>
                </td>
                <td class="text-center">
                    <form method="post">
                        <input type="hidden" name="rowId" value=<?php echo $id; ?>>
                        <button name="deleteItem" type="submit" class="btn btn-sm"><img src="icons/delete2.svg" alt=""></button>
                    </form>
                </td>
                
                </tr>
                    
            <?php
            }
            ?>
            </tbody>
            </table>
            <?php
            }
        }
        else{

            echo '<script>document.getElementById("search").remove()</script>';
            
            if(strpos($sidebarCategory, 'batch')  && strpos($sidebarCategory, 'course'))
            {
                CreateCourseData($conn, $sidebarCategory);
            }
            elseif(strpos($sidebarCategory, 'batch')  && strpos($sidebarCategory, 'assignment'))
            {
                Assignment($conn, $sidebarCategory, $userType);
            }
            else
            {
                CreateSemester($conn, $sidebarCategory);
            }
        }
        ?>
    </div>

<?php 
    switch ($userType) {
        case "admin":

          break;
        case "teacher":
            echo "<script> $('.deleteItem, .addbtn').remove(); </script>";
          break;
        case "student":
          echo "<script> $('.deleteItem, .addbtn').remove(); </script>";
          break;
      }
}
?>