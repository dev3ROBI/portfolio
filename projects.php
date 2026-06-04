<?php
/**
 * Projects Page
 * Dynamic project cards with category filtering, search, and detailed views
 */
require_once 'config/database.php';
$pageTitle = 'Projects';
$pageDescription = 'Explore my portfolio of web applications, APIs, mobile apps, and development tools. Featuring DreamBD, Quran API, Hadith API, and more.';
include 'includes/header.php';

$categories = dbGetAll("SELECT * FROM categories ORDER BY name ASC");
$projects = dbGetAll(
    "SELECT p.*, c.name as category_name, c.slug as category_slug 
     FROM projects p 
     LEFT JOIN categories c ON p.category_id = c.id 
     ORDER BY p.featured DESC, p.sort_order ASC, p.created_at DESC"
);
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
                        <i class="fas fa-code" style="font-size:2rem;color:var(--text-muted)"></i>
                    <?php endif; ?>
                    <?php if ($project['featured']): ?>
                        <span class="project-card__featured">Featured</span>
                    <?php endif; ?>
                </div>
                <div class="project-card__body">
                    <h3 class="project-card__title"><?php echo sanitizeOutput($project['title']); ?></h3>
                    <p class="project-card__description"><?php echo sanitizeOutput($project['description']); ?></p>
                    
                    <?php if (!empty($techs)): ?>
                    <div class="project-card__techs">
                        <?php foreach ($techs as $tech): ?>
                            <span class="project-card__tech"><?php echo sanitizeOutput($tech); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="project-card__actions">
                        <?php if ($project['github_url']): ?>
                            <a href="<?php echo sanitizeOutput($project['github_url']); ?>" 
                               target="_blank" rel="noopener noreferrer" 
                               class="btn btn--ghost btn--sm">
                                <i class="fas fa-link"></i> Source
                            </a>
                        <?php endif; ?>
                        <?php if ($project['live_url']): ?>
                            <a href="<?php echo sanitizeOutput($project['live_url']); ?>" 
                               target="_blank" rel="noopener noreferrer" 
                               class="btn btn--primary btn--sm">
                                <i class="fas fa-rocket"></i> Live Demo
                            </a>
                        <?php else: ?>
                            <span class="btn btn--ghost btn--sm" style="opacity:0.5;cursor:default">
                                <i class="fas fa-lock"></i> Private
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
