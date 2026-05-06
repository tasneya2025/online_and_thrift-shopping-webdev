<?php
require_once("db.php");

function insertUser($role, $fullname, $username, $email, $gender, $address, $password, $secret_answer = '')
{
    $conn           = get_db_connection();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedAnswer   = strtolower(trim($secret_answer));

    $fullname      = mysqli_real_escape_string($conn, $fullname);
    $username      = mysqli_real_escape_string($conn, $username);
    $email         = mysqli_real_escape_string($conn, $email);
    $address       = mysqli_real_escape_string($conn, $address);
    $hashedAnswer  = mysqli_real_escape_string($conn, $hashedAnswer);

    if ($role == 1) {
        $query = "INSERT INTO seller (role, fullname, username, email, gender, address, password, secret_answer)
                  VALUES ('$role', '$fullname', '$username', '$email', '$gender', '$address', '$hashedPassword', '$hashedAnswer')";
    } elseif ($role == 2) {
        $query = "INSERT INTO buyer (role, fullname, username, email, gender, address, password, secret_answer)
                  VALUES ('$role', '$fullname', '$username', '$email', '$gender', '$address', '$hashedPassword', '$hashedAnswer')";
    } elseif ($role == 3) {
        $query = "INSERT INTO admin (role, fullname, username, email, gender, address, password, secret_answer)
                  VALUES ('$role', '$fullname', '$username', '$email', '$gender', '$address', '$hashedPassword', '$hashedAnswer')";
    } else {
        return false;
    }

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn) > 0;
}
?>