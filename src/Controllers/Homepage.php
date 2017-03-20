<?php declare(strict_types = 1);

namespace GTheatre\Controllers;

use Http\Request;
use Http\Response;
use GTheatre\Template\FrontendRenderer;

class Homepage
{
    private $request;
    private $response;
    private $renderer;

    private $templateDir = '';

    public function __construct(Request $request, Response $response, FrontendRenderer $renderer)
    {
       $this->request = $request;
       $this->response = $response;
       $this->renderer = $renderer;
    }

    function debug_to_console( $data ) {
        $output = $data;
        if ( is_array( $output ) )
            $output = implode( ',', $output);

        echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    }

    public function show()
    {

       $data = [
          'name' => $this->request->getParameter('name', 'stranger'),
       ];

       $html = $this->renderer->render($this->templateDir, 'Homepage', $data);
       $this->response->setContent($html);
    }

    public function signout(){
       session_destroy();
    }


}