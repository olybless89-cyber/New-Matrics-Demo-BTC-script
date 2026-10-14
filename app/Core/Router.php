<?php

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');

        if ($method === 'POST') {
            CSRF::check();
        }

        foreach ($this->routes as $route) {
            $params = $this->match($route['path'], $uri);
            if ($params !== null && $route['method'] === $method) {
                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$class, $action] = $handler;
                    $controller = new $class();
                    $controller->$action(...array_values($params));
                } else {
                    $handler(...array_values($params));
                }
                return;
            }
        }

        http_response_code(404);
        View::render('errors/404', [], 'plain');
    }

    private function match(string $pattern, string $uri): ?array
    {
        $pattern = '/' . trim($pattern, '/');
        if ($pattern === '//') {
            $pattern = '/';
        }
        $patternParts = explode('/', $pattern);
        $uriParts = explode('/', $uri);
        if (count($patternParts) !== count($uriParts)) {
            return null;
        }
        $params = [];
        foreach ($patternParts as $i => $part) {
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $params[] = $uriParts[$i];
            } elseif ($part !== $uriParts[$i]) {
                return null;
            }
        }
        return $params;
    }
}
