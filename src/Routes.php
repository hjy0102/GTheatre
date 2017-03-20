<?php declare(strict_types = 1);

// can remove param from route for POST

//format 
/** 
1. restful API
2. web URL extension 
3. [ File , function ]
**/
return [

// home
    ['GET', '/', ['GTheatre\Controllers\Homepage', 'show']],
    ['POST', '/signout', ['GTheatre\Controllers\Homepage', 'signout']],

// login page
    ['GET', '/login', ['GTheatre\Controllers\Loginpage', 'show']],
    ['POST', '/login', ['GTheatre\Controllers\Loginpage', 'login']],
    ['POST', '/account/customer/createCustomerAccount', ['GTheatre\Controllers\Loginpage', 'createCustomerAccount']],
    ['POST', '/account/employee/createEmployeeAccount', ['GTheatre\Controllers\Loginpage', 'createEmployeeAccount']], 

// // menu page
//     ['GET', '/menuItems', ['GTheatre\Controllers\Menupage', 'showAllMenuItems']],
//     ['GET', '/menuItem/create', ['GTheatre\Controllers\Menupage', 'showCreateMenuItemForm']],
//     ['POST', '/menuItem/create', ['GTheatre\Controllers\Menupage', 'create']],
//     ['GET', '/menuItem/update/{id}', ['GTheatre\Controllers\Menupage', 'showUpdateMenuItemForm']],
//     ['POST', '/menuItem/update', ['GTheatre\Controllers\Menupage', 'update']],
//     ['POST', '/menuItem/delete', ['GTheatre\Controllers\Menupage', 'delete']],
//     ['GET', '/menuItem/search', ['GTheatre\Controllers\Menupage', 'showMenuItemSearchForm']],
//     ['GET', '/menuItem/searchResult', ['GTheatre\Controllers\Menupage', 'showMenuItemSearchResult']],


// // ingredient page
//     ['GET', '/ingredients', ['GTheatre\Controllers\Ingredientpage', 'show']],
//     ['POST', '/ingredient/create', ['GTheatre\Controllers\Ingredientpage', 'create']],
//     ['POST', '/ingredient/update', ['GTheatre\Controllers\Ingredientpage', 'update']],
//     ['POST', '/ingredient/delete', ['GTheatre\Controllers\Ingredientpage', 'delete']],

// // order page
//     ['GET', '/orders/paid', ['GTheatre\Controllers\Orderpage', 'showPaidOrders']],
//     ['GET', '/order/current', ['GTheatre\Controllers\Orderpage', 'showCurrentOrder']],
//     ['GET', '/order/update/menuItem/{id}', ['GTheatre\Controllers\Orderpage', 'showOrderMenuItemForm']],
//     ['GET', '/order/current/all', ['GTheatre\Controllers\Orderpage', 'showAllChefOrder']],
//     ['POST', '/order/current/start', ['GTheatre\Controllers\Orderpage', 'chefStartOrder']],
//     ['POST', '/order/current/complete', ['GTheatre\Controllers\Orderpage', 'chefCompleteOrder']],
//     ['POST', '/order/create', ['GTheatre\Controllers\Orderpage', 'createOrder']],
//     ['POST', '/order/addMenuItem', ['GTheatre\Controllers\Orderpage', 'addMenuItem']],
//     ['POST', '/order/update/menuItem', ['GTheatre\Controllers\Orderpage', 'updateMenuItemQuantity']],
//     ['POST', '/order/removeMenuItem', ['GTheatre\Controllers\Orderpage', 'removeMenuItem']],
//     ['POST', '/order/purchase', ['GTheatre\Controllers\Orderpage', 'purchase']],

// // account page
//     ['GET', '/account', ['GTheatre\Controllers\Accountpage', 'show']],
//     ['POST', '/account/update', ['GTheatre\Controllers\Accountpage', 'update']],
//     ['GET', '/account/chef/all', ['GTheatre\Controllers\Accountpage', 'showAllChefAccounts']],
//     ['GET', '/account/chef/create', ['GTheatre\Controllers\Accountpage', 'showCreateChefForm']],
//     ['POST', '/account/chef/create', ['GTheatre\Controllers\Accountpage', 'createChefAccount']],
//     ['GET', '/account/chef/edit/{username}', ['GTheatre\Controllers\Accountpage', 'showEditChefForm']],
//     ['POST', '/account/chef/update', ['GTheatre\Controllers\Accountpage', 'updateChefAccount']],
//     ['POST', '/account/chef/delete', ['GTheatre\Controllers\Accountpage', 'deleteChefAccount']],

];