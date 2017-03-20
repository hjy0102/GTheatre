<?php declare(strict_types = 1);

namespace GTheatre\Template;

use Twig_Environment;

class TwigRenderer implements Renderer
{
   private $renderer;

   public function __construct(Twig_Environment $renderer)
   {
      $this->renderer = $renderer;
   }

   public function render($dir, $template, $data = []) : string
   {
      $path = $dir . '/' . $template . '.html';
      return $this->renderer->render($path, $data);
   }
}