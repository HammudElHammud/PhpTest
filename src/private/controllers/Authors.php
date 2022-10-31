<?php

class Authors extends Controller
{
    function index()
    {
        try {
            $data = [];
            $db = new Database();
            $search = '';
            if (isset($_REQUEST['q'])) {
                $search = $_REQUEST['q'];
            }
            $data = $db->query(
                "SELECT AT.*, BK.name as book_name, BK.id as book_id FROM authors AS AT
                    LEFT JOIN books AS BK ON AT.id = BK.author_id
                 WHERE AT.name LIKE '%${search}%'",
                [],
                "assoc"
            );
        } catch (Exception $e) {
            $this->jsonResponse([
                "message" => "Something went wrong while trying to retrieve authos and books",
                "status" => "failed"
            ], 500);
        }
        $this->jsonResponse($data ? $data : []);
    }
}
