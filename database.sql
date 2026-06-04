-- ============================================================
-- Database Schema for RobiCodes Developer Portfolio
-- Engine: MySQL 8+
-- Character Set: utf8mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS robicodes_portfolio
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE robicodes_portfolio;

-- ============================================================
-- Users Table (Admin Authentication)
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    role ENUM('admin', 'user', 'guest') DEFAULT 'user',
    status ENUM('active', 'pending', 'suspended') DEFAULT 'active',
    verification_token VARCHAR(255) DEFAULT NULL,
    token_expiry DATETIME DEFAULT NULL,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin (password: admin123)
-- IMPORTANT: Change this after first login!
INSERT INTO users (username, email, password_hash, display_name, role) VALUES
('admin', 'iam.robi693@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Robiul Islam', 'admin');

-- ============================================================
-- Categories Table (Project Categorization)
-- ============================================================
CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (name, slug, description) VALUES
('Web Application', 'web-application', 'Full-stack web applications and platforms'),
('API', 'api', 'RESTful API services and backends'),
('Mobile', 'mobile', 'Android and cross-platform mobile applications'),
('Tools', 'tools', 'Developer tools and utilities'),
('AI & Automation', 'ai-automation', 'AI-powered tools and automation systems');

-- ============================================================
-- Projects Table
-- ============================================================
CREATE TABLE IF NOT EXISTS projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    full_description LONGTEXT DEFAULT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    github_url VARCHAR(255) DEFAULT NULL,
    live_url VARCHAR(255) DEFAULT NULL,
    category_id INT UNSIGNED DEFAULT NULL,
    technologies JSON DEFAULT NULL,
    featured TINYINT(1) DEFAULT 0,
    status ENUM('completed', 'in-progress', 'archived') DEFAULT 'completed',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_featured (featured),
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO projects (title, slug, description, full_description, thumbnail, github_url, live_url, category_id, technologies, featured, status) VALUES
(
    'DreamBD',
    'dreambd',
    'A PHP-based social networking platform featuring authentication, friend system, notifications, user profiles, posts, comments, reactions, messaging, and modern responsive UI.',
    'DreamBD is a comprehensive social networking platform built from the ground up using PHP and MySQL. It features a complete user authentication system with email verification, a robust friend request and management system, real-time notifications, user profiles with cover photos, a news feed with posts, comments, and reactions, private messaging, and an admin dashboard for moderation. The platform is built with a mobile-first responsive design and follows MVC architecture principles.',
    'uploads/dreambd.jpg',
    'https://github.com/dev3ROBI/DreamBD',
    'https://dreambd.robicodes.xyz',
    1,
    '["PHP", "MySQL", "JavaScript", "HTML5", "CSS3", "AJAX", "Bootstrap"]',
    1,
    'completed'
),
(
    'Quran API Server',
    'quran-api-server',
    'Full Quran API with Arabic text, Bangla translation, English translation, transliteration, audio support, and downloadable resources.',
    'A comprehensive Quran API server that provides the complete Holy Quran with multiple translations and features. Includes the full Arabic text with diacritical marks, authentic Bangla translations (Tafsir and simple), English translations (Saheeh International and Yusuf Ali), phonetic transliterations, audio recitations from multiple renowned Qaris, chapter and verse metadata, search functionality across all languages, and a downloadable SDK for developers.',
    'uploads/quran-api.jpg',
    'https://github.com/dev3ROBI/Quran-API-Server',
    'https://quran.robicodes.xyz',
    2,
    '["PHP", "MySQL", "REST API", "JSON", "JavaScript", "Audio Processing"]',
    1,
    'completed'
),
(
    'Hadith API Server',
    'hadith-api-server',
    'Complete Hadith API containing Bengali, English, and Arabic content with advanced search and categorization.',
    'A comprehensive Hadith API server that provides authenticated Hadith collections from the major Hadith books including Sahih Bukhari, Sahih Muslim, Sunan Abu Dawud, Jami At-Tirmidhi, Sunan An-Nasai, Sunan Ibn Majah, and others. Features include multi-language support (Arabic, Bengali, English), advanced search with filters, categorization by book, chapter, and narrator, grading information, and complete metadata for each Hadith.',
    'uploads/hadith-api.jpg',
    'https://github.com/dev3ROBI/Hadith-API-Server',
    'https://hadith.robicodes.xyz',
    2,
    '["PHP", "MySQL", "REST API", "JSON", "Full-Text Search"]',
    1,
    'completed'
),
(
    'Quiz Learning App',
    'quiz-learning-app',
    'Android-based educational quiz application supporting SSC, HSC, BCS, and other competitive examinations.',
    'An Android educational quiz application designed for students preparing for SSC, HSC, BCS, and various competitive examinations in Bangladesh. Features include subject-wise quiz categories, timed exams, instant result evaluation, detailed answer explanations, progress tracking, bookmarking difficult questions, offline support, leaderboards, and regular content updates. Built with a clean, intuitive UI optimized for mobile learning.',
    'uploads/quiz-app.jpg',
    'https://github.com/dev3ROBI/Quiz-Learning-App',
    NULL,
    3,
    '["Android", "Java", "SQLite", "REST API", "Material Design"]',
    1,
    'completed'
),
(
    'AI Music Channel Tools',
    'ai-music-channel-tools',
    'Automation tools and management utilities for AI-generated music publishing workflows.',
    'A suite of automation tools designed for AI-generated music content creators. Features include automated video generation from audio tracks, thumbnail creation using AI, metadata optimization for YouTube publishing, scheduling and batch processing, analytics tracking, royalty-free asset management, and integration with major music distribution platforms. Built to streamline the workflow of AI music production and publishing.',
    'uploads/ai-music.jpg',
    'https://github.com/dev3ROBI/AI-Music-Channel-Tools',
    NULL,
    4,
    '["Python", "FFmpeg", "YouTube API", "AI/ML", "Automation"]',
    1,
    'completed'
);

