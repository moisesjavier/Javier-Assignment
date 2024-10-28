<?php
// add_account.php
include '../includes/head.php'; // Include your header and other required files
require_once '../classes/account.class.php'; // Include the Account class

// Initialize an instance of the Account class
$accountObj = new Account();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data
    $accountObj->first_name = trim($_POST['first_name']);
    $accountObj->last_name = trim($_POST['last_name']);
    $accountObj->username = trim($_POST['username']);
    $accountObj->password = trim($_POST['password']);
    
    // Add the account to the database
    if ($accountObj->add()) {
        $message = "Account added successfully.";
    } else {
        $message = "Failed to add account. Username may already exist.";
    }
}
?>

<div class="content-page">
    <h2>Add Account</h2>
    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" required class="form-control">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" required class="form-control">
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Account</button>
    </form>
</div>

<?php include '../includes/footer.php'; // Include your footer ?>
