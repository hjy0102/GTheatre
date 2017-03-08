<?php declare(strict_types = 1);

// can remove param from route for POST
return [

// home
    ['GET', '/', ['GTheatre\Controllers\Home', 'show']],
    ['POST', '/signout', ['GTheatre\Controllers\Home', 'signout']],

// login page
    ['GET', '/login', ['GTheatre\Controllers\Loginpage', 'show']],
    ['POST', '/login', ['GTheatre\Controllers\Loginpage', 'login']],
    ['POST', '/account/customer/create', ['GTheatre\Controllers\Loginpage', 'createAccount']],


];