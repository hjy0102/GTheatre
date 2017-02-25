<?php declare(strict_types = 1);

namespace GTheatre\Template;

interface Renderer
{
   public function render($dir, $template, $data = []);
   
}