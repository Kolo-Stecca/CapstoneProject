<?php
require('database.php');

function get_users() {
    global $db;
    $query = 'SELECT employee_id, first_name, last_name, job_title_name, is_active, email FROM employees';
    $statement = $db->prepare($query);
    $statement->execute();
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $users;
}

// Fetch user by email (without checking password in SQL)
function get_user_by_email($email) {
    global $db;
    $query = 'SELECT * FROM employees WHERE email = :email LIMIT 1';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $user;
}

// User login function
function login($email, $password) {
    global $db;
    $query = 'SELECT employee_id, first_name, last_name, job_title_name, is_active, email, password
              FROM employees WHERE email = :email'; // Retrieve the hashed password
    $statement = $db->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    // Verify password using the hashed password
    if ($user && password_verify($password, $user['password'])) {
        return $user; // Return user data if credentials are valid
    }
    return null; // Return null if invalid credentials
}

// Fetch employee by ID with all relevant fields
function get_employee_by_id($employee_id) {
    global $db;

    $query = 'SELECT employee_id, first_name, last_name, job_title_name, is_active, email FROM employees WHERE employee_id = :employee_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);
    $statement->execute();
    $employee = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $employee;
}

// Get all employees for display purposes
function get_all_employees() {
    global $db;
    $query = 'SELECT employee_id, CONCAT(first_name, " ", last_name) AS employee_name FROM employees ORDER BY last_name ASC';
    $statement = $db->prepare($query);
    $statement->execute();
    $employees = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $employees;
}

// Add a new employee
function add_employee($first_name, $last_name, $job_title_name, $is_active, $email, $password) {
    global $db;

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = 'INSERT INTO employees (first_name, last_name, job_title_name, is_active, email, password)
              VALUES (:first_name, :last_name, :job_title_name, :is_active, :email, :password)';
    $statement = $db->prepare($query);
    $statement->bindParam(':first_name', $first_name);
    $statement->bindParam(':last_name', $last_name);
    $statement->bindParam(':job_title_name', $job_title_name);
    $statement->bindParam(':is_active', $is_active, PDO::PARAM_BOOL);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':password', $hashed_password);
    $statement->execute();
    $statement->closeCursor();
}

// Update an existing employee
function update_employee($employee_id, $first_name, $last_name, $job_title_name, $is_active, $email, $password = null) {
    global $db;

    // Prepare the base query
    $query = 'UPDATE employees SET first_name = :first_name, last_name = :last_name, job_title_name = :job_title_name,
              is_active = :is_active, email = :email';

    // Add password update if provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query .= ', password = :password';
    }

    $query .= ' WHERE employee_id = :employee_id';

    $statement = $db->prepare($query);
    $statement->bindParam(':first_name', $first_name);
    $statement->bindParam(':last_name', $last_name);
    $statement->bindParam(':job_title_name', $job_title_name);
    $statement->bindParam(':is_active', $is_active, PDO::PARAM_BOOL);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);

    // Bind the password parameter if it exists
    if (!empty($password)) {
        $statement->bindParam(':password', $hashed_password);
    }

    $statement->execute();
    $statement->closeCursor();
}
?>
