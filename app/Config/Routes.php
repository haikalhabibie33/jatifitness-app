<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// PUBLIC ROUTES =====================================
$routes->group('',static function($routes){
    $routes->get('/', 'Public\HomeController::index');
    $routes->get('about', 'Public\AboutController::index');
    // CONTACT ROUTES
    $routes->get('contact', 'Public\ContactController::index');
    $routes->post('contact/submit', 'Public\ContactController::submit');
    
    // BECAME A MEMBER ROUTES
    // -------------> Start process for both member and casual
    $routes->get('membership/index', 'Public\MemberController::index');
    // -------------> Casual
    $routes->post('membership/submit-casual', 'Public\MemberController::submitCasual');
    // ------------> Member
    $routes->post('membership/check-duplicate', 'Public\MemberController::checkMemberAvailability');
    $routes->post('membership/submit-member', 'Public\MemberController::submitMembership');
    // -------------> handling till reaching payment status
    $routes->get('membership/request-submitted', 'Public\MemberController::requestSubmitted');
    
    // PAYMENT
    $routes->get('payment/start', 'Public\PaymentController::start');
    $routes->get('payment/complete', 'Public\PaymentController::complete');

    $routes->post('payment/transfer/submit-proof', 'Public\TransferPaymentController::submitProof');
    
    // ACCESS
    // ---- casual access
    $routes->get('access/casual/(:segment)', 'Public\AccessController::casual/$1');
    // ----- member access
    $routes->get('access/member/(:segment)', 'Public\AccessController::member/$1');
    
    $routes->post('gym/scan', 'Public\GymAccessController::scan');
    
    
    $routes->get('/services', 'Public\ServicesController::index');
});
// END OF PUBLIC ROUTES ==================================

// ADMIN ROUTES =========================================================
$routes->group('admin', ['filter' => 'authAdmin'], function($routes){
    // $routes->post('/', 'UserController::postAdmin'); //should change it to '/dashboard' later
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('about', 'Admin\AboutController::index');

    //CONTACT ROUTES
    $routes->get('contact', 'Admin\ContactController::getContact');
    $routes->post('contact/delete/(:num)', 'Admin\ContactController::deleteContact/$1');

    
    // MEMBER
    $routes->get('member', 'Admin\MemberController::index');
    $routes->post('member/store', 'Admin\MemberController::store');
    $routes->post('member/update/(:num)', 'Admin\MemberController::update/$1');
    $routes->post('member/deactivate/(:num)', 'Admin\MemberController::deactivate/$1');
    
    // MEMBERSHIP
    $routes->post('membership/store/(:num)', 'Admin\MemberController::addMembership/$1');
    $routes->post('membership/suspend/(:num)', 'Admin\MembershipController::suspend/$1');
    $routes->post('membership/activate/(:num)', 'Admin\MembershipController::activate/$1');

    // MEMBERSHIP REQUEST
    $routes->get('membership-request', 'Admin\MembershipRequestController::index');
    $routes->post('membership-request/approve/(:num)', 'Admin\MembershipRequestController::approve/$1');
    $routes->post('membership-request/reject/(:num)', 'Admin\MembershipRequestController::reject/$1');

    // MEMBERSHIP PLAN
    $routes->get('membership-package', 'Admin\MembershipPlanController::index');
    $routes->post('membership-package/store', 'Admin\MembershipPlanController::store');
    $routes->post('membership-package/update/(:num)', 'Admin\MembershipPlanController::update/$1');
    $routes->post('membership-package/toggle/(:num)', 'Admin\MembershipPlanController::toggle/$1');


    // TRANSACTION ROUTES
    $routes->get('transaction', 'Admin\TransactionController::index');
    $routes->get('visitor-checkin', 'Admin\TransactionController::checkIn');
    $routes->get('payment', 'Admin\PaymentController::index');
    $routes->post('payment/confirm', 'Admin\PaymentController::confirmOffline');
    $routes->get('payment/access/(:segment)', 'Admin\PaymentController::access/$1');

    // SUB TRANSACTION ROUTES (casual passes and membership registration through admin menu)
    $routes->post('payment/casual-pass/store', 'Admin\PaymentController::createCasualPasses');
    $routes->post('payment/member-pass/store', 'Admin\PaymentController::createMemberPasses');
    $routes->post('payment/member-pass/check-duplicate', 'Admin\PaymentController::checkMemberAvailability');

    // CHECK IN
    $routes->get('checkin', 'Admin\CheckinController::index');
    $routes->get('checkin-list','Admin\CheckinListController::index');
    $routes->post('checkin/confirm', 'Admin\CheckinController::confirm');

    // SCHEDULE
    $routes->get('schedule', 'Admin\ScheduleController::index');
    $routes->post('schedule/store', 'Admin\ScheduleController::store');
    $routes->post('schedule/update/(:num)','Admin\ScheduleController::update/$1');
    $routes->post('schedule/status/(:num)','Admin\ScheduleController::changeStatus/$1');

    // REFUND ROUTES
    $routes->get('refund', 'Admin\RefundController::index');
    $routes->get('history-refund', 'Admin\RefundController::history');
    $routes->post('refund/casual/(:num)', 'Admin\RefundController::refundCasual/$1');
    $routes->post('refund/member/(:num)', 'Admin\RefundController::refundMember/$1');

    // BASIC INFORMATION ROUTES
    $routes->get('basicinformation', 'Admin\BasicInformationController::index');
    $routes->post('basicinformation/update', 'Admin\BasicInformationController::update');
    $routes->post('basicinformation/reset', 'Admin\BasicInformationController::resetAll');
});

