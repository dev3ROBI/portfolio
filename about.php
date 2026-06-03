<?php
/**
 * About Page
 * Personal introduction, experience timeline, education, and personal journey
 */
require_once 'config/database.php';
$pageTitle = 'About';
$pageDescription = 'Learn about Robiul Islam (RobiCodes) - Web & API Developer with expertise in PHP, MySQL, JavaScript, and Android development.';
include 'includes/header.php';
?>

<section class="section hero" style="min-height:auto;padding-top:8rem" aria-label="About me">
    <div class="container">
        <div class="section__header reveal">
            <h2 class="section__title">About Me</h2>
            <p class="section__subtitle">Developer, creator, and lifelong learner passionate about building impactful solutions</p>
        </div>

        <div class="about__grid">
            <div class="about__image-wrapper reveal">
                <div class="about__image">
                    &#128100;
                </div>
                <div class="about__image-stats">
                    <div class="about__stat glass-card">
                        <div class="about__stat-value">3+</div>
                        <div class="about__stat-label">Years Coding</div>
                    </div>
                    <div class="about__stat glass-card">
                        <div class="about__stat-value">25+</div>
                        <div class="about__stat-label">Projects</div>
                    </div>
                    <div class="about__stat glass-card">
                        <div class="about__stat-value">10+</div>
                        <div class="about__stat-label">Technologies</div>
                    </div>
                </div>
            </div>

            <div class="about__text reveal reveal-delay-1">
                <h3>Hi, I'm Robiul Islam</h3>
                
                <p>
                    Welcome to my corner of the web! I'm a passionate Web & API Developer from Bangladesh, 
                    known online as <strong>RobiCodes</strong>. My journey into software development began with 
                    curiosity about how websites work, and it has since evolved into a full-fledged career 
                    building robust digital solutions.
                </p>

                <p>
                    I specialize in PHP and MySQL backend development, creating RESTful APIs, 
                    and building responsive, user-friendly web interfaces. I also have experience 
                    with Android app development and automation tools. My projects range from 
                    social networking platforms and educational apps to comprehensive API services.
                </p>

                <p>
                    I believe in writing clean, maintainable code and following best practices 
                    like MVC architecture, prepared statements, and proper security measures. 
                    Every project is an opportunity to learn something new and push the boundaries 
                    of what I can create.
                </p>

                <p>
                    When I'm not coding, I enjoy exploring new technologies, contributing to 
                    open source, and sharing knowledge with the developer community. 
                    I'm always open to collaborating on interesting projects.
                </p>

                <a href="contact.php" class="btn btn--primary" style="margin-top:var(--spacing-md)">
                    Let's Work Together &#8594;
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Experience Timeline -->
<section class="section" aria-label="Experience timeline">
    <div class="container container--narrow">
        <div class="section__header reveal">
            <h2 class="section__title">Experience</h2>
            <p class="section__subtitle">My professional journey in software development</p>
        </div>

        <div class="timeline">
            <div class="timeline__item reveal">
                <div class="timeline__date">2024 - Present</div>
                <h3 class="timeline__title">Freelance Web & API Developer</h3>
                <p class="timeline__subtitle">Self-employed</p>
                <p class="timeline__description">
                    Developing custom web applications, RESTful APIs, and automation tools for clients. 
                    Built projects including DreamBD social platform, Quran API, Hadith API, and AI music channel tools.
                    Working with PHP, MySQL, JavaScript, and modern development practices.
                </p>
            </div>

            <div class="timeline__item reveal reveal-delay-1">
                <div class="timeline__date">2023 - 2024</div>
                <h3 class="timeline__title">Junior Web Developer</h3>
                <p class="timeline__subtitle">Various Projects & Open Source</p>
                <p class="timeline__description">
                    Contributed to open-source projects and built several full-stack applications. 
                    Gained expertise in PHP backend development, MySQL database design, 
                    and frontend technologies including JavaScript, HTML5, and CSS3.
                </p>
            </div>

            <div class="timeline__item reveal reveal-delay-2">
                <div class="timeline__date">2022 - 2023</div>
                <h3 class="timeline__title">Started Coding Journey</h3>
                <p class="timeline__subtitle">Self-taught Developer</p>
                <p class="timeline__description">
                    Began learning web development with HTML, CSS, and JavaScript. 
                    Quickly progressed to PHP and MySQL, building foundational projects. 
                    Started exploring Android development with Java and REST API architecture.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Education -->
<section class="section" aria-label="Education">
    <div class="container container--narrow">
        <div class="section__header reveal">
            <h2 class="section__title">Education</h2>
            <p class="section__subtitle">Academic background and continuous learning</p>
        </div>

        <div class="timeline">
            <div class="timeline__item reveal">
                <div class="timeline__date">2023 - Present</div>
                <h3 class="timeline__title">Computer Science & Engineering</h3>
                <p class="timeline__subtitle">University</p>
                <p class="timeline__description">
                    Pursuing a degree in Computer Science & Engineering with a focus on software development, 
                    algorithms, data structures, and database management systems.
                </p>
            </div>

            <div class="timeline__item reveal reveal-delay-1">
                <div class="timeline__date">2020 - 2022</div>
                <h3 class="timeline__title">Higher Secondary School</h3>
                <p class="timeline__subtitle">Science Group</p>
                <p class="timeline__description">
                    Completed higher secondary education with a focus on science, mathematics, 
                    and computer fundamentals that laid the foundation for my technical career.
                </p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
