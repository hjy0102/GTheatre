<?php declare(strict_types = 1);
// can remove param from route for POST
return [
// home
    ['GET', '/', ['GTheatre\Controllers\Homepage', 'show']],
    ['POST', '/signout', ['GTheatre\Controllers\Homepage', 'signout']],
// login page
    ['GET', '/login', ['GTheatre\Controllers\Loginpage', 'show']],
    ['POST', '/login', ['GTheatre\Controllers\Loginpage', 'login']],
    ['POST', '/account/customer/createCustomerAccount', ['GTheatre\Controllers\LoginPage', 'createCustomerAccount']],
    ['POST', '/account/employee/createEmployeeAccount', ['GTheatre\Controllers\LoginPage', 'createEmployeeAccount']],
// account page
    ['GET', '/account', ['GTheatre\Controllers\Accountpage', 'show']],
    ['POST', '/account/customer/update', ['GTheatre\Controllers\Accountpage', 'updateCustomer']],
    ['POST', '/account/employee/update', ['GTheatre\Controllers\Accountpage', 'updateEmployee']],
    // show tickets
    ['GET', '/mytickets', ['GTheatre\Controllers\MyTickets', 'show']],
   
// browse movies page 
    ['GET', '/showtimes', ['GTheatre\Controllers\ShowtimesPage', 'show']],
    ['GET', '/showtimes/populate-movies', ['GTheatre\Controllers\ShowtimesPage', 'populateMovies']],
    ['GET', '/showtimes/populate-halls', ['GTheatre\Controllers\ShowtimesPage', 'populateHalls']],
    ['GET', '/showtimes/populate-movies/filter', ['GTheatre\Controllers\ShowtimesPage', 'filter']],
// buy tickets page
	['GET', '/BuyTickets', ['GTheatre\Controllers\BuyTicketsPage', 'show']],
	['GET', '/BuyTickets/populate-movies', ['GTheatre\Controllers\BuyTicketsPage', 'populateMovies']],
];