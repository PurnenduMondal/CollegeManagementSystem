<?php
function Sidebar($conn, $userType, $userEmail, $sidebarCategory) {

  switch ($userType) {
    case "admin":
      $sbitems = array("admin", "teacher", "student");
      break;
    case "teacher":
      $sbitems = array("admin", "teacher", "student");
      break;
    case "student":
      $sbitems = array("teacher", "student");
      break;
  }
  ?>
  <script>
    
    function handleAccordionClick(){
      if(sessionStorage.getItem('clicked') === 'true'){
        sessionStorage.setItem('clicked', 'false')
        console.log(sessionStorage.getItem('clicked'))
      }
      else{
        sessionStorage.setItem('clicked', 'true')
        console.log(sessionStorage.getItem('clicked'))
      }
    }
  </script>
  <div class="p-3" style="width: 250px;">
    <a href="/dms/?sidebarCategory=student" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
      <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
      <span class="fs-3"><?php echo ucwords($userType)?> Panel</span>
    </a>
    <hr>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://avatars.githubusercontent.com/u/38310111?v=4" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong><?php echo ucwords($userEmail)?></strong>
      </a>
      <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
        <li>
          <form method="post">
            <button class="dropdown-item" name="profileBtn" type="submit">Profile</button>
          </form>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <form method="post">
            <button name="signOutBtn" type="submit" class="dropdown-item">Sign out</button>
          </form>
        </li>
      </ul>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto accordion">
        <?php for($x=0; $x < count($sbitems); $x++) { ?>
            <li>
            <a href='http://localhost/dms/?sidebarCategory=<?php echo $sbitems[$x]?>' class='nav-link <?php if ( $sidebarCategory == $sbitems[$x]) { echo 'active'; } else { echo 'link-dark'; }?>'>

              <svg class='bi me-2' width='16' height='16'><use xlink:href='#table'></use></svg>
              All <?php echo ucwords($sbitems[$x])?> Details
            </a>
          </li>
          <?php } ?>
          <li> 
          <div class="dropdown" style="border:none;">
          <button style="font-size:21px;box-shadow:none;border:none;background-color:white" class="dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
           <?php echo "Select Batch" ?>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2" >
          <?php 
            if(isset($_POST['selectAcademicSessionBtn']))
            {
              $_SESSION['selectedAcademicSessionId'] = $_POST['selectAcademicSessionBtn'];
            }
            $batchsql = 'SELECT `id`, `dept_name`, `year` FROM `session`';
            $allbatch = mysqli_query($conn, $batchsql);
            
            while($sessionTableRow = mysqli_fetch_assoc($allbatch))
            {
              $sessionRowId = $sessionTableRow['id'];
              $academicSessionName =  $sessionTableRow['dept_name'].'-batch-'.$sessionTableRow['year'];
            ?>
            <li>
                <form method="post">
                    <button type="submit" name="selectAcademicSessionBtn" value=<?php echo $sessionRowId ?> class='dropdown-item'><?php echo $academicSessionName ?></button>
                </form>
            </li>
            <?php 
            }
            ?>
            </ul>
            </div>
          </li>
        <?php

          $academicSessionId = isset($_SESSION['selectedAcademicSessionId']) ? $_SESSION['selectedAcademicSessionId'] : 1;
          $batchsql = 'SELECT `dept_name`, `year` FROM `session` WHERE `id` = ' . $academicSessionId;

          $allbatch = mysqli_query($conn, $batchsql);
          $sessionTableRow = mysqli_fetch_assoc($allbatch);

          $academicSessionName =  $sessionTableRow['dept_name'] . '-batch-' . $sessionTableRow['year'];

          $semCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) FROM `semester` WHERE `sessionId` = $academicSessionId"))['COUNT(*)'];

        ?>
        <li>
          <div class="accordion-item" >
            <h2 class="accordion-header" id="headingOne">
              <button style="box-shadow:none;height:40px;" class='mca-batch-2020 accordion-button collapsed'  type="button"
              onclick="handleAccordionClick()"
              data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded='false' aria-controls="collapseOne"
              >
              <?php echo strtoupper($academicSessionName); ?>
              </button>
            </h2>
            <div id="collapseOne" class='accordion-collapse collapse'>
              <div class="accordion-body">
                <ul class="nav nav-pills flex-column mb-auto">

                  <li><a href="http://localhost/dms/?sidebarCategory=<?php echo $academicSessionName;?>_course" class="nav-link <?php if ( $sidebarCategory == $academicSessionName.'_course') { echo 'active'; } else { echo 'link-dark'; }?>" style="height:40px">Course Details</a></li>

                  <li><a href="http://localhost/dms/?sidebarCategory=<?php echo $academicSessionName;?>_assignment" class="nav-link <?php if ( $sidebarCategory == $academicSessionName.'_assignment') { echo 'active'; } else { echo 'link-dark'; }?>" style="height:40px">Assignment</a></li>

                  <?php
                  for($i = 1; $i <= $semCount; $i++){
                  ?>
                  <li><a href="http://localhost/dms/?sidebarCategory=<?php echo $academicSessionName; ?>_s<?php echo $i; ?>" class="<?php echo $academicSessionName; ?>_s<?php echo $i; ?> nav-link <?php if ( $sidebarCategory == $academicSessionName."_s".$i) { echo 'active'; } else { echo 'link-dark'; }?>" style="height:40px">Semester <?php echo $i; ?></a></li>
                  <?php 
                  } ?>

                </ul>
              </div>
            </div>
          </div>
        </li>
    </ul>
  </div>
  <script>
      if(sessionStorage.getItem('clicked') === 'true'){
        $('.accordion-collapse').addClass("show");
      }
      else{
        $('.accordion-collapse').removeClass("show");
      }
      // window.onload = function() {
      //   associateIdsAndClickHandler();
      //   $("#"+sessionStorage.getItem('clickedItemId')).addClass("active");

      // }
      // function click(event) {
      //   sessionStorage.setItem('clickedItemId', this.id)
        
      // }
      // function associateIdsAndClickHandler() {
      //   var listAnchers = document.getElementsByTagName('a')
      //   for (var i = 0; i < listAnchers.length; ++i) {
      //     if(listAnchers[i].classList.contains("nav-link"))
      //     {
      //       listAnchers[i].id = i;
      //       listAnchers[i].onclick = click;
      //     }
      //   }
      // }
  </script>
  <?php
}
?>