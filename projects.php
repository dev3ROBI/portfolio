<?php
/**
 * Projects Page
 * Dynamic project cards with category filtering, search, and detailed views
 */
require_once 'config/database.php';

// Handle project details view
$detailedProject = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $detailedProject = dbGetRow(
        "SELECT p.*, c.name as category_name FROM projects p 
         LEFT JOIN categories c ON p.category_id = c.id 
         WHERE p.id = ?", 
        'i', 
        [(int)$_GET['id']]
    );
}

$pageTitle = $detailedProject ? $detailedProject['title'] : 'Projects';
$pageDescription = $detailedProject ? substr(strip_tags($detailedProject['description']), 0, 160) : 'Explore my portfolio...';
include 'includes/header.php';

if ($detailedProject): 
    $techs = json_decode($detailedProject['technologies'] ?? '[]', true);
?>

<section class="section hero" style="min-height:auto;padding-top:10rem">
    <div class="container">
        <div class="project-detail glass-card reveal">
            <div class="project-detail__grid">
                <div class="project-detail__image">
                    <?php if ($detailedProject['thumbnail']): ?>
                        <img src="<?php echo sanitizeOutput($detailedProject['thumbnail']); ?>" alt="<?php echo sanitizeOutput($detailedProject['title']); ?>">
                    <?php else: ?>
                        <div class="project-detail__placeholder"><i class="fas fa-code"></i></div>
                    <?php endif; ?>
                </div>
                <div class="project-detail__content">
                    <div class="project-detail__header">
                        <span class="project-card__category"><?php echo sanitizeOutput($detailedProject['category_name'] ?? 'Web'); ?></span>
                        <h1 class="project-detail__title"><?php echo sanitizeOutput($detailedProject['title']); ?></h1>
                    </div>
                    
                    <div class="project-detail__desc">
                        <?php echo nl2br(sanitizeOutput($detailedProject['full_description'] ?? $detailedProject['description'])); ?>
                    </div>

                    <div class="project-detail__meta">
                        <h3>Technologies</h3>
                        <div class="project-card__techs">
                            <?php foreach ($techs as $tech): ?>
                                <span class="project-card__tech"><?php echo sanitizeOutput($tech); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="project-detail__actions" style="margin-top:2rem;display:flex;gap:1rem">
                        <?php if ($detailedProject['live_url']): ?>
                            <a href="<?php echo sanitizeOutput($detailedProject['live_url']); ?>" target="_blank" class="btn btn--primary">Live Demo</a>
                        <?php endif; ?>
                        <?php if ($detailedProject['github_url']): ?>
                            <a href="<?php echo sanitizeOutput($detailedProject['github_url']); ?>" target="_blank" class="btn btn--secondary">View Code</a>
                        <?php endif; ?>
                        <a href="projects.php" class="btn btn--ghost">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php else: ?>

    <?php
        if (!isset($categories)) {
            $categories = [];
        }
        if (!isset($projects)) {
            $projects = [];
        }
    ?>

<section class="section hero" style="min-height:auto;padding-top:8rem" aria-label="Projects">
    <div class="container">
        <div class="section__header reveal">
            <h2 class="section__title">My Projects</h2>
            <p class="section__subtitle">A showcase of applications, APIs, and tools I've built</p>
        </div>

        <!-- Controls -->
        <div class="projects__controls reveal">
            <div class="projects__search">
                <span class="projects__search-icon"><i class="fas fa-search"></i></span>
                <input type="text" class="projects__search-input" placeholder="Search projects..." aria-label="Search projects">
            </div>
            <div class="projects__filters" role="tablist" aria-label="Filter by category">
                <button class="projects__filter-btn active" data-category="all" role="tab">All</button>
                <?php foreach ($categories as $cat): ?>
                    <button class="projects__filter-btn" data-category="<?php echo $cat['slug']; ?>" role="tab">
                        <?php echo sanitizeOutput($cat['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Project Grid -->
        <div class="projects__grid" role="list">
            <?php if (empty($projects)): ?>
                <div class="glass-card" style="text-align:center;padding:var(--spacing-3xl);grid-column:1/-1">
                    <p style="font-size:var(--font-size-lg);color:var(--text-muted)">No projects found.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($projects as $project): 
                $techs = json_decode($project['technologies'] ?? '[]', true);
                $categorySlug = $project['category_slug'] ?? 'uncategorized';
            ?>
            <article class="project-card glass-card reveal" 
                     role="listitem"
                     data-category="<?php echo sanitizeOutput($categorySlug); ?>"
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
                    <p class="project-card__description"><?php echo sanitizeOutput($project['description']); ?></p>
                    <div class="project-card__footer">
                        <div class="project-card__techs">
                            <?php foreach (array_slice($techs, 0, 4) as $tech): ?>
                                <span class="project-card__tech"><?php echo sanitizeOutput($tech); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
