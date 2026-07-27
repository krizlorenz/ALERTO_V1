<?php
session_start();
include 'db.php';

// Check if user is logged in

// TEMPORARY BYPASS FOR TESTING
$_SESSION['user_id'] = 1; 
$_SESSION['full_name'] = "Test Student";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // Convert resources array into comma-separated string for SET field
    $resources = isset($_POST['resources']) ? implode(',', $_POST['resources']) : '';
    $lat = $_POST['location_pin_lat'];
    $lng = $_POST['location_pin_lng'];
    $address = $_POST['location_address'];
    $id_type = $_POST['id_type'];
    $exemption_reason = $_POST['exemption_reason'] ?? null;

    $id_doc_path = null;
    $selfie_doc_path = null;

    // File Upload Handling
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (isset($_FILES['id_document']) && $_FILES['id_document']['error'] === 0) {
        $id_doc_path = $upload_dir . time() . "_id_" . basename($_FILES['id_document']['name']);
        move_uploaded_file($_FILES['id_document']['tmp_name'], $id_doc_path);
    }

    if (isset($_FILES['selfie_document']) && $_FILES['selfie_document']['error'] === 0) {
        $selfie_doc_path = $upload_dir . time() . "_selfie_" . basename($_FILES['selfie_document']['name']);
        move_uploaded_file($_FILES['selfie_document']['tmp_name'], $selfie_doc_path);
    }

    // Insert Request into Staging Queue as 'Pending'
    $stmt = $conn->prepare("INSERT INTO help_requests (user_id, resources_needed, location_pin_lat, location_pin_lng, location_address, id_type, id_document_path, selfie_document_path, exemption_reason, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("isddsssss", $user_id, $resources, $lat, $lng, $address, $id_type, $id_doc_path, $selfie_doc_path, $exemption_reason);

    if ($stmt->execute()) {
        $message = "Your assistance request has been submitted successfully and is pending admin verification.";
    } else {
        $message = "Error submitting request: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Assistance - ALERTO</title>
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/components.css">
</head>
<body style="padding: 2rem; background-color: #f4f6f8; font-family: sans-serif;">

    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <h2 style="color: #800000; margin-bottom: 1rem;">Request Emergency Assistance</h2>

        <?php if (!empty($message)): ?>
            <div style="padding: 1rem; background-color: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 1rem;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="request.php" enctype="multipart/form-data">
            
            <!-- 1. Resource Selection -->
            <fieldset style="border: 1px solid #ccc; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
                <legend style="font-weight: bold;">1. Resources Needed</legend>
                <label><input type="checkbox" name="resources[]" value="Food"> Food</label><br>
                <label><input type="checkbox" name="resources[]" value="Water"> Water</label><br>
                <label><input type="checkbox" name="resources[]" value="Medicine"> Medicine</label>
            </fieldset>

            <!-- 2. Location Coordinates (Static Pin Inputs) -->
            <fieldset style="border: 1px solid #ccc; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
                <legend style="font-weight: bold;">2. Static Location Pin</legend>
                <label>Latitude:</label>
                <input type="text" name="location_pin_lat" value="17.61320000" required style="width: 100%; margin-bottom: 0.5rem;"><br>
                <label>Longitude:</label>
                <input type="text" name="location_pin_lng" value="121.72700000" required style="width: 100%; margin-bottom: 0.5rem;"><br>
                <label>Specific Address / Landmark:</label>
                <textarea name="location_address" rows="2" style="width: 100%;"></textarea>
            </fieldset>

            <!-- 3. Identification Selection -->
            <fieldset style="border: 1px solid #ccc; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
                <legend style="font-weight: bold;">3. Identification Verification</legend>
                <label style="display: block; margin-bottom: 0.5rem;">Select ID Option:</label>
                <select name="id_type" required style="width: 100%; padding: 0.5rem; margin-bottom: 1rem;">
                    <option value="CSU_ID">Option A: CSU Student ID Card</option>
                    <option value="GRADES_ASSESSMENT">Option B: Certification of Grades / Assessment Form</option>
                    <option value="VALID_ID">Option B2: Other Valid Government ID</option>
                    <option value="DISASTER_EXEMPTION">Option C: Disaster Exemption (Lost all documents)</option>
                </select>

                <div style="margin-bottom: 0.5rem;">
                    <label>Upload ID / Document Photo:</label><br>
                    <input type="file" name="id_document" accept="image/*,.pdf">
                </div>

                <div style="margin-bottom: 0.5rem;">
                    <label>Upload Verification Selfie (Holding Document):</label><br>
                    <input type="file" name="selfie_document" accept="image/*">
                </div>

                <div>
                    <label>If Exemption Chosen, Explain Situation:</label><br>
                    <textarea name="exemption_reason" rows="2" style="width: 100%;"></textarea>
                </div>
            </fieldset>

            <button type="submit" style="width: 100%; padding: 0.75rem; background-color: #800000; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Submit Assistance Request</button>
        </form>
    </div>

</body>
</html>