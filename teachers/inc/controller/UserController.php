<?php
include_once __DIR__."/../model/UserManager.php";
include_once __DIR__."/../model/records/UserRecord.php";

class UserController { 
    private $user_model;

    public function __construct() {
        $this->user_model = new UserManager();
    }

    public function create_user() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $user = new UserRecord();
                $user->set_first_name($_POST['fName']);
                $user->set_last_name($_POST['lName']);
                $user->set_email($_POST['email']);
                $user->set_passcode($_POST['passcode']);
                $user->set_grade($_POST['grade']); 
                $user->set_class($_POST['class']); 
                
                $this->user_model->create_standard_user($user);
                $_SESSION['success_message'] = "Registration successful!";
                header("Location: /teachers/login"); exit;
            } catch (Exception $e) {
                $_SESSION['errors'][] = $e->getMessage();
            }
        }
        include_once __DIR__."/../view/user_registration.php";
    }

    public function find_users() {
        $user_record = new UserRecord();
        $users = $user_record->find_all(); 
        include_once __DIR__."/../view/users_table.php";
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user = $this->user_model->authenticate_user($_POST['email'], $_POST['passcode']);
            if ($user) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user->get_id();
                $_SESSION['user_role'] = $user->get_role();
                $_SESSION['user_email'] = $user->get_email();
                header("Location: /teachers/"); exit;
            }
            $_SESSION['errors'][] = "Login failed.";
        }
        include_once __DIR__."/../view/login_form.php";
    }

    public function delete_user() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $target_id = isset($_POST["user_id"]) ? (int)$_POST["user_id"] : 0;
                
                // Get the account of the person trying to perform the delete
                $current_user = new UserRecord();
                $current_user->populate_by_id($_SESSION['user_id']);
                
                $this->user_model->delete_user($target_id, $current_user);
                $_SESSION['success_message'] = "User successfully deleted.";
            } catch (Exception $e) {
                $_SESSION['errors'][] = $e->getMessage();
            }
        }
        header("Location: /teachers/"); exit;
    }

    public function change_password() {
        $target_id = (int)$_REQUEST["id"];
        
        // Security check: Allow if user is owner OR if user is a superuser
        $is_owner = ((int)$_SESSION['user_id'] === $target_id);
        $is_superuser = ($_SESSION['user_role'] === 'superuser');

        if (!$is_owner && !$is_superuser) {
            $_SESSION['errors'][] = "You do not have permission to change this password.";
            header("Location: /teachers/"); exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $current_user = new UserRecord();
                $current_user->populate_by_id($_SESSION['user_id']);
                
                $this->user_model->change_user_password($target_id, $_POST['new_passcode'], $current_user);
                $_SESSION['success_message'] = "Password updated successfully.";
                header("Location: /teachers/"); exit;
            } catch (Exception $e) {
                $_SESSION['errors'][] = $e->getMessage();
            }
        }
        include_once __DIR__."/../view/change_password_form.php";
    }

    public function logout() {
        session_destroy();
        header("Location: /teachers/login"); exit;
    }
}