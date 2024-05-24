<?php
if(isset($_POST['loginButton'])){
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    $result = $account->login($username, $password);

    if($result == true){
        $_SESSION['userLoggedIn'] = $username;
        
        if (isAdmin($username)) {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
    }
}

function isAdmin($username) {
    $conn = mysqli_connect('localhost', 'root', '', 'pulseplay');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT admin FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);
        return $row['admin'] == 1;
    } else {
        mysqli_close($conn);
        return false;
    }
}
?>