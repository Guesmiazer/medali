<?php
require_once '../config.php';
requireLogin();

$competences = getData('competences');
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_category') {
        $competences['categories'][] = [
            'name' => $_POST['name'],
            'skills' => []
        ];
        saveData('competences', $competences);
        header('Location: manage_competences.php?success=1');
        exit();
    } elseif ($action === 'add_skill') {
        $cat_id = $_POST['category_id'];
        if (isset($competences['categories'][$cat_id])) {
            $competences['categories'][$cat_id]['skills'][] = [
                'name' => $_POST['name'],
                'level' => $_POST['level']
            ];
            saveData('competences', $competences);
            header('Location: manage_competences.php?success=1');
            exit();
        }
    }
}

if (isset($_GET['delete_cat'])) {
    $cat_id = $_GET['delete_cat'];
    if (isset($competences['categories'][$cat_id])) {
        unset($competences['categories'][$cat_id]);
        $competences['categories'] = array_values($competences['categories']);
        saveData('competences', $competences);
        header('Location: manage_competences.php?success=1');
        exit();
    }
}

if (isset($_GET['delete_skill']) && isset($_GET['cat'])) {
    $cat_id = $_GET['cat'];
    $skill_id = $_GET['delete_skill'];
    if (isset($competences['categories'][$cat_id]['skills'][$skill_id])) {
        unset($competences['categories'][$cat_id]['skills'][$skill_id]);
        $competences['categories'][$cat_id]['skills'] = array_values($competences['categories'][$cat_id]['skills']);
        saveData('competences', $competences);
        header('Location: manage_competences.php?success=1');
        exit();
    }
}

if (isset($_GET['success'])) {
    $success = 'Modification enregistrée avec succès !';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Compétences — Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --sidebar-width: 280px;
            --bg-muted: #f1f5f9;
        }
        body {
            background-color: var(--bg-muted);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            color: white;
            padding: 2rem 1.5rem;
            position: fixed;
            height: 100vh;
        }
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 3rem;
        }
        .admin-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .category-box {
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 2rem;
            overflow: hidden;
            background: white;
        }
        .category-header {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .skill-list {
            padding: 1rem 1.5rem;
        }
        .skill-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .skill-item:last-child { border-bottom: none; }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        input, select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #f8fafc;
            margin-bottom: 1rem;
        }
        .badge-level {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .level-expert { background: #dcfce7; color: #166534; }
        .level-intermediate { background: #e0f2fe; color: #0369a1; }
        .level-beginner { background: #f1f5f9; color: #475569; }
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 240px;
                z-index: 1000;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
                padding: 1.5rem;
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
                margin-bottom: 1.5rem;
            }
            div[style*="grid-template-columns: 1fr 1.5fr"] {
                grid-template-columns: 1fr !important;
            }
        }
        .admin-toggle { display: none; }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <aside class="sidebar">
        <a href="index.php" style="color: white; text-decoration: none; font-weight: 800; font-size: 1.25rem; display: block; margin-bottom: 3rem;">PORTFOLIO_ADM</a>
        <ul style="list-style: none;">
            <li style="margin-bottom: 0.5rem;"><a href="index.php?tab=competences" style="display: block; padding: 0.875rem 1rem; color: white; background: var(--accent); text-decoration: none; border-radius: 10px; font-weight: 500;">← Retour Dashboard</a></li>
        </ul>
    </aside>

    <div class="main-wrapper">
        <button class="admin-toggle" id="adminToggle"><i data-lucide="menu"></i></button>
        <div style="max-width: 900px; margin: 0 auto;">
            <h1 style="margin-bottom: 2rem; color: var(--primary);">Matrice de Compétences</h1>

            <?php if ($success): ?>
                <div style="background: #dcfce7; color: #166534; padding: 1rem 1.5rem; border-radius: 10px; margin-bottom: 2rem; border: 1px solid #bbf7d0;">✓ <?php echo $success; ?></div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem; align-items: start;">
                <!-- Forms Column -->
                <div>
                    <div class="admin-card">
                        <h3 style="margin-bottom: 1.5rem; font-size: 1.1rem;">📂 Nouvelle Catégorie</h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="add_category">
                            <label>Nom de la catégorie</label>
                            <input type="text" name="name" placeholder="ex: Logiciels CAO" required>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Créer la catégorie</button>
                        </form>
                    </div>

                    <div class="admin-card">
                        <h3 style="margin-bottom: 1.5rem; font-size: 1.1rem;">🛠️ Ajouter un Savoir-faire</h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="add_skill">
                            <label>Catégorie cible</label>
                            <select name="category_id" required>
                                <?php foreach ($competences['categories'] as $id => $cat): ?>
                                    <option value="<?php echo $id; ?>"><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            
                            <label>Compétence</label>
                            <input type="text" name="name" placeholder="ex: SolidWorks" required>
                            
                            <label>Niveau de maîtrise</label>
                            <select name="level">
                                <option value="Expert">Expert / Senior</option>
                                <option value="Intermédiaire">Intermédiaire / Autonome</option>
                                <option value="Débutant">Débutant / Notions</option>
                            </select>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Ajouter à la matrice</button>
                        </form>
                    </div>
                </div>

                <!-- List Column -->
                <div>
                    <?php if (empty($competences['categories'])): ?>
                        <div style="text-align: center; padding: 4rem; background: white; border-radius: 16px; border: 1px dashed var(--border); color: var(--text-muted);">
                            Commencez par créer une catégorie pour organiser vos compétences.
                        </div>
                    <?php endif; ?>

                    <?php foreach ($competences['categories'] as $cat_id => $cat): ?>
                        <div class="category-box">
                            <div class="category-header">
                                <span style="font-weight: 700; color: var(--primary);"><?php echo $cat['name']; ?></span>
                                <a href="?delete_cat=<?php echo $cat_id; ?>" style="color: #ef4444; font-size: 0.8rem; text-decoration: none;" onclick="return confirm('Supprimer la catégorie et toutes ses compétences ?')">Supprimer</a>
                            </div>
                            <div class="skill-list">
                                <?php if (empty($cat['skills'])): ?>
                                    <p style="font-size: 0.85rem; color: var(--text-muted); font-style: italic;">Aucune compétence enregistrée.</p>
                                <?php else: ?>
                                    <?php foreach ($cat['skills'] as $skill_id => $skill): ?>
                                        <div class="skill-item">
                                            <div>
                                                <span style="font-weight: 500;"><?php echo $skill['name']; ?></span>
                                                <span class="badge-level <?php echo 'badge-' . (strtolower($skill['level']) == 'expert' ? 'expert' : (str_contains(strtolower($skill['level']), 'intermédiaire') ? 'intermediate' : 'beginner')); ?> level-<?php echo (strtolower($skill['level']) == 'expert' ? 'expert' : (str_contains(strtolower($skill['level']), 'intermédiaire') ? 'intermediate' : 'beginner')); ?>">
                                                    <?php echo $skill['level']; ?>
                                                </span>
                                            </div>
                                            <a href="?delete_skill=<?php echo $skill_id; ?>&cat=<?php echo $cat_id; ?>" style="color: #fca3a3; text-decoration: none; font-size: 1.25rem;" title="Supprimer" onclick="return confirm('Supprimer cette compétence ?')">&times;</a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
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

        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
