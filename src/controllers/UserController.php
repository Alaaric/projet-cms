<?php

namespace App\Controllers;

use App\Repositories\UserRepository;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getUserByEmail($email)
{
    $user = $this->userRepository->findByEmail($email);
    
    if (!$user) {
        echo "Utilisateur introuvable.";
        return;
    }

    echo "Utilisateur trouvé : " . $user->getUsername();
}

    public function deleteUser($userId)
    {
        // Supposons que l'ID de l'admin connecté soit stocké dans une variable de session
        $adminId = $_SESSION['admin_id'];
        $this->userRepository->deleteUser($userId, $adminId);
        $this->redirect('/');
    }
}