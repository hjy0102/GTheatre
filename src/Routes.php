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
    ['POST', '/account/update', ['GTheatre\Controllers\Accountpage', 'update']],
    ['GET', '/account/chef/all', ['GTheatre\Controllers\Accountpage', 'showAllChefAccounts']],
    ['GET', '/account/chef/create', ['GTheatre\Controllers\Accountpage', 'showCreateChefForm']],
    ['POST', '/account/chef/create', ['GTheatre\Controllers\Accountpage', 'createChefAccount']],
    ['GET', '/account/chef/edit/{username}', ['GTheatre\Controllers\Accountpage', 'showEditChefForm']],
    ['POST', '/account/chef/update', ['GTheatre\Controllers\Accountpage', 'updateChefAccount']],
    ['POST', '/account/chef/delete', ['GTheatre\Controllers\Accountpage', 'deleteChefAccount']],

// browse movies page 
    ['GET', '/showtimes', ['GTheatre\Controllers\ShowtimesPage', 'show']],
    ['GET', '/showtimes/populate-movies', ['GTheatre\Controllers\ShowtimesPage', 'populateMovies']],
    ['GET', '/showtimes/populate-halls', ['GTheatre\Controllers\ShowtimesPage', 'populateHalls']],


// buy tickets page
	['GET', '/BuyTickets', ['GTheatre\Controllers\BuyTicketsPage', 'show']],

];
