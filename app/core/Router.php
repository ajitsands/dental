<?php
// app/core/Router.php

class Router {
    protected $currentController = 'Home';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // Look in controllers for first value
        if (isset($url[0])) {
            if (file_exists(APPROOT . '/app/controllers/' . ucwords($url[0]) . '.php')) {
                $this->currentController = ucwords($url[0]);
                unset($url[0]);
            }
        }

        require_once APPROOT . '/app/controllers/' . $this->currentController . '.php';
        $this->currentController = new $this->currentController;

        // Check for second part of url
        if (isset($url[1])) {
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        } else {
            // Fallback for PHP built-in server or missing .htaccess
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $url = rtrim($uri, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', ltrim($url, '/'));
            return $url[0] == '' ? [] : $url;
        }
    }
}
