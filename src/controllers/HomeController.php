<?php

class HomeController
{
  public function index()
  {
    // Load the model
    require_once __DIR__ . '/../models/UserModel.php';

    // Instantiate the model
    $userModel = new UserModel();

    // Get data from the model
    $data = $userModel->getData();

    // Load the view
    require_once __DIR__ . '/../views/home.php';
  }

  public function debug()
  {
    // Load the view
    require_once __DIR__ . '/../views/debug.php';
  }
}
