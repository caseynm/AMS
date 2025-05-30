<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $pdo;

    public function __construct() {
        global $pdo; // Use the global PDO connection
        $this->pdo = $pdo;
    }

    // Create a new user
    public function createUser($name, $email, $password, $role = 'regular') {
        // In a real app, hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Handle error, e.g., duplicate email
            error_log("User creation error: " . $e->getMessage());
            return false;
        }
    }

    // Get user by email
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetUserByEmail error: " . $e->getMessage());
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id) {
        $sql = "SELECT id, name, email, role, created_at FROM users WHERE id = :id"; // Exclude password
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetUserById error: " . $e->getMessage());
            return false;
        }
    }

    // Get all users (basic version, for superuser task assignment)
    public function getAllUsers() {
        $sql = "SELECT id, name, email, role FROM users ORDER BY name ASC";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("GetAllUsers error: " . $e->getMessage());
            return [];
        }
    }

    // Verify password (to be used during login)
    public function verifyPassword($email, $password) {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Password matches
        }
        return false; // Password doesn't match or user not found
    }
}
?>
