<?php
require_once '../config.php';
require_once '../includes/upload_helper.php';
requireLogin();

$certifications = getData('certifications');
$success = '';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    unset($certifications[$id]);
    saveData('certifications', array_values($certifications));
    header('Location: manage_certifications.php?status=deleted');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdf_file = $_POST['current_pdf'] ?? '';

    if (!empty($_FILES['pdf_cert']['name'])) {
        $up = uploadFile($_FILES['pdf_cert'], UPLOAD_DIR, ['pdf', 'jpg', 'png', 'jpeg']);
        if ($up['success']) {
            $pdf_file = $up['fileName'];
        }
    }

    $new_cert = [
        'title' => $_POST['title'],
        'issuer' => $_POST['issuer'],
        'date' => $_POST['date'],
        'expiry' => $_POST['expiry'] ?: null,
        'link' => $_POST['link'] ?: null,
        'badge' => $_POST['badge'] ?? '',
        'pdf_file' => $pdf_file
    ];

    if (isset($_POST['id']) && $_POST['id'] !== '') {
        $certifications[$_POST['id']] = $new_cert;
    } else {
        $certifications[] = $new_cert;
    }

    saveData('certifications', $certifications);
    $success = 'Certification enregistrée !';
}

$edit_id = $_GET['edit'] ?? null;
$edit_data = $edit_id !== null ? $certifications[$edit_id] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Certifications — Admin</title>
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
            padding: 2.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
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
            margin-bottom: 0.5rem;
        }
        input, textarea, select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #f8fafc;
        }
        .cert-item {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border);
            transition: all 0.2s;
        }
        .cert-item:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
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
            .form-grid {
                grid-template-columns: 1fr;
            }
            div[style*="grid-template-columns: 1fr 1fr"] {
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
            <li style="margin-bottom: 0.5rem;"><a href="index.php?tab=certifications" style="display: block; padding: 0.875rem 1rem; color: white; background: var(--accent); text-decoration: none; border-radius: 10px; font-weight: 500;">← Retour Dashboard</a></li>
        </ul>
    </aside>

    <div class="main-wrapper">
        <button class="admin-toggle" id="adminToggle"><i data-lucide="menu"></i></button>
        <div style="max-width: 900px; margin: 0 auto;">
            <h1 style="margin-bottom: 2rem; color: var(--primary);">Gestion des Certifications</h1>

            <?php if ($success): ?>
                <div style="background: #dcfce7; color: #166534; padding: 1rem 1.5rem; border-radius: 10px; margin-bottom: 2rem; border: 1px solid #bbf7d0;">✓ <?php echo $success; ?></div>
            <?php endif; ?>

            <div class="admin-card">
                <h2 style="margin-bottom: 2rem; font-size: 1.25rem;"><?php echo $edit_data ? '📝 Modifier' : '➕ Ajouter'; ?> une Certification</h2>
                <form method="POST" enctype="multipart/form-data">
                    <?php if ($edit_id !== null): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-grid">
                        <div>
                            <label>Titre de la Certification</label>
                            <input type="text" name="title" value="<?php echo $edit_data['title'] ?? ''; ?>" placeholder="ex: Certified SolidWorks Professional (CSWP)" required>
                        </div>
                        <div>
                            <label>Organisme Émetteur</label>
                            <input type="text" name="issuer" value="<?php echo $edit_data['issuer'] ?? ''; ?>" placeholder="ex: Dassault Systèmes" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label>Date d'Obtention</label>
                            <input type="date" name="date" value="<?php echo $edit_data['date'] ?? ''; ?>" required>
                        </div>
                        <div>
                            <label>Lien de Vérification (URL)</label>
                            <input type="url" name="link" value="<?php echo $edit_data['link'] ?? ''; ?>" placeholder="https://...">
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label>Fichier du Certificat (PDF ou Image)</label>
                        <input type="file" name="pdf_cert" style="background: white; border-style: dashed;">
                        <input type="hidden" name="current_pdf" value="<?php echo $edit_data['pdf_file'] ?? ''; ?>">
                        <?php if(!empty($edit_data['pdf_file'])): ?>
                            <div style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
                                📎 Fichier actuel : <a href="../public/uploads/<?php echo $edit_data['pdf_file']; ?>" target="_blank"><?php echo $edit_data['pdf_file']; ?></a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn btn-primary" style="flex-grow: 1; justify-content: center; padding: 1rem;">
                            <?php echo $edit_data ? 'Mettre à jour la certification' : 'Ajouter la certification'; ?>
                        </button>
                        <?php if ($edit_id !== null): ?>
                            <a href="manage_certifications.php" class="btn btn-secondary" style="padding: 1rem 2rem;">Annuler</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <h2 style="margin: 3rem 0 1.5rem; font-size: 1.25rem; color: var(--primary);">Liste des Diplômes & Certifications</h2>
            <div class="cert-list">
                <?php foreach ($certifications as $id => $cert): ?>
                    <div class="cert-item">
                        <div>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 1.1rem;"><?php echo $cert['title']; ?></div>
                            <div style="color: var(--accent); font-weight: 500;"><?php echo $cert['issuer']; ?></div>
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                                📅 Obtenu le : <?php echo $cert['date']; ?>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="?edit=<?php echo $id; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Modifier</a>
                            <a href="?delete=<?php echo $id; ?>" class="btn" style="padding: 0.5rem 1rem; font-size: 0.85rem; background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;" onclick="return confirm('Confirmer la suppression de cette certification ?')">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($certifications)): ?>
                    <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; border: 1px dashed var(--border); color: var(--text-muted);">
                        Aucune certification enregistrée.
                    </div>
                <?php endif; ?>
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
