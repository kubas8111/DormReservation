<?php

require_once 'AppController.php';

require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/UserData.php';

require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/UserDataRepository.php';

class UserController extends AppController {
    public function addUser() {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $name = $_POST['name'] ?? '';
            $surname = $_POST['surname'] ?? '';
            $telephone = $_POST['telephone'] ?? '';
            $studentCardID = $_POST['studentCardID'] ?? '';
    
            $userRepository = new UserRepository();
            $userDataRepository = new UserDataRepository();
    
            $userRepository->database->beginTransaction();
    
            $userRepository->addUser($email, $password);
    
            $userID = $userRepository->getLastInsertId();
    
            $userDataRepository->addUserData($userID, $name, $surname, $telephone, $studentCardID);
    
            $userRepository->database->commitTransaction();
        } catch (PDOException $e) {
            $userRepository->database->rollbackTransaction();
            die("Error adding user with data: " . $e->getMessage());
        }
    }

    public function deleteUser() {
        try {
            session_start();
            $userID = $_SESSION['userID'] ?? 0;
    
            $userRepository = new UserRepository();
            $userDataRepository = new UserDataRepository();
    
            $userRepository->database->beginTransaction();
    
            $userDataRepository->deleteUserData($userID);
    
            $userRepository->deleteUser($userID);
    
            $userRepository->database->commitTransaction();
        } catch (PDOException $e) {
            $userRepository->database->rollbackTransaction();
            die("Error deleting user: " . $e->getMessage());
        }
    }
}