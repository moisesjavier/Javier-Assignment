<?php
// view_accounts.php
include '../includes/head.php'; // Include your header and other required files
require_once '../classes/account.class.php'; // Include the Account class

// Create an instance of the Account class
$accountObj = new Account();

// Fetch existing accounts from the database
$accounts = $accountObj->fetchAllAccounts(); // Now calling the new method to fetch all accounts

?>

<div class="content-page">
    <h2>Accounts</h2>
    
    <!-- Add Account Button with updated path -->
    <a href="../account/add_account.php" class="btn btn-primary">Add Account</a>
    
    <div class="table-responsive">
        <table id="table-accounts" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accounts as $account): ?>
                <tr>
                    <td><?php echo htmlspecialchars($account['id']); ?></td>
                    <td><?php echo htmlspecialchars($account['username']); ?></td>
                    <td><?php echo htmlspecialchars($account['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($account['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($account['username']); ?></td>
                    <td>
                        <button class="btn btn-warning edit-account" data-id="<?php echo htmlspecialchars($account['id']); ?>">Edit</button>
                        <button class="btn btn-danger delete-account" data-id="<?php echo htmlspecialchars($account['id']); ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; // Include your footer ?>
