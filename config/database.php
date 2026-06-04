<?php
/**
 * Database Configuration & Connection
 * 
 * Uses MySQLi with prepared statements for security.
 * Implements singleton pattern for connection reuse.
 */

// Secure session configuration
if (session_status() === PHP_SESSION_NONE) {
    // Set security headers
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Permissions-Policy: geolocation=(), camera=(), microphone=()");
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self';");

    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
    }

    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_only_cookies', 1);
    
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', 1);
    }
    
    session_start();
}

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'robicodes_portfolio');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Get database connection (singleton)
 * 
 * @return mysqli
 * @throws RuntimeException
 */
function getDBConnection(): mysqli {
    static $conn = null;
    
    if ($conn === null) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $conn->set_charset(DB_CHARSET);
        } catch (mysqli_sql_exception $e) {
            error_log("Database connection failed: " . $e->getMessage());
            
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                throw new RuntimeException("Database connection failed: " . $e->getMessage());
            }
            
            http_response_code(500);
            die('Server error. Please try again later.');
        }
    }
    
    return $conn;
}

/**
 * Execute a prepared SELECT statement
 * 
 * @param string $sql SQL query with placeholders
 * @param string $types Parameter type string (i, d, s, b)
 * @param array $params Parameter values
 * @return mysqli_result|false
 */
function dbSelect(string $sql, string $types = '', array $params = []): mysqli_result|false {
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Execute a prepared INSERT/UPDATE/DELETE statement
 * 
 * @param string $sql SQL query with placeholders
 * @param string $types Parameter type string
 * @param array $params Parameter values
 * @return bool|int Insert ID for INSERT, true for others
 */
function dbExecute(string $sql, string $types = '', array $params = []): bool|int {
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    
    $insertId = $stmt->insert_id;
    $stmt->close();
    
    return $insertId ?: true;
}

/**
 * Get single row from database
 * 
 * @param string $sql SQL query
 * @param string $types Parameter types
 * @param array $params Parameters
 * @return array|null
 */
function dbGetRow(string $sql, string $types = '', array $params = []): ?array {
    $result = dbSelect($sql, $types, $params);
    return $result->fetch_assoc();
}

/**
 * Get all rows from database
 * 
 * @param string $sql SQL query
 * @param string $types Parameter types
 * @param array $params Parameters
 * @return array
 */
function dbGetAll(string $sql, string $types = '', array $params = []): array {
    $result = dbSelect($sql, $types, $params);
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Sanitize output data for HTML display
 * 
 * @param string|null $data Raw data
 * @return string
 */
function sanitizeOutput(?string $data): string {
    return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 * 
 * @return string
 */
function generateCSRFToken(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool
 */
function verifyCSRFToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get site base URL
 * 
 * @return string
 */
function getBaseURL(): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = dirname($_SERVER['SCRIPT_NAME']);
    
    return rtrim($protocol . $host . $path, '/');
}

/**
 * Check if user has a specific role
 * 
 * @param string|array $roles Role name or array of role names
 * @return bool
 */
function hasRole($roles): bool {
    if (!isset($_SESSION['user_role'])) {
        return false;
    }
    
    if (is_array($roles)) {
        return in_array($_SESSION['user_role'], $roles);
    }
    
    return $_SESSION['user_role'] === $roles;
}

/**
 * Require a specific role or redirect
 * 
 * @param string|array $roles
 * @param string $redirect
 */
function requireRole($roles, string $redirect = 'index.php'): void {
    if (!hasRole($roles)) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
        } else {
            header("Location: $redirect");
        }
        exit;
    }
}

/**
 * Check if user is logged in
 * 
 * @return bool
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}
