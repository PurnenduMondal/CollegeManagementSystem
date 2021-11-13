<?php
function Login($conn)
{
    if(isset($_POST['submit']))
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT email, password, 'admin' as tablename FROM admin WHERE email='$email' AND password=$password
        union all
        SELECT email, password, 'teacher' as tablename FROM teacher WHERE email='$email' AND password=$password
        union all
        SELECT email, password, 'student' as tablename FROM student WHERE email='$email' AND password=$password";

        $res = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($res);
        $row=mysqli_fetch_assoc($res);
        $tablename = $row['tablename'];
        //echo "<script>alert('$tablename');</script>";
        if($count==1)
        {
            $_SESSION['userType'] = $tablename;
            $_SESSION['userEmail'] = $email;
            if($tablename == 'admin')
            {
                echo "<script>location.replace('http://localhost/dms/?sidebarCategory=admin');
                </script>";
            } elseif($tablename == 'teacher'){ 
                echo "<script>location.replace('http://localhost/dms/?sidebarCategory=student');
                </script>";
            } elseif($tablename == 'student'){
                echo "<script>location.replace('http://localhost/dms/?sidebarCategory=teacher');
                </script>";
            }
        }
    }

?>

<div class="login">
    <h1 class="text-center mt-5">Login</h1>
    <form action="" method="POST" class="text-center">
    <label for="exampleInputEmail1">Email:  </label>
    <input type="text" name="email" placeholder="Enter Email"><br><br>

    <label for="inputPassword2" class="sr-only">Password:  </label> 
    <input type="password" name="password" placeholder="Enter Password"><br><br>

    <input type="submit" name="submit" value="Login" class="btn btn-primary btn-sm">
    <br><br>
    </form>
</div>
<?php
}
?>