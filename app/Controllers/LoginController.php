<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LoginController extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function auth()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Static validation for demo purposes with password hashing
        // Recommended: Use a database-driven user management system
        $storedHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // Hash for 'admin123'

        if ($username === 'admin' && password_verify($password, $storedHash)) {
            session()->set([
                'isLoggedIn' => true,
                'username' => $username,
                'role' => 'Super Admin',
            ]);

            return redirect()->to('/backup');
        }

        return redirect()->to('/login')->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
