<?php
require_once 'db_con.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Libmanan Food</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

<?php
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Check for the verification code in the database
    $stmt = $conn->prepare("SELECT user_id FROM accounts WHERE verification_code = ? AND email_verified = 0");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Verification Successful
        $update = $conn->prepare("UPDATE accounts SET email_verified = 1, verification_code = NULL WHERE verification_code = ?");
        $update->bind_param("s", $code);
        
        if ($update->execute()) {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Verified!',
                        text: 'Your email has been successfully verified. You may now log in.',
                        confirmButtonText: 'Go to Login',
                        confirmButtonColor: '#A80000', // Your primary brand color
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php'; // Redirect to index
                        }
                    });
                });
            </script>";
        } else {
            // Database update failed
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'System Error',
                        text: 'Something went wrong while updating your account. Please try again.',
                        confirmButtonColor: '#A80000'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                });
            </script>";
        }
    } else {
        // Invalid or Expired Code
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Link',
                    text: 'This verification link is invalid, expired, or the account is already verified.',
                    confirmButtonText: 'Back to Home',
                    confirmButtonColor: '#A80000'
                }).then(() => {
                    window.location.href = 'index.php';
                });
            });
        </script>";
    }
} else {
    // No code provided, direct redirect
    header("Location: index.php");
    exit();
}
?>

</body>
</html>