<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::group([
    'prefix' => 'auth'
        ], function () {
    Route::post('login', 'API\UserController@login');
    Route::post('loginGoogle', 'API\UserController@loginGoogle');
    Route::post('signup', 'API\UserController@signup');
    Route::post('signupClient', 'API\UserController@signupClient');
    Route::get('auth/{provider}', 'API\UserController@redirectToProvider');
    Route::get('auth/{provider}/callback', 'API\UserController@hanbleProviderCallback');
    Route::post('forgot-password', 'Auth\ForgotPasswordController@forgot');
    Route::post('reset-password', 'Auth\ForgotPasswordController@reset');
    Route::post('get-code-recovery', 'Auth\ForgotPasswordController@get_code_recovery');
    Route::post('reset-recovery-pass', 'Auth\ForgotPasswordController@reset_recovery');
    Route::post('gettest', 'Auth\ForgotPasswordController@test');

    Route::group([
        'middleware' => 'auth:api'
            ], function() {
        Route::get('logout', 'API\UserController@logout');
        Route::get('user', 'API\UserController@user');

        Route::post('setUserCity', 'API\UserController@set_user_city');
    });
});

Route::group([
    'prefix' => 'gymBranch'
        ], function () {
    Route::group([
        'middleware' => 'auth:api',
            ], function() {
        Route::post('getGymBranchs', 'API\EvaluationController@get_gym_branchs');
        Route::post('getAllGymBranchs', 'API\EvaluationController@get_all_gym_branchs');
        Route::post('saveGymBranch', 'API\EvaluationController@save_gym_branch');
        Route::post('deleteGymBranch', 'API\EvaluationController@delete_gym_branch');
    });
});


Route::group([
    'prefix' => 'gymUser'
        ], function () {
    Route::group([
        'middleware' => 'auth:api',
            ], function() {
        Route::get('getGymUser/{id}', 'API\UserController@get_gym_user');
        Route::get('getGymUsers', 'API\UserController@get_gym_users');
        Route::post('saveGymUser', 'API\UserController@save_gym_user');
        Route::post('deleteGymUser', 'API\UserController@delete_gym_user');
    });
});

Route::group([
    'prefix' => 'resource'
        ], function () {
    Route::group([
        'middleware' => 'auth:api'
            ], function() {
        Route::apiResource('resource', 'API\resourceController');
    });
});


Route::group([
    'prefix' => 'gymBranch'
        ], function () {
    Route::group([
        'middleware' => 'auth:api',
            ], function() {
        Route::get('getGymBranchs', 'API\EvaluationController@get_gym_branchs');
        Route::post('getGymBranchData', 'API\EvaluationController@get_gym_branch_data');
        Route::post('saveGymBranch', 'API\EvaluationController@save_gym_branch');
        Route::post('saveGymBranchData', 'API\EvaluationController@save_gym_branch_data');
        Route::post('deleteGymBranch', 'API\EvaluationController@delete_gym_branch');
        Route::post('getGalleryGymBranch', 'API\EvaluationController@get_gallery_gym_branch');
        Route::post('updateGalleryGymBranch', 'API\EvaluationController@update_gallery_gym_branch');
        Route::post('getReviewGymBranch', 'API\EvaluationController@get_review_gym_branch');
        Route::post('updateReviewGymBranch', 'API\EvaluationController@update_review_gym_branch');
    });
});
Route::post('getReviewGymBranch', 'API\EvaluationController@get_review_gym_branch');

Route::group([
    'prefix' => 'slider'
], function () {
    Route::group([
        'middleware' => 'auth:api',
    ], function() {
        Route::post('getSlidersHome', 'API\SliderHomeController@get_sliders_home');
        Route::post('saveSliderHome', 'API\SliderHomeController@save_slider_home');
        Route::post('deleteSliderHome', 'API\SliderHomeController@delete_slider_home');
    });
});
Route::get('getSlidersHomeFront', 'API\SliderHomeController@get_sliders_home');


Route::group([
    'prefix' => 'publicity'
], function () {
    Route::group([
        'middleware' => 'auth:api',
    ], function() {
        Route::post('getPublicity', 'API\PublicityController@get_publicity');
        Route::post('savePublicity', 'API\PublicityController@save_publicity');
        Route::post('deletePublicity', 'API\PublicityController@delete_publicity');
    });
});
Route::get('getPublicity', 'API\PublicityController@get_publicity');


