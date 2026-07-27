<?php
session_start();
include 'db.php';

// Simple admin check (Ensure user has admin role or session flag set)
// $_SESSION['role'] = 'admin';

// TEMPORARY BYPASS FOR TESTING
$_SESSION['role'] = 'admin';

// Handle Status Updates (Approve, Reject, In Progress, Completed)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['new_status'])) {
    $req_id = intval($_POST['request_id']);
    $new_status = $_POST['new_status'];
    $admin_notes = trim($_POST['admin_notes']);

    $update_stmt = $conn->prepare("UPDATE help_requests SET status = ?, admin_notes = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $new_status, $admin_notes, $req_id);
    $update_stmt->execute();
    
    header("Location: admin.php");
    exit();
}

// Fetch all pending and active requests
$query = "SELECT help_requests.*, users.full_name, users.student_id_num, users.course_year 
          FROM help_requests 
          JOIN users ON help_requests.user_id = users.id 
          ORDER BY help_requests.submitted_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ALERTO</title>
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/components.css">
</head>
<body style="padding: 2rem; background-color: #f4f6f8; font-family: sans-serif;">

    <div style="max-width: 1200px; margin: 0 auto;">
        <h2 style="color: #800000; margin-bottom: 0.5rem;">COEA Student Council - Admin Control Panel</h2>
        <p style="margin-bottom: 2rem; color: #666;">Review and manage student emergency assistance requests.</p>

        <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd; background-color: #f9f9f9;">
                        <th style="padding: 10px;">Student</th>
                        <th style="padding: 10px;">Resources</th>
                        <th style="padding: 10px;">Location</th>
                        <th style="padding: 10px;">ID Verification</th>
                        <th style="padding: 10px;">Status</th>
                        <th style="padding: 10px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;">
                                    <strong><?php echo htmlspecialchars($row['full_name']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($row['student_id_num']); ?> (<?php echo htmlspecialchars($row['course_year']); ?>)</small>
                                </td>
                                <td style="padding: 10px;">
                                    <?php echo htmlspecialchars($row['resources_needed']); ?>
                                </td>
                                <td style="padding: 10px;">
                                    <small><?php echo htmlspecialchars($row['location_address']); ?></small><br>
                                    <a href="https://maps.google.com/?q=<?php echo $row['location_pin_lat']; ?>,<?php echo $row['location_pin_lng']; ?>" target="_blank" style="color: #800000; font-size: 0.85rem;">View Map Pin</a>
                                </td>
                                <td style="padding: 10px;">
                                    <span style="font-size: 0.85rem; font-weight: bold;"><?php echo htmlspecialchars($row['id_type']); ?></span><br>
                                    <?php if($row['id_document_path']): ?>
                                        <a href="<?php echo htmlspecialchars($row['id_document_path']); ?>" target="_blank" style="font-size: 0.8rem;">[View ID]</a>
                                    <?php endif; ?>
                                    <?php if($row['selfie_document_path']): ?>
                                        <a href="<?php echo htmlspecialchars($row['selfie_document_path']); ?>" target="_blank" style="font-size: 0.8rem;">[View Selfie]</a>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 10px;">
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: bold;
                                        <?php 
                                            if($row['status'] == 'Approved') echo 'background: #d4edda; color: #155724;';
                                            elseif($row['status'] == 'Rejected') echo 'background: #f8d7da; color: #721c24;';
                                            else echo 'background: #fff3cd; color: #856404;';
                                        ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td style="padding: 10px;">
                                    <form method="POST" action="admin.php" style="display: flex; flex-direction: column; gap: 5px;">
                                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                        <select name="new_status" style="padding: 4px; font-size: 0.85rem;">
                                            <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="Approved" <?php if($row['status']=='Approved') echo 'selected'; ?>>Approve</option>
                                            <option value="In Progress" <?php if($row['status']=='In Progress') echo 'selected'; ?>>In Progress</option>
                                            <option value="Completed" <?php if($row['status']=='Completed') echo 'selected'; ?>>Completed</option>
                                            <option value="Rejected" <?php if($row['status']=='Rejected') echo 'selected'; ?>>Reject</option>
                                        </select>
                                        <input type="text" name="admin_notes" placeholder="Notes..." value="<?php echo htmlspecialchars($row['admin_notes'] ?? ''); ?>" style="padding: 4px; font-size: 0.85rem;">
                                        <button type="submit" style="background: #800000; color: #fff; border: none; padding: 4px 8px; border-radius: 3px; cursor: pointer; font-size: 0.85rem;">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center; color: #666;">No assistance requests submitted yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>