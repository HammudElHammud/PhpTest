<?php

/**
 * main controller class
 */
class Controller
{

    public function view($view, $data = array())
    {
        extract($data);

        if (file_exists("../private/views/" . $view . ".view.php")) {
            require("../private/views/" . $view . ".view.php");
        } else {
            require("../private/views/404.view.php");
        }
    }

    public function jsonResponse($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        die();
    }
}