// AUTH ROUTES =================================================
$routes->get('/login','Auth\LoginController::index');
$routes->post('/login','Auth\LoginController::authenticate');
$routes->get('/logout','Auth\LoginController::logout');

// FORGOT PASSWORD
$routes->get('forgot-password', 'Auth\ForgotPasswordController::index');
// $routes->get('/forgot-password','UserController::forgotPassword');
// $routes->post('/send-otp', 'UserController::otpVerification'); 


$routes->post('forgot-password/send', 'Auth\ForgotPasswordController::sendOtp');

$routes->get('forgot-password/verify', 'Auth\ForgotPasswordController::verifyForm');
$routes->post('forgot-password/verify', 'Auth\ForgotPasswordController::verifyOtp');

$routes->get('forgot-password/reset', 'Auth\ForgotPasswordController::resetForm');
$routes->post('forgot-password/reset', 'Auth\ForgotPasswordController::resetPassword');

// END OF AUTH ROUTES ================================================

// TEST
// $routes->get('test-email', 'TestEmail::index');
// $routes->post('/admin/test-insert-transaction','Admin\TransactionController::fakeInsert');

//TESTING UTILITIES => will be switched to API routes with JSON request and response soon
$routes->group('testing', ['filter' => ['authAdmin', 'noCache']], function($routes){
    //get all members
    $routes->get('members','Testing\MembershipTestController::getAllMembers');

    // get member by id
    $routes->get('member/(:num)', 'Testing\MembershipTestController::getMemberById/$1');

    // get all memberships
    $routes->get('memberships', 'Testing\MembershipTestController::getAllMemberships');

    // get memberships by member id
    $routes->get('memberships/(:num)', 'Testing\MembershipTestController::getMembershipsByMemberId/$1');

    // get member by phone and email
    $routes->get('member/phone-email', 'Testing\MembershipTestController::getMemberByPhoneAndEmail');

    // create member without membership
    $routes->post('create-member', 'Testing\MembershipTestController::createMemberWithoutMembership');

    // get all active memberships of member id
    $routes->get('memberships/active/(:num)', 'Testing\MembershipTestController::getActiveMembershipsByMemberId/$1');

    // get all expired memberships of member id
    $routes->get('memberships/expired/(:num)', 'Testing\MembershipTestController::getExpiredMembershipsByMemberId/$1');

    // get all transactions
    $routes->get('transactions', 'Testing\TransactionTestController::getAllTransactions');

    // get all transactions of member by phone and email
    $routes->get('transactions/phone-email', 'Testing\TransactionTestController::getTransactionsByPhoneEmail');

    // get all membership requests
    $routes->get('membership-requests', 'Testing\MembershipRequestTestController::getAllMembershipRequests');

    // get all student membership requests
    $routes->get('membership-requests/student', 'Testing\MembershipRequestTestController::getAllStudentMembershipRequests');

    // get all regular membership requests
    $routes->get('membership-requests/regular', 'Testing\MembershipRequestTestController::getAllRegularMembershipRequests');

    // get all membership requests by phone and email
    $routes->get('membership-requests/phone-email', 'Testing\MembershipRequestTestController::getMembershipRequestsByPhoneEmail');

    // get all membership plans
    $routes->get('membership-plans', 'Testing\MembershipPlanTestController::getAllMembershipPlans');

    // get all student membership plans
    $routes->get('membership-plans/student', 'Testing\MembershipPlanTestController::getAllStudentMembershipPlans');

    // get all regular membership plans
    $routes->get('membership-plans/regular', 'Testing\MembershipPlanTestController::getAllRegularMembershipPlans');

    // clear member test data
    $routes->post('member/clear-data', 'Testing\ClearTestDataController::clearMemberTestData');

    // clear casual pass test data
    $routes->post('casual/clear-data', 'Testing\ClearTestDataController::clearCasualTestData'); 
});

// PUBLIC API ROUTES GROUP
$routes->group('api/v1', static function($routes){
    // auth with api
    $routes->post('auth/login', 'Api\Auth\LoginControllerApi::authenticate');

    // membership plan
    $routes->get('membership-plan/all', 'Api\Public\PublicMembershipPlanController::findAll');
    $routes->get('membership-plan/(:segment)', 'Api\Public\PublicMembershipPlanController::findByID/$1');
    $routes->get('membership-plan/(:segment)/active','Api\Public\PublicMembershipPlanController::findActiveByID/$1');
    $routes->get('membership-plan/(:segment)/code', 'Api\Public\PublicMembershipPlanController::findByCode/$1');

});

// ADMIN API ROUTES GROUP
$routes->group('api/v1/admin', ['filter' => ['authAdmin', 'noCache']], function($routes){
    // membership plan routes
    $routes->get('membership-plan/all', 'Api\Admin\AdminMembershipPlanController::findAll');
    $routes->get('membership-plan/(:segment)/active','Api\Admin\AdminMembershipPlanController::findActiveByID/$1');
    $routes->get('membership-plan/(:segment)','Api\Admin\AdminMembershipPlanController::findByID/$1');
    $routes->get('membership-plan/(:segment)/code', 'Api\Admin\AdminMembershipPlanController::findByCode/$1');

    $routes->post('membership-plan', 'Api\Admin\AdminMembershipPlanController::create');
    $routes->put('membership-plan/(:segment)', 'Api\Admin\AdminMembershipPlanController::update/$1');
    $routes->delete('membership-plan/(:segment)', 'Api\Admin\AdminMembershipPlanController::delete/$1');

    // member routes
    $routes->get('members', 'Api\Admin\AdminMemberController::findAll');
    $routes->get('member/(:segment)','Api\Admin\AdminMemberController::findByID/$1');
});