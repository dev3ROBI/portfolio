<?php
/**
 * Skills Page
 * Animated skill bars and circular progress indicators organized by category
 */
require_once 'config/database.php';
$pageTitle = 'Skills';
$pageDescription = 'Technical skills and expertise of Robiul Islam (RobiCodes) including frontend, backend, database, DevOps, and tools.';
include 'includes/header.php';

$categoryLabels = [
    'frontend' => ['Frontend Development', '<i class="fas fa-palette"></i>'],
    'backend' => ['Backend Development', '<i class="fas fa-cog"></i>'],
    'database' => ['Database Management', '<i class="fas fa-database"></i>'],
    'devops' => ['DevOps & Version Control', '<i class="fas fa-wrench"></i>'],
    'tools' => ['Tools & Platforms', '<i class="fas fa-tools"></i>'],
];

$skills = dbGetAll("SELECT * FROM skills ORDER BY display_order ASC");
$grouped = [];
foreach ($skills as $s) {
    $grouped[$s['category']][] = $s;
}
?>

<svg style="position:absolute;width:0;height:0" aria-hidden="true">
    <defs>
        <linearGradient id="skill-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="#6c5ce7" />
            <stop offset="100%" stop-color="#00cec9" />
        </linearGradient>
    </defs>
</svg>

<section class="section hero" style="min-height:auto;padding-top:8rem" aria-label="Skills">
    <div class="container">
        <div class="section__header reveal">
            <h2 class="section__title">Skills & Expertise</h2>
            <p class="section__subtitle">Technologies and tools I work with on a daily basis</p>
        </div>

        <!-- Skill Bars by Category -->
        <div class="skills__grid">
            <?php foreach ($grouped as $category => $categorySkills): 
                $label = $categoryLabels[$category] ?? [$category, '<i class="fas fa-file-alt"></i>'];
            ?>
            <div class="skills__category glass-card reveal">
                <h3 class="skills__category-title">
                    <span class="skills__category-icon"><?php echo $label[1]; ?></span>
                    <?php echo sanitizeOutput($label[0]); ?>
                </h3>

                <?php foreach ($categorySkills as $skill): ?>
                <div class="skill-item">
                    <div class="skill-item__header">
                        <span class="skill-item__name"><?php echo sanitizeOutput($skill['name']); ?></span>
                        <span class="skill-item__percent"><?php echo (int)$skill['proficiency']; ?>%</span>
                    </div>
                    <div class="skill-item__bar">
                        <div class="skill-item__fill" data-percent="<?php echo (int)$skill['proficiency']; ?>" 
                             style="width:0%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Circular Progress Skills -->
        <div class="section__header reveal" style="margin-top:var(--spacing-3xl)">
            <h2 class="section__title">Core Proficiencies</h2>
            <p class="section__subtitle">My strongest technical areas</p>
        </div>

        <div class="skills__circular">
            <?php
            $coreSkills = [
                ['PHP', 90],
                ['JavaScript', 88],
                ['MySQL', 88],
                ['HTML/CSS', 92],
                ['REST API', 85],
                ['Git', 85],
            ];
            foreach ($coreSkills as $core):
            ?>
            <div class="circular-skill reveal">
                <div class="circular-skill__svg">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle class="circular-skill__bg" cx="50" cy="50" r="42"></circle>
                        <circle class="circular-skill__progress" 
                                data-percent="<?php echo $core[1]; ?>"
                                cx="50" cy="50" r="42"></circle>
                    </svg>
                    <span class="circular-skill__value"><?php echo $core[1]; ?>%</span>
                </div>
                <p class="circular-skill__label"><?php echo $core[0]; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
