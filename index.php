<?php
/**
 * Home Page
 * Hero section with typing effect, CTAs, social links, and feature highlights
 */
require_once 'config/database.php';
$pageTitle = 'Home';
$pageDescription = 'Robiul Islam (RobiCodes) - Professional Web & API Developer. Building modern, scalable applications with PHP, MySQL, and JavaScript.';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero hero--premium" aria-label="Hero introduction">
    <div class="hero__background">
        <div class="hero__glow hero__glow--1"></div>
        <div class="hero__glow hero__glow--2"></div>
    </div>
    <div class="container">
        <div class="hero__content reveal">
            <div class="hero__badge">
                <span class="hero__badge-dot"></span>
                <span class="hero__badge-text">Open for Collaboration</span>
            </div>

            <p class="hero__greeting">Hi there, I'm</p>
            <h1 class="hero__name">Robiul Islam</h1>

            <div class="hero__title-container">
                <span class="hero__typing" data-texts='["Full-Stack Web Architect","PHP & MySQL Specialist","REST API Developer","UI/UX Enthusiast","Performance Optimizer"]'></span>
            </div>

            <p class="hero__description">
                Crafting high-performance digital solutions with precision and passion. 
                Specializing in robust backends, interactive frontends, and seamless API integrations.
            </p>

            <div class="hero__actions">
                <a href="projects.php" class="btn btn--primary btn--lg">
                    <span class="btn__icon"><i class="fas fa-rocket"></i></span>
                    <span class="btn__text">Explore Projects</span>
                </a>
                <a href="contact.php" class="btn btn--secondary btn--lg">
                    <span class="btn__icon"><i class="fas fa-paper-plane"></i></span>
                    <span class="btn__text">Let's Talk</span>
                </a>
            </div>

            <div class="hero__tech-stack">
                <span class="hero__tech-label">Core Stack:</span>
                <div class="hero__tech-icons">
                    <i class="fab fa-php" title="PHP"></i>
                    <i class="fab fa-js" title="JavaScript"></i>
                    <i class="fab fa-python" title="Python"></i>
                    <i class="fab fa-react" title="React"></i>
                    <i class="fab fa-node-js" title="Node.js"></i>
                    <i class="fas fa-database" title="MySQL/PostgreSQL"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Projects Preview -->
<section class="section" aria-label="Featured projects">
    <div class="container">
        <div class="section__header reveal">
            <h2 class="section__title">Featured Projects</h2>
            <p class="section__subtitle">A selection of my best work showcasing diverse skills and technologies</p>
        </div>

        <div class="projects__grid">
            <?php
            $featured = dbGetAll(
                "SELECT p.*, c.name as category_name 
                 FROM projects p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.featured = 1 AND p.status = 'completed' 
                 ORDER BY p.sort_order ASC 
                 LIMIT 3"
            );

            foreach ($featured as $project):
                $techs = json_decode($project['technologies'] ?? '[]', true);
                $desc = htmlspecialchars(substr($project['description'], 0, 120));
            ?>
            <article class="project-card glass-card reveal" 
                     data-category="<?php echo sanitizeOutput($project['category_name'] ?? 'general'); ?>"
                     data-title="<?php echo strtolower(sanitizeOutput($project['title'])); ?>"
                     data-description="<?php echo strtolower(sanitizeOutput($project['description'])); ?>"
                     data-techs="<?php echo strtolower(implode(' ', $techs)); ?>">
                <div class="project-card__image">
                    <?php if ($project['thumbnail'] && file_exists($project['thumbnail'])): ?>
                        <img src="<?php echo sanitizeOutput($project['thumbnail']); ?>" 
                             alt="<?php echo sanitizeOutput($project['title']); ?>" 
                             loading="lazy">
                    <?php else: ?>
                        <div class="project-card__placeholder">
                            <i class="fas fa-code"></i>
                        </div>
                    <?php endif; ?>
                    <div class="project-card__overlay">
                        <div class="project-card__actions">
                            <?php if ($project['github_url']): ?>
                                <a href="<?php echo sanitizeOutput($project['github_url']); ?>" 
                                   target="_blank" rel="noopener noreferrer" 
                                   class="btn-icon" aria-label="View Code"><i class="fab fa-github"></i></a>
                            <?php endif; ?>
                            <?php if ($project['live_url']): ?>
                                <a href="<?php echo sanitizeOutput($project['live_url']); ?>" 
                                   target="_blank" rel="noopener noreferrer" 
                                   class="btn-icon" aria-label="Live Demo"><i class="fas fa-external-link-alt"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="project-card__body">
                    <div class="project-card__header">
                        <span class="project-card__category"><?php echo sanitizeOutput($project['category_name'] ?? 'Web'); ?></span>
                        <?php if ($project['featured']): ?>
                            <span class="project-card__badge">Featured</span>
                        <?php endif; ?>
                    </div>
                    <h3 class="project-card__title"><?php echo sanitizeOutput($project['title']); ?></h3>
                    <p class="project-card__description"><?php echo sanitizeOutput($desc); ?>...</p>
                    <div class="project-card__footer">
                        <div class="project-card__techs">
                            <?php foreach (array_slice($techs, 0, 3) as $tech): ?>
                                <span class="project-card__tech"><?php echo sanitizeOutput($tech); ?></span>
                            <?php endforeach; ?>
                            <?php if (count($techs) > 3): ?>
                                <span class="project-card__tech">+<?php echo count($techs) - 3; ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="projects.php?id=<?php echo $project['id']; ?>" class="btn-text">Details <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:var(--spacing-2xl)" class="reveal">
            <a href="projects.php" class="btn btn--primary">View All Projects <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="section" aria-label="Statistics overview">
    <div class="container">
        <div class="stats__grid">
            <?php
            $stats = dbGetAll("SELECT * FROM statistics ORDER BY sort_order ASC");
            foreach ($stats as $stat):
            ?>
            <div class="stat-card glass-card reveal">
                <div class="stat-card__icon"><i class="fas fa-chart-bar"></i></div>
                <div class="stat-card__value" 
                     data-target="<?php echo (int)$stat['value']; ?>" 
                     data-suffix="<?php echo sanitizeOutput($stat['suffix']); ?>">
                    0<?php echo sanitizeOutput($stat['suffix']); ?>
                </div>
                <p class="stat-card__label"><?php echo sanitizeOutput($stat['label']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