Route::group([
    'prefix' => 'gym'
        ], function () {
    Route::group([
        'middleware' => 'auth:api',
            ], function() {
        Route::get('getGyms', 'API\GymController@get_gyms');
        Route::post('getGymData', 'API\GymController@get_gym_data');
        Route::post('saveGym', 'API\GymController@save_gym');
        Route::post('saveGymData', 'API\GymController@save_gym_data');
        Route::post('deleteGym', 'API\GymController@delete_gym');
    });
});


Route::group([
    'prefix' => 'passe'
        ], function () {
    Route::group([
        'middleware' => 'auth:api',
            ], function() {
        Route::post('getPasses', 'API\PassesController@get_passes');
        Route::post('savePasse', 'API\PassesController@save_passe');
        Route::post('activePasse', 'API\PassesController@active_passe');
        Route::post('deletePasse', 'API\PassesController@delete_passe');

    });
});


Route::group([
    'prefix' => 'typePasse'
        ], function () {
    Route::group([
        'middleware' => 'auth:api',
            ], function() {
        Route::post('getTypePasses', 'API\TypePasseController@get_type_passes');
        Route::post('saveTypePasse', 'API\TypePasseController@save_type_passe');
        Route::post('deleteTypePasse', 'API\TypePasseController@delete_type_passe');
    });
});


Route::group([
    'prefix' => 'service'
], function () {
    Route::group([
        'middleware' => 'auth:api',
    ], function() {
        Route::post('getServices', 'API\ServiceController@get_services');
        Route::post('saveService', 'API\ServiceController@save_service');
        Route::post('deleteService', 'API\ServiceController@delete_service');
    });
});

Route::group([
    'prefix' => 'user'
], function () {
    Route::group([
        'middleware' => 'auth:api',
    ], function() {
        Route::post('getUsers', 'API\UserController@get_users');
        Route::post('getPassesUsers/{id_user}', 'API\UserController@get_passes_users');

//        Route::post('saveService', 'API\ServiceController@save_service');
//        Route::post('deleteService', 'API\ServiceController@delete_service');
    });
});
Route::post('getUsers', 'API\UserController@get_users');
Route::post('getPassesUsers/{id_user}', 'API\UserController@get_passes_users');


Route::group([
    'prefix' => 'category'
        ], function () {
    Route::group([
        'middleware' => 'auth:api',
            ], function() {
        Route::post('getCategories', 'API\CategoryController@get_categories');
        Route::post('saveCategory', 'API\CategoryController@save_category');
        Route::post('deleteCategory', 'API\CategoryController@delete_category');
    });
});
Route::group([
    'prefix' => 'amenity'
], function () {
    Route::group([
        'middleware' => 'auth:api',
    ], function() {
        Route::post('getAmenities', 'API\AmenityController@get_amenities');
        Route::post('saveAmenity', 'API\AmenityController@save_amenity');
        Route::post('deleteAmenity', 'API\AmenityController@delete_amenity');
    });
});

Route::post('getAmenities', 'API\AmenityController@get_amenities');

Route::group([
    'prefix' => 'calendarActivity'
        ], function () {
    Route::group([
        'middleware' => 'auth:api',
            ], function() {
        Route::post('getCalendarActivities', 'API\CalendarActivityController@get_calendar_activities');
        Route::post('getCalendarActivitiesByBranch', 'API\CalendarActivityController@get_calendar_activities_by_branch');
        Route::post('saveCalendarActivity', 'API\CalendarActivityController@save_calendar_activity');
        Route::post('deleteCalendarActivity', 'API\CalendarActivityController@delete_calendar_activity');
        Route::post('setShowCalendarActivity', 'API\CalendarActivityController@set_show_calendar_activity');

    });
});


Route::group([
    'prefix' => 'reviews'
], function () {
    Route::group([
        'middleware' => 'auth:api',
    ], function() {
        Route::post('activeReview', 'API\ReviewGymBranchController@active_review');
    });
});



