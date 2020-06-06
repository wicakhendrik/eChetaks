<?php
    session_start();
    include "../koneksi.php";
    include "../function/function.php";
    $errors1 = array();   
    $errors2 = array();   
    if( isset($_SESSION["login"]) ) {
        header("Location: index.php");   
    }  
    function cekPassword(){
        global $db;
        $query = $db->prepare("SELECT * FROM admins WHERE username=:username and passwd=SHA2(:password,0)");
        $query->bindValue(':username',$_POST['username']);
        $query->bindValue(':password',$_POST['password']);
        $query->execute();
        return $query-> rowCount() > 0; 
    }
    if (isset($_POST['login']))
    {   
        $captcha = $_POST['captcha'];
        if (cekPassword($_POST['username'], $_POST['password']))
        {   
            if($_SESSION['captcha'] == $captcha){
				$_SESSION['login'] = true;
                $_SESSION['id_admin'] = $id_admin;
                header("Location: index.php");  
			}else{
				$errors1 = true;  
			} 
        }
        else{
            $errors2 = true;  
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Login eChetak's</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../asset/icon.png">
    <link rel="stylesheet" type="text/css" href="../css/styleNW.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <style>
    .captcha
      { font: bold 35px "Comic Sans MS", cursive, sans-serif; 
        color:#a0a0a0;background-color:#c0c0c0;
        width:100px;border-radius: 5px;
        text-align:center;
        text-decoration:line-through;
      }
    .errmsg
      {color:#ff0000;}
  </style>
	</head>
	<body>
        <img class="img-iconLogin" src="../img/about/logo.png" alt="icon">
        <form action="" method="post">
            <h2 class="login-title">Login Admin E Chetak's</h2>
            <table class="formLogin">
                <tr>
                    <td class="opt-login">Login</td>
                </tr>
                <!-- menampilkan error login -->
                <tr>
                    <td><input class="input" type="text" name="username" placeholder="Username" required></td>
                </tr> 
                <tr>
                    <td><input class="input" type="password" name="password" placeholder="Password" required></td>
                </tr>
                <?php
                    if ($errors2){
                        echo "<tr><td><span style='color:red; text-align: center; font-size:20px;' >periksa kembali username dan password</span></td></tr><br>";
                    }
                ?>  
                <tr>
                    <td>
                        <h3>Captcha</h3>
                        <center><img src="captcha.php" /></center>
                        <br />
                        <br />
                        <div class="form-group">
                            <input type="text" class="input" name="captcha" placeholder="Masukkan captcha" required/>
                        </div>
                    </td>
                </tr>
                <?php
                    if ($errors1){
                        echo "<tr><td><span style='color:red; text-align: center; font-size:20px;' >captcha salah!</span></td></tr><br>";
                    }
                ?>       
                <tr>
                    <td>
                        <button class="login-btn" type="submit" name="login">Login</button>
                    </td>
                </tr>
            </table>
        </form> 
    </body>
</html>