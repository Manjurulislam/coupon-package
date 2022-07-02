<?php

return [
    'product_model'           => '\App\Models\Course',
    'product_primary_key'     => 'id',
    'product_display_column'  => 'name',
    'product_category_column' => 'course_category_id',
    'product_price_column'    => 'price',
    'category_model'          => '\App\Models\CourseCategory',
    'category_primary_key'    => 'id',
    'category_display_column' => 'name',
    'api_route_prefix'        => 'api',
    'api_auth_middleware'     => ['auth:sanctum'],
];
