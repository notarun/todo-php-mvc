<?php

namespace App\Controllers;

use Core\View;
use Core\Redirect;

class Controller
{
    /**
     * Render the view.
     *
     * @param string $viewName
     * @param array $data
     * @return void
     */
    protected function render(string $viewName, array $data)
    {
        return View::render($viewName, $data);
    }

    /**
     * Redirect to the specified url.
     *
     * @param string $uri
     * @param array $message
     * @return void
     */
    protected function redirect(string $uri, array $message = [])
    {
        return Redirect::to($uri, $message);
    }

    /**
     * Go back to previous url.
     *
     * @param array $message
     * @return void
     */
    protected function back(array $message = [])
    {
        return Redirect::back($message);
    }

    /**
     * Return the JSON data.
     *
     * @param array $input
     * @return void
     */
    protected function jsonify(array $input)
    {
        header('Content-type: application/json'); 
        echo json_encode($input);
        exit();
    }
}
