<?php

namespace Manjurulislam\Coupon\Resource;
use Manjurulislam\Coupon\Facades\Coupon;

class CouponResource
{
    public function getCoupons($coupons)
    {
        $coupons->getCollection()->transform(function ($coupon) {
            $data                            = $coupon->only('id', 'label', 'code', 'discount_type', 'discount_amount', 'expire_date', 'status', 'coupon_applied_on', 'discount_type');
            $data['coupon_applied_on_label'] = Coupon::couponAppliedOnLabel($coupon->coupon_applied_on);
            $data['discount_type_label']     = Coupon::discountTypeLabel($coupon->discount_type);
            $data['product_category_id']     = data_get($coupon, 'product_category_id');
            $data['product_category_name']   = data_get($coupon, 'productCategory.' . config('coupon.category_display_column'));
            $data['expire_date']             = $coupon->expire_date->toDateTimeString();
            $data['active']                  = (bool)$coupon->status;
            return $data;
        });
        return $coupons;
    }

    public function getCoupon($coupon)
    {
        $coupon->load('products', 'productCategory');
        $data                            = $coupon->only('id', 'label', 'code', 'discount_type', 'discount_amount', 'expire_date', 'status', 'coupon_applied_on', 'discount_type');
        $data['coupon_applied_on_label'] = Coupon::couponAppliedOnLabel($coupon->coupon_applied_on);
        $data['discount_type_label']     = Coupon::discountTypeLabel($coupon->discount_type);
        $data['product_category_id']     = data_get($coupon, 'product_category_id');
        $data['product_category_name']   = data_get($coupon, 'productCategory.' . config('coupon.category_display_column'));
        $data['expire_date']             = $coupon->expire_date->toDateTimeString();
        $data['active']                  = (bool)$coupon->status;
        $data['products']                = $coupon->products->map(function ($product) {
            return $product->id;
        });
        $data['product_details']         = $coupon->products->map(function ($product) {
            return [
                'id'   => $product->id,
                'name' => $product->title,
            ];
        });
        return $data;
    }

    public function calculateDiscount($coupon, $product)
    {
        $productPrice = data_get($product, config('coupon.product_price_column'));
        if (Coupon::typePercentage($coupon->discount_type)) {
            $discountAmount = $productPrice * ($coupon->discount_amount / 100);
        } else {
            $discountAmount = $coupon->discount_amount;
        }

        return [
            'discount_type'    => Coupon::discountTypeLabel($coupon->discount_type),
            'applied_on'       => Coupon::couponAppliedOnLabel($coupon->coupon_applied_on),
            'discount_amount'  => $discountAmount,
            'discounted_price' => $productPrice - $discountAmount,
        ];
    }
}
