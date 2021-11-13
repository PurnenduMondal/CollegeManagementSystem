<?php
function CreateSemester($conn, $sidebarCategory) {

    $academicSessionName = explode("_", $sidebarCategory)[0];
    $semester = explode("_", $sidebarCategory)[1]; // s1 to s4
    $semesterNo = (int)str_replace('s', '', $semester); // replace s1 with 1 in $semester.result
    $courseName = strtoupper(explode("-", $academicSessionName)[0]);
    $batchYear = explode("-", $academicSessionName)[2];

    $batchsql = "SELECT `id` FROM `session` WHERE dept_name = '$courseName' AND `year` = $batchYear";
    $sessionTableRow = mysqli_fetch_assoc(mysqli_query($conn, $batchsql));

    $sessionId = $sessionTableRow['id'];

    if(isset($_POST['saveResultBtn'])){

        $id = $_POST['id'];
        $subjectId = $_POST['subjectId'];
        $theory = $_POST['theory'];
        $practical = $_POST['practical'];

        $query = "UPDATE `result` SET `theory`=$theory,`practical`=$practical WHERE `studentId`=$id AND `subjectId`=$subjectId AND `sessionId` = $sessionId";

        mysqli_query($conn, $query);
    }

    if(isset($_POST['saveDiscountBtn'])){

        $id = $_POST['id'];
        $feesType = $_POST['feesType'];
        $discount = $_POST['discount'];
        $discount_type = $feesType.'_discount';
        
        if($feesType === 'hostel'){
            $hostelAssigned = (int)isset($_POST['hostelAssigned']);
            $query = "UPDATE `fees` SET `$discount_type`=$discount, `hostelAssigned`=$hostelAssigned WHERE `studentId`=$id AND `sessionId` = $sessionId AND `semesterNo` = $semesterNo";
        }
        else{
            $query = "UPDATE `fees` SET `$discount_type`=$discount WHERE `studentId`=$id AND `sessionId` = $sessionId AND `semesterNo` = $semesterNo";
        }
        
        $query_run = mysqli_query($conn, $query);
    }
?>
<style>
    .collapsing {
        -webkit-transition: none;
        transition: none;
        display: none;
    }
    button, input{
        box-shadow:none;
    }
    tbody{
        display:block;
        height:500px;
        overflow:auto;
    }
    thead, .tbody, tr {
        display:table;
        width:100%;
        table-layout:fixed;
    }
    thead {
        width: calc( 100% - 1em )
    }
    table {
        width:400px;
    }
    .hiddenRow {
        padding: 0 !important;
    }
</style>
<h1>Exam Result</h1>
<table class="table">
<thead>
    <tr>
        <th scope="col" class="text-center">Id</th>
        <th scope="col"
        class="text-center">Name</th>
        <th scope="col"
        class="text-center subjectColumn">Subject</th>
        <th scope="col"
        class="text-center">Theory</th>
        <th scope="col"
        class="text-center">Practical</th>
        <th scope="col"
        class="text-center">TotalResult</th>
        <th scope="col"
        class="text-center">Payable Fees</th>
        <th scope="col"
        class="text-center">Profile</th>
    </tr>
</thead>
<tbody >
    <?php
    mysqli_select_db($conn, 'dms') or die(mysqli_error($conn)); 
    $sql = "SELECT * FROM student WHERE `batch`=$batchYear AND `courseName`='$courseName'";
    $res = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($res);
    if($count>0)
    {
    $j=0;
    while($row=mysqli_fetch_assoc($res))
    {
        $name = $row['name'];
        $id = $row['id'];
        
        $totalResultQuery = "SELECT SUM(`theory`) as theoryTotal, SUM(`practical`) as practicalTotal FROM (SELECT `studentId`, `sessionId`, `subjectId`, `theory`, `practical` FROM `result` WHERE `studentId` = $id) a INNER JOIN `tagging` b ON a.subjectId = b.subjectId WHERE a.sessionId = $sessionId AND b.semesterNo = $semesterNo";

        $totalResultRow=mysqli_fetch_assoc(mysqli_query($conn, $totalResultQuery));

        ?>
            <tr id="rowId-<?php echo $id;?>" >
                <td class="text-center"><?php echo $id; ?></td>
                <td class="text-center"><?php echo $name; ?></td>
                <td class="text-center subjectColumn"></td>
                <td class="text-center"><?php echo $totalResultRow['theoryTotal']?></td>
                <td class="text-center"><?php echo $totalResultRow['practicalTotal']?></td>
                <td>
                    <input type="hidden" name="rowId" value=<?php echo $id; ?>>
                    <button
                    class="btn btn-sm displayResultBtn" 
                    style = "display: block;margin: auto;box-shadow:none;"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseResult<?php echo $id?>"
                    aria-expanded="false"
                    aria-controls="collapseResult<?php echo $id?>"
                    name="<?php echo $id; ?>" 
                    ><?php echo $totalResultRow['theoryTotal'] + $totalResultRow['practicalTotal'] ?><img class="resultBtnImg<?php echo $id; ?>" src="icons/expand_more.svg" alt="">
                    </button>
                </td>
                <td class="text-center">
                    <input type="hidden" name="rowId" value=<?php echo $id; ?>>
                    <button
                    class="btn btn-sm displayFeesBtn" 
                    style = "display: block;margin: auto;box-shadow:none;"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseFees<?php echo $id?>"
                    aria-expanded="false"
                    aria-controls="collapseFees<?php echo $id?>"
                    name="<?php echo $id; ?>" 
                    > <?php if($j==0 ) echo "25700 Paid"; else echo "25700 NotPaid";?><img class="feesBtnImg<?php echo $id; ?>" src="icons/expand_more.svg" alt="">
                    </button>
                </td>
                <td>
                    <form method="post">
                    <input type="hidden" name="rowId" class value=<?php echo $id; ?>>
                    <button
                    style = "display: block;margin: auto;"
                    name="profileBtn" type="submit" class="btn btn-sm"><img src="icons/eye2.svg" alt="">
                    </button>
                    </form>
                </td>
            </tr>  
            <!-- Hidden Rows-->                          
            <!-- Collapsed Result Data Rows-->
            <tr class="hide-table-padding">
            <td class="hiddenRow">                            
            <div                                 
            method="post" 
            id="collapseResult<?php echo $id?>" 
            class="accordian-body collapse  border-end border-start accordian-body text-center">
            <table class="table" style="border-top: 1px solid black;">
            <?php
            
            $result_sql = "SELECT `studentId`, `theory`, `practical`, a.subjectId, b.semesterNo, a.sessionId, c.subject FROM (SELECT `studentId`, `sessionId`, `subjectId`, `theory`, `practical` FROM `result` WHERE `studentId` = $id) a INNER JOIN `tagging` b ON a.subjectId = b.subjectId INNER JOIN `subject` c ON b.subjectId = c.id  WHERE a.sessionId = $sessionId AND b.semesterNo = $semesterNo";

            $result_res = mysqli_query($conn, $result_sql);  
            $i = 0;
            while($result_row=mysqli_fetch_assoc($result_res))
            {           
                $theory = $result_row['theory'];
                $practical = $result_row['practical'];
                $subject = $result_row['subject'];  
                $subjectId = $result_row['subjectId'];
            ?>
            <tbody style="height:100%">
                <form method="post">
                <tr style="border-bottom: 1px solid black;margin-bottom:1px;border-left: 1px solid black;border-right: 1px solid black;">
                    <td class="text-center">isBacklogged</td>
                    <td class="text-center">
                        <input style="box-shadow:none; width:25px; height:25px;" type="checkbox" class="form-check-input isBacklogged" name="isBacklogged" value="<?php //echo $isBacklogged ?>" <?php //if($isBacklogged == 1) echo "checked";?>>
                    </td>
                    <td class="text-center"><?php echo $subject ?></td>
                    <td class="text-center">
                        <input style="box-shadow:none;text-align:center;border:none;background-color:white" type="number" class="form-control resultInput<?php echo $id;?><?php echo $subject;?>" name="theory" value="<?php echo $theory ?>" readonly>
                    </td>
                    <td class="text-center">
                        <input style="box-shadow:none;text-align:center;border:none;background-color:white" type="number" class="form-control resultInput<?php echo $id;?><?php echo $subject;?>" name="practical" value="<?php echo $practical ?>" readonly>
                    </td>
                    <td class="text-center">
                        <button 
                        style = "display: block;margin: auto;box-shadow:none;"
                        type="button"
                        class="btn btn-sm editResultBtn"
                        name="<?php echo $id ?>-<?php echo $subject ?>" 
                        ><?php echo $theory+$practical ?><img src="icons/edit.svg" alt=""></button>
                    </td>
                    <td>-</td>
                    <td class="text-center">
                        <input type="hidden" name="id" value=<?php echo $id ?>>
                        <input type="hidden" name="subjectId" value=<?php echo $subjectId ?>>
                        <button 
                        style = "display: block;margin: auto;box-shadow:none;"
                        type="submit"
                        class="btn btn-primary btn-sm saveResultBtn"
                        name="saveResultBtn" 
                        >Save Result
                        </button>
                    </td>
                </tr>
                </form>
                </tbody>
            <?php
            $i++;
            }
            ?>                                
            </table>
            </div>
            </td>
            </tr>

            <!--Collapsed Fees Data Rows-->
            <tr class="hide-table-padding">
            <td class="hiddenRow">                            
            <div                                 
            method="post" 
            id="collapseFees<?php echo $id?>" 
            class="accordian-body collapse  border-end border-start accordian-body text-center">
            <table class="table" style="border-top: 1px solid black;">
            <?php

            $semeterFeesSQL = "SELECT * FROM `fees` WHERE `studentId`=$id AND `semesterNo`=$semesterNo AND `sessionId`=$sessionId";
            $semeterFeesSQLresponse = mysqli_query($conn, $semeterFeesSQL);  
            $frow=mysqli_fetch_assoc($semeterFeesSQLresponse);
            
            $i = 0;
            $courseFeesSQL = "SELECT * FROM `semester` WHERE `semesterNo`=$semesterNo AND `semesterNo`=$semesterNo AND `sessionId`=$sessionId";
            $courseFeesSQLresponse = mysqli_query($conn, $courseFeesSQL);
            $courseFeesSQLrow = mysqli_fetch_assoc($courseFeesSQLresponse);

            $t = $courseFeesSQLrow['tuitionFees'];
            $h = $courseFeesSQLrow['hostelFees'];
            $o = $courseFeesSQLrow['otherFees'];
            
            $tatolPayableFees = ($t-$t*($frow['tuition_discount']/100)) 
                                + ($h-$h*($frow['hostel_discount']/100)) 
                                + ($o-$o*($frow['others_discount']/100));
            ?>
            <script>
                var clickedRowId = $(this).attr('name');
            </script>
            <?php
            while($i <= 2)
            {      
                $isPaid = $frow['isPaid'];
                $hostelAssigned = $frow['hostelAssigned'];
                $feesType="";
                $feesAmount=0;
                $discount = 0;

                if($i===0) {
                    $feesType="tuition";
                    $feesAmount=$t;
                    $discount = $frow['tuition_discount'];
                } 
                elseif($i === 1) {
                    $feesType="hostel";
                    $feesAmount=$h; 
                    $discount = $frow['hostel_discount']; 
                }
                elseif($i === 2) {
                    $feesType="others";
                    $feesAmount=$o;
                    $discount = $frow['others_discount'];
                }
            ?>
            <tbody style="height:100%">
                <form method="post">
                <tr style="border-bottom: 1px solid black;margin-bottom:1px;border-left: 1px solid black;border-right: 1px solid black;">
                    <td class="text-center"><?php if($i == 1) echo "Hostel Assigned"; else echo '"';?></td>
                    <td class="text-center">
                    <?php
                    if($i == 1){
                    ?>
                    <input style="pointer-events: none;box-shadow:none; width:25px; height:25px;" type="checkbox" class="form-check-input hostelCheckbox" name="hostelAssigned" value="<?php echo $hostelAssigned ?>" <?php if($hostelAssigned == 1) echo "checked";?>>
                    <?php }else{
                        echo '"';
                    } ?>
                    </td>
                    <td class="text-center ">
                        <?php echo ucwords($feesType);?>
                    </td>
                    <td class="text-center">
                        <?php echo $feesAmount?>
                    </td>
                    <td class="text-center" style="display:flex;border:none">
                        <input style="box-shadow:none;text-align:center;border:none;background-color:white;width:60px; height:40px;padding:none" type="number" class="form-control <?php echo $id; ?>-FeesDiscount-<?php echo $feesType; ?>" name="discount" value="<?php echo $discount ?>" readonly>                             
                        <button 
                        style = "margin: 0;box-shadow:none;"
                        type="button"
                        class="btn btn-sm editDiscountBtn"
                        name="<?php echo $id ?>-<?php echo $feesType ?>" 
                        >%Off<img src="icons/edit.svg" alt=""></button>
                    </td>
                    <td class="text-center">
                        Rs.
                        <?php 
                        if($feesType == "hostel" && !$hostelAssigned) {echo "0";}
                        else {echo $feesAmount-$feesAmount*($discount/100);}
                        ?> 
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="id" value=<?php echo $id ?>>
                        <input type="hidden" name="feesType" value=<?php echo $feesType ?>>
                        <button 
                        style = "display: block;margin: auto;box-shadow:none;"
                        type="submit"
                        class="btn btn-primary btn-sm saveDiscountBtn"
                        name="saveDiscountBtn">
                        Save Fees
                        </button>
                    </td>
                    
                </tr>
                </form>
                </tbody>
                <!-- Hidden Rows Ending-->  
            <?php
            $i++;
            }
            ?>                                
            </table>
            </div>
            </td>
            </tr>
            
    <?php
        $j++;    
        }
    }
    ?>
</tbody>
</table>
<script>
    //prevent the "Confirm Form Resubmission" dialog when navigating from ProfileDetails to ExamDetails
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href )
    }

    var expandedResultRows = 0,expandedFeesRows=0
    var expandedResultRowsObj = {"expandedResultRows":expandedResultRows}
    var expandedFeesRowsObj = {"expandedFeesRows":expandedFeesRows}

    window.onload = function() {

        if(sessionStorage.getItem('expandedResultRowsObj') === null) {
            sessionStorage.setItem("expandedResultRowsObj", JSON.stringify(expandedResultRowsObj))
        }else{
            expandedResultRowsObj = JSON.parse(sessionStorage.getItem('expandedResultRowsObj'))
            expandedResultRows = expandedResultRowsObj["expandedResultRows"]
        }
        if(sessionStorage.getItem('expandedFeesRowsObj') === null) {
            sessionStorage.setItem("expandedFeesRowsObj", JSON.stringify(expandedFeesRowsObj))
        }else{
            expandedFeesRowsObj = JSON.parse(sessionStorage.getItem('expandedFeesRowsObj'));
            expandedFeesRows = expandedFeesRowsObj["expandedFeesRows"]
        }


        for (const rowId in expandedResultRowsObj) {
            var isExpanded = expandedResultRowsObj[rowId]
            if(isExpanded){
                $('#collapseResult'+rowId).addClass("show")
                $(".resultBtnImg"+rowId).attr("src","icons/expand_less.svg")
            }
            else{
                $('#collapseResult'+rowId).removeClass("show")
                $(".resultBtnImg"+rowId).attr("src","icons/expand_more.svg")
            }
        }

        for (const rowId in expandedFeesRowsObj) {
            var isExpanded = expandedFeesRowsObj[rowId];
            if(isExpanded){
                $('#collapseFees'+rowId).addClass("show")
                $(".feesBtnImg"+rowId).attr("src","icons/expand_less.svg")
            }
            else{
                $('#collapseFees'+rowId).removeClass("show")
                $(".feesBtnImg"+rowId).attr("src","icons/expand_more.svg")
            }
        }

        if(expandedResultRows == 0){
            $(".subjectColumn").hide()
        }
        else{
            $(".subjectColumn").show()
        }
    }

    
    $(".subjectColumn").hide(); 
    $(".displayResultBtn").on( "click", function()
    { 
        
        var clickedRowId = $(this).attr('name');
        if ($(".resultBtnImg"+clickedRowId).attr("src") == "icons/expand_more.svg"){
            expandedResultRowsObj["expandedResultRows"] += 1
            expandedResultRowsObj[clickedRowId] = true;
            $(".resultBtnImg"+clickedRowId).attr("src","icons/expand_less.svg");
            
        }
        else {
            expandedResultRowsObj["expandedResultRows"] -= 1
            expandedResultRowsObj[clickedRowId] = false;
            $(".resultBtnImg"+clickedRowId).attr("src","icons/expand_more.svg");
        }
        sessionStorage.setItem("expandedResultRowsObj", JSON.stringify(expandedResultRowsObj));

        if(expandedResultRowsObj["expandedResultRows"] == 0){
            $(".subjectColumn").hide();
        }
        else{
            $(".subjectColumn").show();
        }

    });

    $(".displayFeesBtn").on( "click", function()
    { 
        
        var clickedRowId = $(this).attr('name');
        if ($(".feesBtnImg"+clickedRowId).attr("src") == "icons/expand_more.svg"){
            expandedFeesRowsObj["expandedFeesRows"] +=1
            expandedFeesRowsObj[clickedRowId] = true;
            $(".feesBtnImg"+clickedRowId).attr("src","icons/expand_less.svg");
        }
        else {
            expandedFeesRowsObj["expandedFeesRows"] -=1
            expandedFeesRowsObj[clickedRowId] = false;
            $(".feesBtnImg"+clickedRowId).attr("src","icons/expand_more.svg");
        }
        sessionStorage.setItem("expandedFeesRowsObj", JSON.stringify(expandedFeesRowsObj));

    });

    $(".editResultBtn").on( "click", function()
    { 
        var id = $(this).attr('name').split("-")[0];
        var subject = $(this).attr('name').split("-")[1];

        if ($('.resultInput'+id+subject).attr("readonly")){
            $('.resultInput'+id+subject).attr("readonly", false);
            $('.resultInput'+id+subject).css("border", "2px solid #000000");
        }
        else {
            $('.resultInput'+id+subject).attr("readonly", true);
            $('.resultInput'+id+subject).css("border", "none");
        }
    });

    $(".editDiscountBtn").on( "click", function()
    { 
        var id = $(this).attr('name').split("-")[0];
        var feesType = $(this).attr('name').split("-")[1];

        if ($('.'+id+'-FeesDiscount-'+feesType).attr("readonly")){
            $('.'+id+'-FeesDiscount-'+feesType).attr("readonly", false);
            $('.'+id+'-FeesDiscount-'+feesType).css("border", "2px solid #000000");
        }
        else {
            $('.'+id+'-FeesDiscount-'+feesType).attr("readonly", true);
            $('.'+id+'-FeesDiscount-'+feesType).css("border", "none");
        }

        if(feesType == "hostel" && $('.hostelCheckbox').css("pointer-events")=="none")
        {
            $('.hostelCheckbox').css("pointer-events","all");
            $('.hostelCheckbox').css("border", "2px solid #000000");
            $('.hostelCheckbox').attr("value", 0);
        }
        else
        {
            $('.hostelCheckbox').css("pointer-events","none")
            $('.hostelCheckbox').css("border", "1px solid rgba(0,0,0,.25)");
            $('.hostelCheckbox').attr("value", 1);
        }
    });

</script>
<?php
}
?>
