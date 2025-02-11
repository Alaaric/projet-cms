<?php

class UserModel
{
  public function getData()
  {
    // In a real application, data would come from a database
    return [
      'title' => 'Hello !',
      'content' => 'This is a simple MVC in PHP'
    ];
  }
}
