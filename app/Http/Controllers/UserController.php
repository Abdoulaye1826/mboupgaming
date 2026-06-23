<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('modules.placeholder', ['title' => 'Gestion des utilisateurs', 'icon' => 'bi-person-gear']);
    }
}
