<?php

namespace Manjurulislam\Coupon\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Manjurulislam\Coupon\Models\Coupon;
use Manjurulislam\Coupon\Services\ApiResponse;
use Manjurulislam\Coupon\Services\CouponService;
use Manjurulislam\Coupon\Resource\CouponResource;


class CouponController extends Controller
{
    private CouponService  $couponService;
    private CouponResource $couponResource;
    private ApiResponse    $apiResponse;

    public function __construct()
    {
        $this->couponService  = new CouponService();
        $this->couponResource = new CouponResource();
        $this->apiResponse    = new ApiResponse();
        $this->middleware(config('coupon.api_auth_middleware'));
    }


    public function getList(Request $request): JsonResponse
    {
        try {
            $data = $this->couponResource->getCoupons($this->couponService->getCoupons($request));
            return $this->apiResponse->successResponse($data);
        } catch (Exception $e) {
            Log::error('Exception coupon', [$e->getMessage()]);
            return $this->apiResponse->errorResponse('Something went wrong');
        }
    }


    /**
     * @throws \Throwable
     */
    public function store(Request $request) : JsonResponse
    {
        $validation = $this->couponService->validateCoupon($request);

        if ($validation->fails()) {
            return $this->apiResponse->validationErrorResponse($validation->errors()->toArray());
        }

        try {
            $coupon = $this->couponService->createCoupon($request);
            return $this->apiResponse->successResponse($this->couponResource->getCoupons($coupon));
        } catch (Exception $e) {
            Log::error('Exception coupon create', [$e->getMessage()]);
            return $this->apiResponse->errorResponse('Something went wrong');
        }
    }


    public function details($couponId) : JsonResponse
    {
        if (blank($coupon = Coupon::find($couponId))) {
            return $this->apiResponse->errorResponse('Coupon not found');
        }

        try {
            return $this->apiResponse->successResponse($this->couponResource->getCoupons($coupon));
        } catch (Exception $e) {
            Log::error('Exception coupon details', [$e->getMessage()]);
            return $this->apiResponse->errorResponse('Something went wrong');
        }
    }

    public function update($couponId, Request $request) : JsonResponse
    {
        if (blank($coupon = Coupon::find($couponId))) {
            return $this->apiResponse->errorResponse('Coupon not found');
        }

        $validation = $this->couponService->validateCoupon($request, $coupon->id);
        if ($validation->fails()) {
            return $this->apiResponse->validationErrorResponse($validation->errors()->toArray());
        }

        try {
            $coupon = $this->couponService->updateCoupon($request, $coupon);
            return $this->apiResponse->successResponse($this->couponResource->getCoupons($coupon));
        } catch (Exception $e) {
            Log::error('Exception coupon update', [$e->getMessage()]);
            return $this->apiResponse->errorResponse('Something went wrong');
        }
    }

    public function getSearchParameters()
    {
        return $this->apiResponse->successResponse($this->couponService->getSearchParams());
    }


}
