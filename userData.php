<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function getUsersFromDatabase() {
    $conn = mysqli_connect('localhost', 'root', '', 'pulseplay');
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    $users = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
    mysqli_close($conn);
    return $users;
}

function deleteUserFromDatabase($userId) {
    $conn = mysqli_connect('localhost', 'root', '', 'pulseplay');
    $sql = "DELETE FROM users WHERE userid = $userId";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
}

function addUserToDatabase($username, $firstName, $lastName, $email, $password, $signUpDate, $profilePic, $admin) {
    $conn = mysqli_connect('localhost', 'root', '', 'pulseplay');
    $username = mysqli_real_escape_string($conn, $username);
    $firstName = mysqli_real_escape_string($conn, $firstName);
    $lastName = mysqli_real_escape_string($conn, $lastName);
    $email = mysqli_real_escape_string($conn, $email);
    $passwordHash = md5($password); // Encrypting password using MD5
    $signUpDate = mysqli_real_escape_string($conn, $signUpDate);
    $profilePic = mysqli_real_escape_string($conn, $profilePic);
    $admin = (int)$admin;
    $sql = "INSERT INTO users (username, firstName, lastName, email, password, signUpDate, profilePic, admin) VALUES ('$username', '$firstName', '$lastName', '$email', '$passwordHash', '$signUpDate', '$profilePic', $admin)";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteButton']) && isset($_POST['userIdToDelete'])) {
        $userIdToDelete = $_POST['userIdToDelete'];
        deleteUserFromDatabase($userIdToDelete);
        echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
        exit();
    } elseif (isset($_POST['addUser'])) {
        $username = $_POST['username'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $signUpDate = $_POST['signUpDate'];
        $profilePic = $_POST['profilePic'];
        $admin = $_POST['admin'];
        addUserToDatabase($username, $firstName, $lastName, $email, $password, $signUpDate, $profilePic, $admin);
        echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
        exit();
    }
}

$users = getUsersFromDatabase();

include("includes/includedFilesAdmin.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            font-size: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 7px;
            border-bottom: 2px solid #ddd;
            text-align: left;
        }

        th {
            padding: 8px;
            border: 3px solid #ddd;
            text-align: left;
        }

        tr:hover {
            background-color: #282828;
        }

        .add-user-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
            display: inline-block;
        }

        .add-user-button:hover {
            background-color: #45a049;
        }

        .form-container {
            display: none;
            background-color: #282828;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .form-container label {
            margin-bottom: 10px;
            display: block;
        }

        .form-container input[type=text], .form-container input[type=email], .form-container input[type=password], .form-container input[type=date], .form-container input[type=number] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            color: black;
        }

        .form-container button[type=submit], .close-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button[type=submit]:hover, .close-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>User List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Sign Up Date</th>
                <th>Profile Pic</th>
                <th>Admin</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['userid']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['firstName']; ?></td>
                <td><?php echo $user['lastName']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['password']; ?></td>
                <td><?php echo $user['signUpDate']; ?></td>
                <td><?php echo $user['profilePic']; ?></td>
                <td><?php echo $user['admin']; ?></td>
                <td>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" name="userIdToDelete" value="<?php echo $user['userid']; ?>">
                        <button type="submit" class="add-user-button" name="deleteButton">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button class="add-user-button" id="toggleAddUserForm">Add User</button>

    <div class="form-container" id="addUserForm">
        <h2>Add New User</h2>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required><br>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="signUpDate">Sign Up Date:</label>
            <input type="date" id="signUpDate" name="signUpDate" required><br>

            <label for="profilePic">Profile Pic:</label>
            <input type="text" id="profilePic" name="profilePic" required><br>

            <label for="admin">Admin:</label>
            <input type="number" id="admin" name="admin" min="0" max="1" required><br>

            <button type="submit" name="addUser">Add User</button>
            <button type="button" class="close-button" id="closeAddUserForm">Close</button>
        </form>
    </div>

    <script>
        document.getElementById('toggleAddUserForm').addEventListener('click', function() {
            var formContainer = document.getElementById('addUserForm');
            if (formContainer.style.display === 'none') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        });

        document.getElementById('closeAddUserForm').addEventListener('click', function() {
            document.getElementById('addUserForm').style.display = 'none';
        });
    </script>
</body>
</html>