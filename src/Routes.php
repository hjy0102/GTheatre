<?php declare(strict_types = 1);
// can remove param from route for POST
return [
// home
    ['GET', '/', ['GTheatre\Controllers\Homepage', 'show']],
    ['POST', '/signout', ['GTheatre\Controllers\Homepage', 'signout']],
// login page
    ['GET', '/login', ['\src\Controllers\LoginPage', 'show']],
    ['POST', '/login', ['GTheatre\Controllers\LoginPage', 'login']],
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
    ['GET', '/showtimes/get-acc', ['GTheatre\Controllers\ShowtimesPage', 'getAccType']],
    ['GET', '/showtimes/populate-movies', ['GTheatre\Controllers\ShowtimesPage', 'populateMovies']],
    ['GET', '/showtimes/populate-halls', ['GTheatre\Controllers\ShowtimesPage', 'populateHalls']],
    ['GET', '/showtimes/populate-movies/filter', ['GTheatre\Controllers\ShowtimesPage', 'filter']],
    ['POST', '/showtimes/update-movie', ['GTheatre\Controllers\ShowtimesPage', 'updateMovie']],
    ['POST', '/showtimes/delete-movie', ['GTheatre\Controllers\ShowtimesPage', 'deleteMovie']],
    ['POST', '/showtimes/add-movie', ['GTheatre\Controllers\ShowtimesPage', 'addMovie']],
    ['POST', '/showtimes/get-sold-count', ['GTheatre\Controllers\ShowtimesPage', 'getSoldCount']],

    
// buy tickets page
	['GET', '/BuyTickets', ['GTheatre\Controllers\BuyTicketsPage', 'show']],
	['POST', '/BuyTickets/createBundle', ['GTheatre\Controllers\BuyTicketsPage', 'createBundle']],
	['POST', '/BuyTickets/createTicket', ['GTheatre\Controllers\BuyTicketsPage', 'createTicket']],
];
?>