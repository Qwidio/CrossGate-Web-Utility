<?php
$new_members = ['new_member1', 'new_member2']; // New members you want to add
// Fetch current members from the database
$check_orgs = $connects->prepare("SELECT members FROM ogroups WHERE identification = ?;");
$check_orgs->bind_param("s", $oGroups);
$check_orgs->execute();
$result_check_orgs = $check_orgs->get_result();
if ($result_check_orgs->num_rows == 1) {
    $value = $result_check_orgs->fetch_assoc();
    $members = json_decode($value['members'], true); // Decode the current members JSON array
    // Check if members already exist
    foreach ($new_members as $new_member) {
        if (!in_array($new_member, $members)) {
            $members[] = $new_member;  // Add if not already present
        }
    }

    // Encode the updated members array back into JSON
    $updated_members_json = json_encode($members)
    // Update the members in the database
    $update_query = $connects->prepare("UPDATE ogroups SET members = ? WHERE identification = ?;");
    $update_query->bind_param("ss", $updated_members_json, $oGroups);
    $update_query->execute();
    if ($update_query->affected_rows > 0) {
        echo "New members were successfully added.";
    } else {
        echo "No changes made, possibly because the group identification doesn't exist.";
    }
} else {
    echo "Group not found.";
}



$check_orgs = $connects->prepare("SELECT JSON_EXTRACT(members, '$[0]') AS first_member FROM ogroups WHERE identification = ?;");
$check_orgs->bind_param("s", $oGroups);
$check_orgs->execute();
$result_check_orgs = $check_orgs->get_result();

if ($result_check_orgs->num_rows == 1) {
    $value = $result_check_orgs->fetch_assoc();
    $first_member = $value['first_member'];
    echo "First member: " . $first_member;
}


$users_to_check = ['taka21', 'C0rals', 'S4nders']; // Example user list
$found_users = [];

foreach ($users_to_check as $user_to_check) {
    $user_to_check_json = json_encode($user_to_check);

    $check_orgs = $connects->prepare("SELECT 1 FROM ogroups WHERE identification = ? AND JSON_CONTAINS(members, ?) = 1;");
    $check_orgs->bind_param("ss", $oGroups, $user_to_check_json);
    $check_orgs->execute();
    $result_check_orgs = $check_orgs->get_result();

    if ($result_check_orgs->num_rows > 0) {
        // User exists in the group
        $found_users[] = $user_to_check;
    }
}

if (!empty($found_users)) {
    echo "Found users: " . implode(', ', $found_users);
} else {
    echo "No users found in the group.";
}


$check_orgs = $connects->prepare("SELECT members FROM ogroups WHERE identification = ?;");
$check_orgs->bind_param("s", $oGroups);
$check_orgs->execute();
$result_check_orgs = $check_orgs->get_result();

if ($result_check_orgs->num_rows == 1) {
    $value = $result_check_orgs->fetch_assoc();
    $members = json_decode($value['members'], true); // Decode the JSON array

    if (in_array('taka21', $members)) {
        echo "User 'taka21' exists in the group.";
    } else {
        echo "User 'taka21' does not exist in the group.";
    }
}

?>