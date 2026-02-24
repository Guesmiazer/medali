<?php
require_once '../config.php';
require_once '../includes/upload_helper.php';
requireLogin();

$active_tab = $_GET['tab'] ?? 'about';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($active_tab === 'about') {
        $photo = $_POST['current_photo'] ?? '';
        $cv_pdf = $_POST['current_cv'] ?? '';

        if (!empty($_FILES['photo']['name'])) {
            $up = uploadFile($_FILES['photo'], UPLOAD_DIR);
            if ($up['success']) $photo = $up['fileName'];
        }

        if (!empty($_FILES['cv_pdf']['name'])) {
            $up = uploadFile($_FILES['cv_pdf'], MEDIA_DIR);
            if ($up['success']) $cv_pdf = $up['fileName'];
        }

        $about = [
            'name' => $_POST['name'] ?? '',
            'title' => $_POST['title'] ?? '',
            'bio' => $_POST['bio'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'linkedin' => $_POST['linkedin'] ?? '',
            'photo' => $photo,
            'cv_pdf' => $cv_pdf
        ];
        saveData('about', $about);
        $success = 'Informations mises à jour !';
    } elseif ($active_tab === 'settings') {
        $theme = [
            'primary_color' => $_POST['primary_color'] ?? '#2563eb',
            'secondary_color' => $_POST['secondary_color'] ?? '#64748b',
            'background_color' => $_POST['background_color'] ?? '#0f172a',
            'text_color' => $_POST['text_color'] ?? '#f8fafc',
            'font_family' => $_POST['font_family'] ?? "'Outfit', sans-serif"
        ];
        saveData('theme', $theme);
        $success = 'Thème mis à jour !';
    }
}

