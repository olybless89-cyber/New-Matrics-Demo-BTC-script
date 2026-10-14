<?php

class View
{
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        $viewFile = ROOT . "/app/Views/{$view}.php";
        if (!file_exists($viewFile)) {
            throw new RuntimeException("View not found: {$view}");
        }
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        $layoutFile = ROOT . "/app/Views/layouts/{$layout}.php";
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    public static function partial(string $partial, array $data = []): void
    {
        extract($data);
        $file = ROOT . "/app/Views/partials/{$partial}.php";
        if (file_exists($file)) {
            require $file;
        }
    }
}
