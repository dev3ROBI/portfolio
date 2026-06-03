<?php
/**
 * Contact Page
 * Contact form with PHP mail integration, validation, and social media links
 */
require_once 'config/database.php';

$pageTitle = 'Contact';
$pageDescription = 'Get in touch with Robiul Islam (RobiCodes). Send a message, discuss a project, or just say hello.';
include 'includes/header.php';

// Handle form submission via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    
    header('Content-Type: application/json');
    
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    $errors = [];
    
    if (strlen($name) < 2) $errors[] = 'Name must be at least 2 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please provide a valid email.';
    if (strlen($subject) < 3) $errors[] = 'Subject must be at least 3 characters.';
    if (strlen($message) < 10) $errors[] = 'Message must be at least 10 characters.';
    
    if (empty($errors)) {
        try {
            // Store in database
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $inserted = dbExecute(
                "INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)",
                'ssssss',
                [$name, $email, $subject, $message, $ip, $ua]
            );
            
            // Send email notification
            $to = 'iam.robi693@gmail.com';
            $emailSubject = "Portfolio Contact: $subject";
            $emailBody = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
            $emailHeaders = "From: $email\r\nReply-To: $email";
            
            mail($to, $emailSubject, $emailBody, $emailHeaders);
            
            echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    }
    exit;
}
?>

<section class="section hero" style="min-height:auto;padding-top:8rem" aria-label="Contact">
    <div class="container">
        <div class="section__header reveal">
            <h2 class="section__title">Get In Touch</h2>
            <p class="section__subtitle">Have a project in mind? Let's build something great together</p>
        </div>

        <div class="contact__grid">
            <!-- Contact Info -->
            <div class="contact__info reveal">
                <div class="contact__info-item">
                    <div class="contact__info-icon">&#128231;</div>
                    <div class="contact__info-text">
                        <h4>Email</h4>
                        <p><a href="mailto:iam.robi693@gmail.com">iam.robi693@gmail.com</a></p>
                    </div>
                </div>

                <div class="contact__info-item">
                    <div class="contact__info-icon">&#127760;</div>
                    <div class="contact__info-text">
                        <h4>Website</h4>
                        <p><a href="https://me.robicodes.xyz" target="_blank">me.robicodes.xyz</a></p>
                    </div>
                </div>

                <div class="contact__info-item">
                    <div class="contact__info-icon">&#128279;</div>
                    <div class="contact__info-text">
                        <h4>GitHub</h4>
                        <p><a href="https://github.com/dev3ROBI" target="_blank">github.com/dev3ROBI</a></p>
                    </div>
                </div>

                <div class="contact__info-item">
                    <div class="contact__info-icon">&#128101;</div>
                    <div class="contact__info-text">
                        <h4>Facebook</h4>
                        <p><a href="https://www.facebook.com/iam.robi69/" target="_blank">facebook.com/iam.robi69</a></p>
                    </div>
                </div>

                <!-- Social Links -->
                <div style="display:flex;gap:var(--spacing-md);margin-top:var(--spacing-lg)">
                    <a href="https://github.com/dev3ROBI" target="_blank" rel="noopener noreferrer" 
                       class="hero__social-link" aria-label="GitHub">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/iam.robi69/" target="_blank" rel="noopener noreferrer" 
                       class="hero__social-link" aria-label="Facebook">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="mailto:iam.robi693@gmail.com" class="hero__social-link" aria-label="Email">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Contact Form -->
            <form class="contact__form glass-card reveal reveal-delay-1" 
                  action="contact.php" method="POST" novalidate>
                <div class="form-group">
                    <input type="text" name="name" class="form-group__input" 
                           placeholder="Your Name" required minlength="2" autocomplete="name">
                    <p class="form-group__error"></p>
                </div>

                <div class="form-group">
                    <input type="email" name="email" class="form-group__input" 
                           placeholder="Your Email" required autocomplete="email">
                    <p class="form-group__error"></p>
                </div>

                <div class="form-group">
                    <input type="text" name="subject" class="form-group__input" 
                           placeholder="Subject" required minlength="3">
                    <p class="form-group__error"></p>
                </div>

                <div class="form-group">
                    <textarea name="message" class="form-group__textarea" 
                              placeholder="Your Message" required minlength="10"></textarea>
                    <p class="form-group__error"></p>
                </div>

                <button type="submit" class="btn btn--primary">
                    &#128640; Send Message
                </button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
