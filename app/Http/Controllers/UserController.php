<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index()
    {
        // 1. Data Dummy Statistik
        $stats = [
            ['label' => 'Total all user', 'value' => '2,500'],
            ['label' => 'Total active user', 'value' => '1,500'],
            ['label' => 'Total deactived user', 'value' => '1,000'],
        ];

        // 2. Data Dummy Users
        $users = [
            [
                'id' => 1,
                'name' => 'Bradley William',
                'email' => 'bradleywill@proton.me',
                'date_added' => '12/12/2025',
                'department' => 'ME',
                'status' => 'Active',
            ],
            [
                'id' => 2,
                'name' => 'Bradley William',
                'email' => 'bradleywill@proton.me',
                'date_added' => '12/12/2025',
                'department' => 'QA',
                'status' => 'Active',
            ],
            [
                'id' => 3,
                'name' => 'Bradley William',
                'email' => 'bradleywill@proton.me',
                'date_added' => '12/12/2025',
                'department' => 'ME',
                'status' => 'Active',
            ],
            [
                'id' => 4,
                'name' => 'Bradley William',
                'email' => 'bradleywill@proton.me',
                'date_added' => '12/12/2025',
                'department' => 'QA',
                'status' => 'Active',
            ],
            [
                'id' => 5,
                'name' => 'Bradley William',
                'email' => 'bradleywill@proton.me',
                'date_added' => '12/12/2025',
                'department' => 'QA',
                'status' => 'Inactive', // Contoh inactive
            ],
            [
                'id' => 6,
                'name' => 'Bradley William',
                'email' => 'bradleywill@proton.me',
                'date_added' => '12/12/2025',
                'department' => 'QA',
                'status' => 'Active',
            ],
        ];

        return view('users.index', compact('stats', 'users'));
    }

    public function create()
    {
        return view('users.create');
    }
}
