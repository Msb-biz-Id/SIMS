<?php
namespace App\Core;

use App\SistemDataMaster\Models\Role;

abstract class Controller
{
    protected string $pageTitle = 'Dashboard';
    protected array $layoutData = [];

    protected function setPageTitle(string $title): void
    {
        $this->pageTitle = $title;
    }

    protected function render(string $module, string $view, array $data = []): void
    {
        $content = $this->renderView($module, $view, $data);
        $pageTitle = $this->pageTitle;
        $layoutData = $this->layoutData;

        require __DIR__ . '/../Views/layouts/main.php';
    }

    protected function renderAuth(string $view, array $data = []): void
    {
        $content = $this->renderViewRaw(__DIR__ . '/../Auth/Views/' . $view . '.php', $data);
        $pageTitle = $this->pageTitle;
        $layoutData = $this->layoutData;
        require __DIR__ . '/../Views/layouts/auth.php';
    }

    protected function renderView(string $module, string $view, array $data = []): string
    {
        $viewPath = __DIR__ . '/../' . $module . '/Views/' . $view . '.php';
        if (!is_file($viewPath)) {
            http_response_code(500);
            throw new \RuntimeException("View not found: {$viewPath}");
        }
        extract($data, EXTR_OVERWRITE);
        ob_start();
        require $viewPath;
        return (string) ob_get_clean();
    }

    protected function renderViewRaw(string $viewPath, array $data = []): string
    {
        if (!is_file($viewPath)) {
            http_response_code(500);
            throw new \RuntimeException("View not found: {$viewPath}");
        }
        extract($data, EXTR_OVERWRITE);
        ob_start();
        require $viewPath;
        return (string) ob_get_clean();
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . base_url($path));
        exit;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu';
            $this->redirect('auth/login');
        }
    }

    protected function requireRole(string $roleName): void
    {
        $this->requireAuth();
        $roleModel = new Role();
        if (!$roleModel->userHasRole((int) $_SESSION['user_id'], $roleName)) {
            http_response_code(403);
            exit('Forbidden: role ' . $roleName . ' diperlukan');
        }
    }
}