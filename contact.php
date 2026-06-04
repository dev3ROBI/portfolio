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
    
    // Verify CSRF token
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrfToken)) {
        echo json_encode(['success' => false, 'message' => 'Invalid form submission. Please refresh and try again.']);
        exit;
    }
    
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
            
            // Send email notification (sanitize headers to prevent injection)
            $to = 'iam.robi693@gmail.com';
            $safeSubject = str_replace(["\r", "\n"], '', $subject);
            $safeName = str_replace(["\r", "\n"], '', $name);
            $emailSubject = "Portfolio Contact: $safeSubject";
            $emailBody = "Name: $safeName\nEmail: $email\nSubject: $safeSubject\n\nMessage:\n$message";
            
            $safeEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $emailHeaders = "From: $safeEmail\r\nReply-To: $safeEmail";
            
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
                    <div class="contact__info-icon"><i class="fas fa-envelope"></i></div>
                    <div class="contact__info-text">
                        <h4>Email</h4>
                        <p><a href="mailto:iam.robi693@gmail.com">iam.robi693@gmail.com</a></p>
                    </div>
                </div>

                <div class="contact__info-item">
                    <div class="contact__info-icon"><i class="fas fa-globe"></i></div>
                    <div class="contact__info-text">
                        <h4>Website</h4>
                        <p><a href="https://me.robicodes.xyz" target="_blank">me.robicodes.xyz</a></p>
                    </div>
                </div>

                <div class="contact__info-item">
                    <div class="contact__info-icon"><i class="fab fa-github"></i></div>
                    <div class="contact__info-text">
                        <h4>GitHub</h4>
                        <p><a href="https://github.com/dev3ROBI" target="_blank">github.com/dev3ROBI</a></p>
                    </div>
                </div>

                <div class="contact__info-item">
                    <div class="contact__info-icon"><i class="fas fa-users"></i></div>
                    <div class="contact__info-text">
                        <h4>Facebook</h4>
                        <p><a href="https://www.facebook.com/iam.robi69/" target="_blank">facebook.com/iam.robi69</a></p>
                    </div>
                </div>

                <!-- Social Links -->
                <div style="display:flex;gap:var(--spacing-md);margin-top:var(--spacing-lg)">
                    <a href="https://github.com/dev3ROBI" target="_blank" rel="noopener noreferrer" 
                       class="hero__social-link" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://www.facebook.com/iam.robi69/" target="_blank" rel="noopener noreferrer" 
                       class="hero__social-link" aria-label="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="mailto:iam.robi693@gmail.com" class="hero__social-link" aria-label="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            <!-- Contact Form -->
            <form class="contact__form glass-card reveal reveal-delay-1" 
                  action="contact.php" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
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
                    <i class="fas fa-rocket"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
