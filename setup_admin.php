<?php
require_once 'config.php';

$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$users = [
    [
        'username' => $username,
        'password' => $hash
    ]
];

saveData('users', $users);
echo "Utilisateur administrateur configuré : admin / admin123\n";
echo "Veuillez supprimer ce fichier ou changer le mot de passe après la première connexion.";
?>