$about = getData('about');
$theme = getData('theme');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Administration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --sidebar-width: 280px;
            --topbar-height: 70px;
            --bg-muted: #f1f5f9;
        }
        body {
            background-color: var(--bg-muted);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            color: white;
            padding: 2rem 1.5rem;
            position: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }
        .sidebar-logo {
            font-size: 1.25rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            margin-bottom: 3rem;
            display: block;
            letter-spacing: -0.5px;
        }
        .sidebar-nav {
            list-style: none;
            flex-grow: 1;
        }
        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .sidebar-nav a.active {
            background: var(--accent);
        }
        /* Main Content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 3rem;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        .content-area {
            padding: 3rem;
            max-width: 1000px;
        }
        .page-header {
            margin-bottom: 2.5rem;
        }
        .page-header h1 {
            font-size: 1.75rem;
            color: var(--primary);
        }
        .admin-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        /* Form improvements */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }
        input, textarea, select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #f8fafc;
            color: var(--text-main);
            font-family: inherit;
            transition: all 0.2s;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-light);
            background: white;
        }
        .help-text {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            font-weight: 500;
            border: 1px solid #bbf7d0;
        }
        .logout-btn {
            margin-top: auto;
            color: #fca5a5;
            text-decoration: none;
            padding: 1rem;
            font-weight: 500;
            border-radius: 10px;
            transition: background 0.2s;
        }
        /* Responsive Admin Layout */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 240px;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
            .topbar {
                padding: 0 1.5rem;
            }
            .content-area {
                padding: 1.5rem;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .admin-toggle {
                display: flex !important;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background: var(--primary);
                color: white;
                border: none;
                border-radius: 4px;
                margin-right: 1rem;
            }
        }
        .admin-toggle { display: none; }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <aside class="sidebar">
        <a href="../index.php" class="sidebar-logo">PORTFOLIO_ADM <sub style="font-size: 0.6rem; opacity: 0.5;">v2.0</sub></a>
        <ul class="sidebar-nav">
            <li><a href="?tab=about" class="<?php echo $active_tab === 'about' ? 'active' : ''; ?>">👤 CONFIG_AUTO</a></li>
            <li><a href="?tab=experiences" class="<?php echo $active_tab === 'experiences' ? 'active' : ''; ?>">💼 PARCOURS_PRO</a></li>
            <li><a href="?tab=competences" class="<?php echo $active_tab === 'competences' ? 'active' : ''; ?>">📊 MATRICE_TECH</a></li>
            <li><a href="?tab=certifications" class="<?php echo $active_tab === 'certifications' ? 'active' : ''; ?>">📜 CERTIFS_VAL</a></li>
            <li><a href="?tab=settings" class="<?php echo $active_tab === 'settings' ? 'active' : ''; ?>">⚙️ SYS_SETTINGS</a></li>
        </ul>
        <a href="logout.php" class="logout-btn">DISCONNECT_SESSION</a>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div style="display: flex; align-items: center;">
                <button class="admin-toggle" id="adminToggle"><i data-lucide="menu"></i></button>
                <span style="font-weight: 600; color: var(--text-muted);">// AUTH_STATUS: <span style="color: var(--accent);">ROOT_ADMIN</span></span>
            </div>
            <a href="../index.php" target="_blank" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.8rem; border-radius: 4px;">VOIR_LIAISON_EXTERN →</a>
        </header>

        <main class="content-area">
            <?php if ($success): ?>
                <div class="alert-success">✓ SYNCHRONISATION_RÉUSSIE: <?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($active_tab === 'about'): ?>
                <div class="page-header">
                    <h1>IDENTITÉ_PROFESSIONNELLE</h1>
                    <p style="color: var(--text-muted);">Configuration des métadonnées profil et bio-mécanique.</p>
                </div>
                
                <div class="admin-card">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-grid">
                            <div>
                                <label>NOM_IDENTIFIANT</label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($about['name']); ?>" required>
                            </div>
                            <div>
                                <label>TITRE_EXPLOITATION</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($about['title']); ?>" required>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label>DESCRIPTION_MODÈLE (BIO)</label>
                            <textarea name="bio" style="min-height: 150px;"><?php echo htmlspecialchars($about['bio']); ?></textarea>
                        </div>
                        
                        <div class="form-grid">
                            <div>
                                <label>CANAL_COMM_MAIL</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($about['email']); ?>" required>
                            </div>
                            <div>
                                <label>LIGNE_DIRECTE (OPT)</label>
                                <input type="text" name="phone" value="<?php echo htmlspecialchars($about['phone']); ?>">
                            </div>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <label>COORDONNÉES_LINKEDIN</label>
                            <input type="url" name="linkedin" value="<?php echo htmlspecialchars($about['linkedin']); ?>">
                        </div>
                        
                        <div class="form-grid" style="padding-top: 1.5rem; border-top: 1px solid var(--border);">
                            <div>
                                <label>RESSOURCE_IMAGE (AUTO)</label>
                                <input type="file" name="photo">
                                <input type="hidden" name="current_photo" value="<?php echo $about['photo']; ?>">
                                <?php if($about['photo']): ?>
                                    <div class="help-text">REF_LINK: <a href="../public/uploads/<?php echo $about['photo']; ?>" target="_blank"><?php echo $about['photo']; ?></a></div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label>SPEC_SHEET_RESUME (PDF)</label>
                                <input type="file" name="cv_pdf">
                                <input type="hidden" name="current_cv" value="<?php echo $about['cv_pdf']; ?>">
                                <?php if($about['cv_pdf']): ?>
                                    <div class="help-text">REF_LINK: <a href="../media/<?php echo $about['cv_pdf']; ?>" target="_blank"><?php echo $about['cv_pdf']; ?></a></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div style="margin-top: 3rem;">
                            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem; border-radius: 4px;">
                                COMMIT_CHANGES
                            </button>
                        </div>
                    </form>
                </div>
            <?php elseif ($active_tab === 'experiences'): ?>
                <div class="page-header">
                    <h1>LOGS_EXPÉRIENCES</h1>
                    <p style="color: var(--text-muted);">Registre chronologique des déploiements professionnels.</p>
                </div>
                <div class="admin-card" style="text-align: center; padding: 4rem;">
                    <div style="font-size: 3rem; margin-bottom: 1.5rem;">🏗️</div>
                    <a href="manage_experiences.php" class="btn btn-primary" style="border-radius: 4px;">ACCÉDER_AU_REGISTRE</a>
                </div>
            <?php elseif ($active_tab === 'competences'): ?>
                <div class="page-header">
                    <h1>ANALYSE_SÉMENTIQUE_TECHNIQUE</h1>
                    <p style="color: var(--text-muted);">Matrice de maîtrise des outils et processus.</p>
                </div>
                <div class="admin-card" style="text-align: center; padding: 4rem;">
                    <div style="font-size: 3rem; margin-bottom: 1.5rem;">⚙️</div>
                    <a href="manage_competences.php" class="btn btn-primary" style="border-radius: 4px;">MODIFIER_MATRICE</a>
                </div>
            <?php elseif ($active_tab === 'certifications'): ?>
                <div class="page-header">
                    <h1>PROTOCOLES_VALIDATION</h1>
                    <p style="color: var(--text-muted);">Vérification des accréditations industrielles.</p>
                </div>
                <div class="admin-card" style="text-align: center; padding: 4rem;">
                    <div style="font-size: 3rem; margin-bottom: 1.5rem;">💎</div>
                    <a href="manage_certifications.php" class="btn btn-primary" style="border-radius: 4px;">GÉRER_PROTOCOLES</a>
                </div>
            <?php elseif ($active_tab === 'settings'): ?>
                <div class="page-header">
                    <h1>PARAMÈTRES_SYSTÈME</h1>
                    <p style="color: var(--text-muted);">Ajustements des constantes visuelles globales.</p>
                </div>
                <div class="admin-card">
                    <form method="POST">
                        <div class="form-grid" style="grid-template-columns: 1fr;">
                            <div>
                                <label>VARIABLE_ACCENT_COLOR</label>
                                <input type="color" name="primary_color" value="<?php echo htmlspecialchars($theme['primary_color'] ?? '#2563eb'); ?>" style="height: 60px; padding: 4px; border-radius: 4px; cursor: pointer;">
                            </div>
                        </div>
                        <div style="margin-top: 2rem;">
                            <button type="submit" class="btn btn-primary" style="border-radius: 4px;">PUSH_CONFIG</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        lucide.createIcons();
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.getElementById('adminToggle');
        
        if(toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
