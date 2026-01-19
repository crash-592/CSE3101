<?php

include_once __DIR__ . "/records/UserRecord.php";

class UserManager {

    public function create_standard_user($user) {
        if (!$user instanceof UserRecord) {
            throw new Exception("Invalid user object.");
        }
        $user->set_role("user"); 
        $user->set_passcode(password_hash($user->get_passcode(), PASSWORD_DEFAULT));
        return $user->create();
    }

    public function authenticate_user($email, $passcode) {
        $user_record = new UserRecord();
        $user = $user_record->find_by_email($email);
        if ($user && password_verify($passcode, $user->get_passcode())) {
            return $user;
        }
        return null;
    }

    /**
     * Delete Feature: Only Super Users can delete other accounts.
     */
    public function delete_user($user_id, UserRecord $current_user) {
        // Verification: Check if requester is superuser
        if ($current_user->get_role() !== "superuser") {
            throw new Exception("Access denied. Only super users can delete users.");
        }

        // Prevention: Do not allow superuser to delete themselves
        if ((int)$current_user->get_id() === (int)$user_id) {
            throw new Exception("Security Error: You cannot delete your own account.");
        }

        $user = new UserRecord();
        if ($user->populate_by_id($user_id)) {
            return $user->delete();
        }
        throw new Exception("User not found.");
    }

    /**
     * Password Feature: Users change their own, Super Users change any.
     */
    public function change_user_password($target_user_id, $new_password, UserRecord $current_user) {
        $is_owner = (int)$current_user->get_id() === (int)$target_user_id;
        $is_superuser = $current_user->get_role() === "superuser";

        // Logic: Allow if user owns the account OR is an admin
        if (!$is_owner && !$is_superuser) {
            throw new Exception("Access denied. You do not have permission to change this password.");
        }

        $user = new UserRecord();
        if ($user->populate_by_id($target_user_id)) {
            $user->set_passcode(password_hash($new_password, PASSWORD_DEFAULT));
            return $user->update();
        }
        throw new Exception("User not found.");
    }
}