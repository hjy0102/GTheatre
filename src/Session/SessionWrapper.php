<?php declare(strict_types = 1);

namespace GTheatre\Session;

// TO DELETE LATER !!!
echo 'Hello from Session Wrapper';

interface SessionWrapper {

   public function getValue($key);
   public function setValue($key, $value);

}