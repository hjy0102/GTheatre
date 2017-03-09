<?php declare(strict_types = 1);

namespace GTheatre\Template;

use GTheatre\Session\SessionWrapper;

class FrontendTwigRenderer implements FrontendRenderer{
    private $renderer;
    private $session;

    public function __construct(Renderer $renderer, SessionWrapper $session){
 
        $this->renderer = $renderer;
        $this->session = $session;
    }
    
    public function render($dir, $template, $data = []) {
        $accType = $this->session->getValue('accType');

        $data = array_merge($data, [
         'accType' => $accType]);
        
        return $this->renderer->render($dir, $template, $data);
    }

}