-- ============================================================
-- Skills Table
-- ============================================================
CREATE TABLE IF NOT EXISTS skills (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(255) DEFAULT NULL,
    category ENUM('frontend', 'backend', 'database', 'devops', 'tools', 'other') NOT NULL DEFAULT 'other',
    proficiency TINYINT UNSIGNED NOT NULL DEFAULT 0,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO skills (name, icon, category, proficiency, display_order) VALUES
('HTML5', 'html5.svg', 'frontend', 95, 1),
('CSS3', 'css3.svg', 'frontend', 92, 2),
('JavaScript', 'javascript.svg', 'frontend', 88, 3),
('Bootstrap', 'bootstrap.svg', 'frontend', 85, 4),
('Tailwind CSS', 'tailwind.svg', 'frontend', 80, 5),
('jQuery', 'jquery.svg', 'frontend', 78, 6),
('PHP', 'php.svg', 'backend', 90, 7),
('Python', 'python.svg', 'backend', 75, 8),
('Java', 'java.svg', 'backend', 70, 9),
('Node.js', 'nodejs.svg', 'backend', 65, 10),
('MySQL', 'mysql.svg', 'database', 88, 11),
('PostgreSQL', 'postgresql.svg', 'database', 70, 12),
('SQLite', 'sqlite.svg', 'database', 75, 13),
('Git', 'git.svg', 'devops', 85, 14),
('GitHub Actions', 'github-actions.svg', 'devops', 70, 15),
('Docker', 'docker.svg', 'devops', 60, 16),
('Linux', 'linux.svg', 'devops', 78, 17),
('VS Code', 'vscode.svg', 'tools', 90, 18),
('Android Studio', 'android-studio.svg', 'tools', 72, 19),
('Postman', 'postman.svg', 'tools', 80, 20);

-- ============================================================
-- Statistics Table
-- ============================================================
CREATE TABLE IF NOT EXISTS statistics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL,
    value INT NOT NULL DEFAULT 0,
    suffix VARCHAR(20) DEFAULT '',
    icon VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO statistics (label, value, suffix, icon, sort_order) VALUES
('Total Projects', 25, '+', 'folder.svg', 1),
('GitHub Repositories', 18, '+', 'github.svg', 2),
('Contributions', 500, '+', 'git-commit.svg', 3),
('Experience Years', 3, '+', 'clock.svg', 4);

-- ============================================================
-- Contact Messages Table
-- ============================================================
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) DEFAULT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    replied TINYINT(1) DEFAULT 0,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_read (is_read),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Sessions Table (PHP Session Management)
-- ============================================================
CREATE TABLE IF NOT EXISTS sessions (
    session_id VARCHAR(128) NOT NULL PRIMARY KEY,
    session_data LONGTEXT NOT NULL,
    session_expires INT UNSIGNED NOT NULL,
    INDEX idx_expires (session_expires)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
