<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            helper('cookie');
            $token = get_cookie('remember_admin');
            
            if ($token) {
                $adminModel = new \App\Models\AdminModel();
                $admin = $adminModel->where('remember_token', $token)->first();
                
                if ($admin) {
                    // Auto login
                    $ses_data = [
                        'id'         => $admin['id'],
                        'nama'       => $admin['nama'],
                        'username'   => $admin['username'],
                        'foto_profil'=> $admin['foto_profil'],
                        'isLoggedIn' => TRUE
                    ];
                    session()->set($ses_data);
                    return; // Continue request
                }
            }
            
            return redirect()->to('/admin/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
