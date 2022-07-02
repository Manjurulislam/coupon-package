<?php

use Manjurulislam\Coupon\Http\Controllers\CouponController;


Route::get('/test', function () {
    return 'hello world';
});


Route::prefix(config('coupon.api_route_prefix'))->group(function () {
    Route::controller(CouponController::class)
        ->prefix('coupons')->group(function () {
            Route::get('/', 'getList');
            Route::post('/create', 'store');
            Route::post('/{coupon}/update', 'update');
            Route::get('/{coupon}/details', 'details');
            Route::get('/search', 'search');
        });
});
