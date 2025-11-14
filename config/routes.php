<?php
/**
 * Complete Application Routes
 * Configure all routes for the RMS application
 */

function registerRoutes($router) {
    // ===== DASHBOARD ROUTES =====
    $router->register('GET', '/dashboard', 'DashboardController@index');
    $router->register('GET', '/system/info', 'DashboardController@info');
    
    // ===== EVENT ROUTES =====
    $router->register('GET', '/events', 'EventsController@index');
    $router->register('GET', '/events/create', 'EventsController@create');
    $router->register('POST', '/events/store', 'EventsController@store');
    $router->register('GET', '/events/:id', 'EventsController@show');
    $router->register('GET', '/events/:id/edit', 'EventsController@edit');
    $router->register('POST', '/events/:id/update', 'EventsController@update');
    $router->register('POST', '/events/:id/delete', 'EventsController@delete');
    $router->register('POST', '/events/:id/add-category', 'EventsController@addCategory');
    
    // ===== ATHLETE ROUTES =====
    $router->register('GET', '/athletes', 'AthletesController@index');
    $router->register('GET', '/athletes/create', 'AthletesController@create');
    $router->register('POST', '/athletes/store', 'AthletesController@store');
    $router->register('GET', '/athletes/:id', 'AthletesController@show');
    $router->register('GET', '/athletes/:id/edit', 'AthletesController@edit');
    $router->register('POST', '/athletes/:id/update', 'AthletesController@update');
    $router->register('GET', '/athletes/search', 'AthletesController@search');
    $router->register('GET', '/athletes/import', 'AthletesController@import');
    $router->register('POST', '/athletes/import', 'AthletesController@import');
    
    // ===== RESULTS ROUTES =====
    $router->register('GET', '/results/record', 'ResultsController@record');
    $router->register('POST', '/results/store', 'ResultsController@store');
    $router->register('GET', '/results/list', 'ResultsController@list');
    $router->register('GET', '/results/:id', 'ResultsController@show');
    $router->register('GET', '/results/:id/edit', 'ResultsController@edit');
    $router->register('POST', '/results/:id/update', 'ResultsController@update');
    $router->register('POST', '/results/:id/delete', 'ResultsController@delete');
    
    // ===== RANKINGS ROUTES =====
    $router->register('GET', '/rankings/event', 'RankingsController@event');
    $router->register('POST', '/rankings/calculate', 'RankingsController@calculate');
    $router->register('GET', '/rankings/team', 'RankingsController@overall');
    $router->register('GET', '/rankings/category', 'RankingsController@category');
    $router->register('GET', '/rankings/top', 'RankingsController@top');
    $router->register('GET', '/rankings/json', 'RankingsController@json');
    
    // ===== IMPORT ROUTES =====
    $router->register('GET', '/imports', 'ImportController@index');
    $router->register('GET', '/imports/form', 'ImportController@form');
    $router->register('POST', '/imports/upload', 'ImportController@upload');
    $router->register('POST', '/imports/preview', 'ImportController@preview');
    $router->register('POST', '/imports/process', 'ImportController@process');
    $router->register('GET', '/imports/result/:id', 'ImportController@result');
    $router->register('GET', '/imports/template', 'ImportController@template');
    
    // ===== REGISTRATION ROUTES =====
    $router->register('GET', '/registrations/form', 'RegistrationController@form');
    $router->register('POST', '/registrations/submit', 'RegistrationController@submit');
    $router->register('GET', '/registrations/confirm', 'RegistrationController@confirm');
    $router->register('GET', '/registrations/list', 'RegistrationController@list');
    $router->register('GET', '/registrations/:id', 'RegistrationController@view');
    $router->register('POST', '/registrations/:id/approve', 'RegistrationController@approve');
    $router->register('POST', '/registrations/:id/reject', 'RegistrationController@reject');
    $router->register('POST', '/registrations/bulk-approve', 'RegistrationController@bulkApprove');
    $router->register('GET', '/registrations/export', 'RegistrationController@export');
    
    // ===== HOME ROUTE =====
    $router->register('GET', '/', 'DashboardController@index');
}

return 'Routes registered successfully';
