<?php

namespace Core;

use Exception;
use Core\View;
use Core\Csrf;
use Core\Redirect;
use Core\Container;

class Router
{
    private const GET = 'GET';
    private const POST = 'POST';

    /**
     * Stores all the GET and POST routes defined in routes file.
     *
     * @var array
     */
    public $routes = [
        self::GET => [],
        self::POST => []
    ];

    /**
     * Stores all the dynamic routes.
     * Dynamic route example: "/foo/:bar"
     * Here ":bar" is a placeholder.
     *
     * @var array
     */
    public $dynamicRoutes = [
        self::GET => [],
        self::POST => []
    ];

    /**
     * Load up the routes file.
     *
     * @param string $file
     * @return Router
     */
    public static function load(string $file): Router
    {
        $router = new static;
        require $file;
        return $router;
    }

    /**
     * Add new GET/POST route
     *
     * @param string $method
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function add(string $method, string $uri, string $controller): void
    {
        // remove trailing slash from the uri before storing it
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = substr($uri, 0, -1);
        }

        if (!$this->hasPlaceholder($uri)) {
            $this->routes[$method][$uri] = $controller;
            return;
        }

        $this->dynamicRoutes[$method][$uri] = $controller;
    }

    /**
     * Adds new GET route
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get(string $uri, string $controller): void
    {
        $this->add(self::GET, $uri, $controller);
    }

    /**
     * Adds new POST route
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post(string $uri, string $controller): void
    {
        $this->add(self::POST, $uri, $controller);
    }

    public function run(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        // if request uri have a trailing / redirect to the not trailed version
        if ($requestUri !== '/' && substr($requestUri, -1) === '/') {
            Redirect::to(substr($requestUri, 0, -1));
        }

        if ($requestMethod !== self::GET && $requestMethod !== self::POST) {
            throw new Exception('Unsupported HTTP method.');
        }

        $action = $this->resolveRoute($requestMethod, $requestUri);

        if (!is_null($action)) {
            $this->runAction(...$action);
            return;
        }

        // nothing found return a 404 page
        View::throw404();
   }

    /**
     * Find the appropriate route and do things accordingly.
     *
     * @param string $requestMethod
     * @param string $requestUri
     * @return void
     */
    private function resolveRoute(string $requestMethod, string $requestUri): ?array
    {
        if (array_key_exists($requestUri, $this->routes[$requestMethod])) {
            return explode('@', $this->routes[$requestMethod][$requestUri]);
        }

        foreach ($this->dynamicRoutes[$requestMethod] as $dynamicRoute => $controller) {
            $routeTemplate = explode('/', $dynamicRoute);
            $requestRoute = explode('/', $requestUri);

            // not the route we are looking for move to next iteration
            if (count($routeTemplate) !== count($requestRoute)) {
                continue;
            }

            $params = [];
            $counter = 0;
            foreach ($routeTemplate as $element) {
                if ($element !== $requestRoute[$counter]) {
                    // if current element is not a placeholder break out.
                    if ($element[0] !== ':') {
                        $params = [];
                        break;
                    }

                    $params[substr($element, 1)] = $requestRoute[$counter];
                }

                $counter++;
            }

            if ($counter === count($requestRoute)) {
                return array_merge(explode('@', $controller), [$params]);
            }
        }

        // nothing found
        return null;
    }

   /**
    * Creates instance of the controller class and
    * calls the action method accordingly.
    *
    * @param string $class
    * @param string $method
    * @param array $params
    * @return void
    */
    private function runAction(string $class, string $method, array $params = []): void
    {
        $className = CONTROLLERS_CLASS_PATH . $class;

        if (!class_exists($className)) {
            throw new Exception("${className} doesn't exist.");
        }

        $controller = new $className(Container::load(PROJECT_DIR . '/src/containers.php'), $params);

        if (!method_exists($controller, $method)) {
            throw new Exception("${class} doesn't contains ${method} method.");
        }

        // csrf token is generated inside View::render()
        if ($_SERVER['REQUEST_METHOD'] === self::POST) {
            Csrf::validateToken();
        }

        $controller->$method($_REQUEST, $params);
    }

    /**
     * Checks whether the given route have a plcaeholder or not.
     *
     * @param string $route
     * @return boolean
     */
    private function hasPlaceholder(string $route): bool
    {
        return strpos($route, ':') ? true : false;
    }
}