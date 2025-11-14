<?php
/**
 * Router Class
 * Simple URL routing without framework
 */

class Router {
    private $routes = [];
    private $controller = 'Home';
    private $action = 'index';
    private $params = [];
    
    /**
     * Register a route
     */
    public function register($method, $path, $callback) {
        $this->routes[$method][$path] = $callback;
    }
    
    /**
     * Match and dispatch request
     */
    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = trim($uri, '/');
        
        // Remove base path if needed
        if (!empty($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] != '/index.php') {
            $base = dirname($_SERVER['SCRIPT_NAME']);
            if ($base !== '/' && strpos($uri, $base) === 0) {
                $uri = substr($uri, strlen($base));
                $uri = trim($uri, '/');
            }
        }
        
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Try to match route
        foreach ($this->routes[$method] ?? [] as $pattern => $callback) {
            if ($this->matchRoute($pattern, $uri, $params)) {
                $this->params = $params;
                return call_user_func_array($callback, $params);
            }
        }
        
        // Parse default route: controller/action/params
        $segments = array_filter(explode('/', $uri));
        $segments = array_values($segments); // reindex
        
        if (!empty($segments[0])) {
            $this->controller = ucfirst($segments[0]);
        }
        if (!empty($segments[1])) {
            $this->action = $segments[1];
        }
        if (!empty($segments[2])) {
            $this->params = array_slice($segments, 2);
        }
        
        return $this->callController();
    }
    
    /**
     * Match route pattern
     */
    private function matchRoute($pattern, $uri, &$params) {
        $pattern = preg_replace('/{[^}]+}/', '([^/]+)', $pattern);
        $pattern = '^' . trim($pattern, '/') . '$';
        
        if (preg_match('#' . $pattern . '#', $uri, $matches)) {
            array_shift($matches);
            $params = $matches;
            return true;
        }
        return false;
    }
    
    /**
     * Call controller action
     */
    private function callController() {
        $controllerClass = 'App\\Controllers\\' . $this->controller . 'Controller';
        $controllerFile = __DIR__ . '/../app/controllers/' . $this->controller . 'Controller.php';
        
        if (!file_exists($controllerFile)) {
            return $this->notFound();
        }
        
        require_once $controllerFile;
        
        if (!class_exists($controllerClass)) {
            return $this->notFound();
        }
        
        $controller = new $controllerClass();
        $action = $this->action . 'Action';
        
        if (!method_exists($controller, $action)) {
            return $this->notFound();
        }
        
        return call_user_func_array([$controller, $action], $this->params);
    }
    
    /**
     * 404 Not Found
     */
    private function notFound() {
        http_response_code(404);
        echo "404 - Page Not Found";
    }
}
