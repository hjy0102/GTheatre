<?php declare(strict_types=1);

// src/Controllers/Home.php 

namespace GTheatre\Controllers;

use Http\Request;
use Http\Response;
use GTheatre\Template\FrontendRenderer;

class Home {
    private $req;
    private $resp;
    private $renderer;

    private $tempDir = ''; 

    public function homeContruct (Request $req, Response $resp, FrontendRenderer $renderer) {
        $this->req = $req;
        $this->resp = $resp;
        $this->renderer = $renderer;

    }

// signout of session

    public function signout(){
        session_destroy();
    }

// get parametres from request

    public function show() {
        $info = [
            'name' => $this->req->getParameter('name', 'stranger'),
        ];
        $html = $this->renderer->render($this->tempDir, 'Home', $info);

        $this->resp->setContent($html);
    }

}
