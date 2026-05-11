<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (empty($session->get('user_id'))) {
            $session->setFlashdata('flash_error', 'Session expire, vous devez vous connecter d\'abord');
            return redirect()->to('/SignIn');
        }
        // If the current request targets admin area, ensure the user is admin
        try {
            $uri = service('uri');
            $path = trim((string) $uri->getPath(), '/');
        } catch (\Throwable $e) {
            $path = '';
        }

        if ($path === 'admin' || $path === 'admin-dashboard' || str_starts_with($path, 'admin/')) {
            $userType = $session->get('user_type') ?? null;
            $userTypeId = $session->get('user_type_id') ?? null;
            if ($userType !== 'admin' && (int) $userTypeId !== 2) {
                $session->setFlashdata('flash_error', 'Accès non autorisé. Connexion administrateur requise.');
                return redirect()->to('/SignIn');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No-op
    }
}
