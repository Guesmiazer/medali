<?php
require_once 'config.php';

$about = getData('about');
$experiences = getData('experiences');
$certifications = getData('certifications');
$competences = getData('competences');
$theme = getData('theme');

// Sort experiences by date (assuming YYYY-MM format)
usort($experiences, function($a, $b) {
    return strcmp($b['start_date'], $a['start_date']);
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($about['name']); ?> | Ingénieur Expert en Conception</title>
    <meta name="description" content="<?php echo htmlspecialchars($about['bio']); ?>">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <header class="site-header">
        <div class="nav-container">
            <a href="#" class="nav-logo">
                <i data-lucide="cog" style="color: var(--accent);"></i>
                <span><?php echo strtoupper(explode(' ', $about['name'])[0]); ?>.SOLUTIONS</span>
            </a>
            <ul class="nav-links">
                <li><a href="#about">Expertise</a></li>
                <li><a href="#experience">Réalisations</a></li>
                <li><a href="#skills">Compétences</a></li>
                <li><a href="#certifications">Certifications</a></li>
            </ul>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <a href="#contact" class="btn-tech desktop-only">
                    Collaborer <i data-lucide="arrow-right"></i>
                </a>
                <button class="mobile-nav-toggle"><i data-lucide="menu"></i></button>
            </div>
        </div>
    </header>

    <div class="mobile-overlay"></div>

    <main>
        <section class="hero">
            <div class="hero-layout">
                <div class="hero-content">
                    <div class="hero-tag">
                        <i data-lucide="sparkles" size="16"></i> Ingénierie de Haute Précision
                    </div>
                    <h1>Concevoir l'avenir de la <span>Mécanique</span></h1>
                    <p class="description">
                        Expert en modélisation avancée et optimisation industrielle. J'accompagne les entreprises dans la transformation de concepts complexes en systèmes manufacturables haute performance.
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 1rem; width: 100%;">
                        <a href="#experience" class="btn-tech" style="width: 100%; justify-content: center;">
                            Voir mes projets <i data-lucide="layout"></i>
                        </a>
                        <?php if (!empty($about['cv_pdf'])): ?>
                        <a href="media/<?php echo $about['cv_pdf']; ?>" target="_blank" class="btn-tech btn-outline" style="width: 100%; justify-content: center;">
                            CV complet <i data-lucide="download"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hero-visual" style="position: relative;">
                    <div style="width: 100%; aspect-ratio: 1; background: var(--accent-light); border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; overflow: hidden; position: relative;">
                         <?php if (!empty($about['photo'])): ?>
                         <img src="public/uploads/<?php echo $about['photo']; ?>" style="width:100%; height:100%; object-fit: cover; filter: saturate(1.1); mix-blend-mode: luminosity; opacity: 0.8;" alt="">
                         <?php else: ?>
                         <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--accent);"><i data-lucide="user" size="64"></i></div>
                         <?php endif; ?>
                         <div style="position: absolute; inset:0; background: linear-gradient(to top, var(--accent-glow), transparent);"></div>
                    </div>
                    <div style="position: absolute; bottom: 10%; right: -5%; background: white; padding: 1rem; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border);" class="desktop-only">
                        <div class="mono" style="color: var(--accent); margin-bottom: 0.5rem; font-size: 0.6rem;">Expertise CAO</div>
                        <div style="font-size: 1rem; font-weight: 800; color: var(--primary);">SOLIDWORKS & CATIA</div>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="section-wrapper">
            <div class="about-split">
                <div class="profile-container">
                    <?php if (!empty($about['photo'])): ?>
                    <img src="public/uploads/<?php echo $about['photo']; ?>" alt="<?php echo $about['name']; ?>">
                    <?php endif; ?>
                </div>
                <div>
                    <span class="mono" style="color: var(--accent); margin-bottom: 1rem; display: block;">Profil Professionnel</span>
                    <h2 style="margin-bottom: 2rem;">L'Ingénierie au service de l'Innovation</h2>
                    <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2.5rem;">
                        <?php echo $about['bio']; ?>
                    </p>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="background: var(--accent-light); padding: 0.75rem; border-radius: 12px; color: var(--accent);">
                                <i data-lucide="cpu"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem;">Performance</h4>
                                <p style="font-size: 0.85rem; color: var(--text-muted);">Structures complexes.</p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="background: var(--accent-light); padding: 0.75rem; border-radius: 12px; color: var(--accent);">
                                <i data-lucide="layers"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem;">Gestion</h4>
                                <p style="font-size: 0.85rem; color: var(--text-muted);">PLM & Cycle de vie.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="experience" class="section-wrapper" style="background: var(--bg-alt); border-radius: var(--radius-lg);">
            <div class="section-header">
                <span class="mono" style="color: var(--accent);">Parcours & Réalisations</span>
                <h2>Expériences Significatives</h2>
            </div>
            
            <div class="tech-grid">
                <?php foreach ($experiences as $exp): ?>
                <div class="tech-card">
                    <div class="exp-date"><?php echo $exp['start_date']; ?> — <?php echo $exp['end_date'] ?: 'Présent'; ?></div>
                    <h3 style="margin-bottom: 0.5rem;"><?php echo $exp['title']; ?></h3>
                    <div class="mono" style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.75rem;">
                         <i data-lucide="building-2" size="14"></i> <?php echo $exp['company']; ?> | <i data-lucide="map-pin" size="14"></i> <?php echo $exp['location']; ?>
                    </div>
                    <p style="color: var(--text-muted); flex-grow: 1; font-size: 0.95rem; margin-bottom: 2rem;"><?php echo $exp['description']; ?></p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <?php foreach ($exp['skills'] as $skill): ?>
                        <span class="badge-tag"><?php echo $skill; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="skills" class="section-wrapper">
            <div class="section-header">
                <span class="mono" style="color: var(--accent);">Matrice Technique</span>
                <h2>Expertises & Maîtrise</h2>
            </div>
            
            <div class="skills-container">
                <?php foreach ($competences['categories'] as $cat): ?>
                <div>
                    <h3 style="margin-bottom: 2rem; font-size: 1.4rem; display: flex; align-items: center; gap: 1rem;">
                        <span style="width: 6px; height: 28px; background: var(--accent); border-radius: 3px;"></span>
                        <?php echo $cat['name']; ?>
                    </h3>
                    <?php foreach ($cat['skills'] as $skill): ?>
                    <div class="skill-bar-container">
                        <div class="skill-info">
                            <span><?php echo $skill['name']; ?></span>
                            <span class="mono" style="font-size: 0.7rem; color: var(--accent);"><?php echo $skill['level']; ?></span>
                        </div>
                        <div class="skill-rail">
                            <?php 
                                $pct = 45;
                                if ($skill['level'] == 'Expert') $pct = 100;
                                elseif (str_contains($skill['level'], 'Intermédiaire')) $pct = 75;
                            ?>
                            <div class="skill-fill" style="width: <?php echo $pct; ?>%;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if (!empty($certifications)): ?>
        <section id="certifications" class="section-wrapper">
            <div class="section-header">
                <span class="mono" style="color: var(--accent);">Gages de Confiance</span>
                <h2>Certifications Certifiées</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem;">
                <?php foreach ($certifications as $cert): ?>
                <div class="tech-card" style="padding: 2rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div style="width: 60px; height: 60px; background: var(--accent-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--accent); margin-bottom: 1.5rem;">
                        <i data-lucide="award" size="32"></i>
                    </div>
                    <span class="mono" style="color: var(--text-muted); font-size: 0.7rem; margin-bottom: 0.5rem;"><?php echo $cert['issuer']; ?></span>
                    <h4 style="font-size: 1.1rem; margin-bottom: 1.5rem;"><?php echo $cert['title']; ?></h4>
                    
                    <div style="margin-top: auto; display: flex; gap: 1rem;">
                        <?php if ($cert['link']): ?>
                        <a href="<?php echo $cert['link']; ?>" target="_blank" class="btn-tech btn-outline" style="padding: 0.5rem 1rem; font-size: 0.75rem;">Vérifier</a>
                        <?php endif; ?>
                        <?php if (!empty($cert['pdf_file'])): ?>
                        <a href="public/uploads/<?php echo $cert['pdf_file']; ?>" target="_blank" class="btn-tech" style="padding: 0.5rem 1rem; font-size: 0.75rem;">Document</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <section id="contact" class="footer-cta">
            <div style="max-width: 800px; margin: 0 auto;">
                <span class="mono" style="color: var(--accent); display: block; margin-bottom: 1.5rem;">Prêt pour votre prochain défi ?</span>
                <h2>Démarrons une Collaboration</h2>
                <p style="margin-bottom: 3rem;">Besoin d'un expert pour sécuriser vos phases de conception mécanique ou optimiser vos processus de fabrication ?</p>
                
                <div style="display: flex; justify-content: center; gap: 1.5rem; flex-wrap: wrap;">
                    <a href="mailto:<?php echo $about['email']; ?>" class="btn-tech" style="background: white; color: var(--primary); padding: 1.25rem 2rem; font-size: 1rem; width: 100%; max-width: 350px; justify-content: center;">
                        <i data-lucide="mail"></i> Me contacter par Email
                    </a>
                    <a href="<?php echo $about['linkedin']; ?>" target="_blank" class="btn-tech" style="border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.05); padding: 1.25rem 2rem; width: 100%; max-width: 350px; justify-content: center;">
                        <i data-lucide="linkedin"></i> Profil LinkedIn
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer style="padding: 4rem 1.5rem; background: var(--bg-alt); text-align: center;">
        <div class="nav-container" style="flex-direction: column; gap: 2rem;">
            <div class="nav-logo">
                 <i data-lucide="cog" style="color: var(--accent);"></i>
                 <span><?php echo strtoupper(explode(' ', $about['name'])[0]); ?>.SOLUTIONS</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; max-width: 500px; margin: 0 auto;">
                Ingénieur concepteur spécialisé en solutions industrielles innovantes. 
                Basé à Paris, disponible pour des projets à l'échelle internationale.
            </p>
            <div style="height: 1px; width: 100%; background: var(--border); margin: 2rem 0;"></div>
            <p style="font-size: 0.75rem; color: var(--text-muted);">
                &copy; <?php echo date('Y'); ?> <?php echo $about['name']; ?>. Tous droits réservés.
            </p>
        </div>
    </footer>

    <a href="admin/login.php" class="admin-link" title="Administration">
        <i data-lucide="lock"></i>
    </a>

    <script>
        lucide.createIcons();
        
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.site-header');
            if (window.scrollY > 50) {
                header.style.padding = '0.5rem 0';
                header.style.boxShadow = 'var(--shadow)';
            } else {
                header.style.padding = '1.25rem 0';
                header.style.boxShadow = 'none';
            }
        });

        // Mobile Menu Logic
        const toggle = document.querySelector('.mobile-nav-toggle');
        const navLinks = document.querySelector('.nav-links');
        const overlay = document.querySelector('.mobile-overlay');

        function toggleMenu() {
            navLinks.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = navLinks.classList.contains('active') ? 'hidden' : 'auto';
        }

        if(toggle) {
            toggle.addEventListener('click', toggleMenu);
        }

        if(overlay) {
            overlay.addEventListener('click', toggleMenu);
        }

        // Close menu on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        });
    </script>
</body>
</html>


