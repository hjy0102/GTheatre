<?php declare(strict_types = 1);

namespace GTheatre\Session;

class PHPSessionWrapper implements SessionWrapper
{
   public function getValue($key)
   {
      if (isset($_SESSION[$key])) {
         return $_SESSION[$key];
      }
      else {
         return null;
      }
   }

   public function setValue($key, $value)
   {
      $_SESSION[$key] = $value;
   }
}
?>