Route::group([
    'prefix' => 'front'
        ], function () {
    Route::get('getGymBranchs/{city}', 'API\EvaluationController@get_gym_branchs_front');
    Route::get('getGymBranchsByActivity/{activity}/{city}', 'API\EvaluationController@get_gym_branchs_by_activity_front');
    Route::post('getGymBranchsByFilter/', 'API\EvaluationController@get_gym_branchs_by_filter_front');
    Route::get('getGymBranchDetail/{id}', 'API\EvaluationController@get_gym_branch_data_front');
    Route::get('getHotDealPasses/{city}', 'API\PassesController@get_hot_deal_passes');
    Route::get('getSuggestionTags', 'API\SuggestionTagController@get_suggestion_tag');
    Route::post('getTagsTest', 'API\SuggestionTagController@get_test');

    Route::post('paymentTest', 'API\SuggestionTagController@payment_test');
    Route::post('paymentCards', 'API\SuggestionTagController@payment_cards');
    Route::post('executePayment', 'API\SuggestionTagController@payment_pay');
    Route::post('paymentDeleteCard', 'API\SuggestionTagController@delete_card');
    Route::post('getCardsByUser', 'API\SuggestionTagController@get_cards_by_user');
    
    
    Route::post('pagomediosApiEjemplos', 'API\SuggestionTagController@pagomediosApiEjemplos');

    Route::get('getCities', 'API\EvaluationController@get_cities');
    Route::get('getServices', 'API\EvaluationController@get_services');
    Route::get('getPassesbyGymBranch/{id}', 'API\PassesController@get_passes_gym_branch_validated_front');
    Route::get('getUserbyId/{id}', 'API\UserController@get_user_by_id');
    Route::get('getBlogsPaginated/{limit}/{offset}', 'API\BlogController@get_blogs_paginated');
    Route::get('getPassesUserPaginated/{id_user}/{limit}/{offset}', 'API\PassesUserController@get_user_passes');
    Route::post('saveUserPasse', 'API\PassesUserController@save_user_passe');
    Route::get('getGymBranchDetailLight/{id}', 'API\EvaluationController@get_gym_branch_data_front_light');
    // MOBILE API.
    Route::post('getNearGymBranchs', 'API\EvaluationController@get_near_gym_brachs');
    Route::post('getFilterGymBranchs', 'API\EvaluationController@get_app_filter_gym_brachs');

//    Route::post('postReviewGymBranch','API\ReviewGymBranchController@save_review');
    Route::post('getReviewsBranchs','API\EvaluationController@get_reviews');
    Route::post('deleteReviewsBranchs','API\EvaluationController@delete_review');
});

Route::post('postReviewGymBranch','API\ReviewGymBranchController@save_review');

Route::group([
    'prefix' => 'social'
        ], function () {
    Route::get('auth/{provider}', 'API\UserController@redirectToProvider');
    Route::get('auth/{provider}/callback', 'API\UserController@hanbleProviderCallback');
});


//testing ru
Route::get('gymUser/getGymUserProfile/{id}', 'API\UserController@get_gym_user_profile');
Route::put('gymUser/getGymUserProfile/{id}', 'API\UserController@update_user');
Route::delete('gymUser/getGymUserProfile/{id}', 'API\UserController@delete_gym_users');

Route::group([
    'prefix' => 'blog'
        ], function () {
    Route::group([
        'middleware' => 'auth:api',
            ], function() {
        Route::post('getBlogs', 'API\BlogController@get_blogs');
        Route::post('saveBlog', 'API\BlogController@save_blog');
        Route::post('deleteBlog', 'API\BlogController@delete_blog');
    });
});


Route::group([
    'prefix' => 'blog'
        ], function() {
    //routes to front client
    Route::get('getActivesBlogs', 'API\BlogController@get_actives_blogs');
    Route::get('getActivesBlog', 'API\BlogController@get_actives_blog');
    Route::get('getActivesBlog1', 'API\BlogController@get_actives_blog1');
    Route::get('getActivesBlog2', 'API\BlogController@get_actives_blog2');
    Route::get('getActivesBlog3', 'API\BlogController@get_actives_blog3');
    Route::post('activeBlog', 'API\BlogController@active_blog');
    //end routes to front client
});

Route::group([
    'prefix' => 'vacancies'
],function(){
    //Routes to Front Cliente
    Route::get('getActiveVacancies', 'API\VacantController@get_actives_vacancies');
    //End Routes Front Client 
    //Routes to Front Admin
    Route::get('getAllVacancies','API\VacantController@get_all_vacancies');
    Route::post('saveVacant','API\VacantController@set_vacant');
    Route::put('updateVacant/{id}','API\VacantController@update_vacant');
    Route::put('activeVacant/{id}/{action}','API\VacantController@active_vacant');
    Route::delete('deleteVacant/{id}','API\VacantController@delete_vacant');
    //End Routes to front admin
}
);

//Route::get('/mail','ServicesController@mail');

Route::post('sendEmail','API\ServicesController@contact');

