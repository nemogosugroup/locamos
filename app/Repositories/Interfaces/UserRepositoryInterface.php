<?php

namespace App\Repositories\Interfaces;

//use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface
{
    public function getAllUsers(array $params);
    public function getUserById($id);
    public function deleteUser($id);
    public function createUser(array $userDetails);
    public function updateUsers(array $newDetails);
    public function updateRoles($id, array $roles );
    public function exportUsers();
    public function listEmployee();
    public function store(array $params);
}

