<?php

namespace Manjurulislam\Coupon\Repository;

use Manjurulislam\Coupon\Services\CouponService;

class CouponRepository
{
    public function getCouponByCode($couponCode)
    {
        return app(CouponService::class)->getCouponByCode($couponCode);

    }

    public function applyCouponToProduct($coupon, $productId)
    {
        return app(CouponService::class)->checkValidity($coupon, $productId);
    }

    public function details($couponCode): array
    {
        return [];
    }

    public function couponAppliedOnLabel($data)
    {

        return data_get(collect($this->couponAppliedOnValues())->where('value', $data)->first(), 'label', 'N/A');
    }

    public function discountTypeLabel($data)
    {
        return data_get(collect($this->discountTypeValues())->where('value', $data)->first(), 'label', 'N/A');

    }


    public function couponAppliedOnValues(): array
    {
        return app(CouponService::class)->getCouponAppliedOn();

    }

    public function discountTypeValues(): array
    {
        return app(CouponService::class)->getCouponDiscountTypes();
    }

    public function typePercentage($data)
    {
        return data_get(collect($this->discountTypeValues())->where('value', $data)->first(), 'key', 'N/A') === 'percentage';

    }
}
