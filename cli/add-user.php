<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Repositories\UserRepository;
use App\Entities\User;


function prompt(string $message, bool $hidden = false): string {
    echo $message . ": ";

    if ($hidden) { 
        system('stty -echo');
        $input = trim(fgets(STDIN));
        system('stty echo');
        echo "\n";
    } else {
        $input = trim(fgets(STDIN));
    }

    return $input;
}

function selectOption(string $message, array $options): string {
    echo $message . "\n";
    
    foreach ($options as $key => $option) {
        echo "[$key] $option\n";
    }

    do {
        $choice = prompt("👉 Entrez le numéro correspondant");
        if (!array_key_exists($choice, $options)) {
            echo "❌ Sélection invalide, veuillez choisir un numéro valide.\n";
        }
    } while (!array_key_exists($choice, $options));

    return $options[$choice];
}

$userRepo = new UserRepository();

echo "\n📝 Création d'un nouvel utilisateur\n";
echo "-----------------------------------\n";

do {
    $email = prompt("📧 Email");
    $existingEmail = $userRepo->findByEmail($email);
    if ($existingEmail) {
        echo "❌ L'email '$email' est déjà utilisé. Veuillez en choisir un autre.\n";
    }
} while ($existingEmail);

$username = prompt("👤 Nom d'utilisateur");

do {
    $password = prompt("🔑 Mot de passe", true);
    $confirmPassword = prompt("🔑 Confirmez le mot de passe", true);

    if ($password !== $confirmPassword) {
        echo "❌ Les mots de passe ne correspondent pas. Veuillez réessayer.\n";
    }
} while ($password !== $confirmPassword);

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$role = selectOption("🎭 Sélectionnez un rôle :", [
    1 => "user",
    2 => "admin"
]);

$user = new User(
    email: $email,
    username: $username,
    password: $hashedPassword,
    role: $role,
);

$userRepo->save($user);

echo "✅ Utilisateur '$username' ($role) créé avec succès ! 🎉\n";
