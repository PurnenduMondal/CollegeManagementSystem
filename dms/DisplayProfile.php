<?php
function DisplayProfile($userType, $userEmail)
{
?>
<div class="container bg-white ">
    <div class="row">
        <div class="col-md-4 border-end d-flex flex-column align-items-center justify-content-center ">
        <img style="width:215px" class="rounded-circle mt-5" src="https://avatars.githubusercontent.com/u/38310111?v=4">
        <div class="col-md-10"><label >Change Image</label><input style="box-shadow:none;" type="file" class="form-control" placeholder="full name" accept="image/png, image/jpeg"></div>
        </div>

        <div class="col-md-4 border-end d-flex flex-column align-items-center justify-content-center">
            <h4 class="text-right">Profile</h4>
            <div class="col-md-10"><label >Name</label><input style="box-shadow:none;" type="text" class="form-control" placeholder="full name" ></div>
            <div class="col-md-10"><label >Email-ID</label><input style="box-shadow:none;" type="text" class="form-control" placeholder="enter email id" ></div>
            <div class="col-md-10"><label >Password</label><input style="box-shadow:none;" type="text" class="form-control" placeholder="Password" ></div>
        </div>
        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
            <h4 class="text-right">Education</h4>
            <div class="col-md-10"><label >Current Course Name</label><input style="box-shadow:none;" type="text" class="form-control"  placeholder="Course Name"></div>
            <div class="col-md-10"><label >Year Of Admission</label><input style="box-shadow:none;" type="text" class="form-control"  placeholder="Year Of Admission"></div>
            <div class="col-md-10"><label >Current Semester</label><input style="box-shadow:none;" type="text" class="form-control"  placeholder="" value = "1"></div>
        </div>
    </div>
<div class="mt-3 text-center"><button style="box-shadow:none;" class="btn btn-primary profile-button" type="button">Edit Profile</button></div>

</div>

<?php
}
?>