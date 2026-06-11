<?php

/**
 * Canonical database schema — single source of truth for table/column names.
 * All migrations, controllers, seeders, and views MUST use these names.
 *
 * NOT legacy ERD names (categories, ProVariant, order, user, stock_import…).
 */
return [

    'tables' => [
        'category' => [
            'id', 'name', 'slug', 'status', 'created_at', 'updated_at',
        ],
        'brand' => [
            'id', 'name', 'description', 'status', 'created_at', 'updated_at',
        ],
        'product' => [
            'id', 'category_id', 'brand_id', 'name', 'slug', 'images', 'banner',
            'description', 'detail', 'status', 'created_at', 'updated_at',
        ],
        'product_var' => [
            'id', 'product_id', 'color_id', 'size_id', 'material_id',
            'codeSKU', 'prices', 'stock', 'minQuantity', 'status', 'created_at', 'updated_at',
        ],
        'color' => [
            'id', 'name', 'hex', 'status', 'created_at', 'updated_at',
        ],
        'size' => [
            'id', 'name', 'status', 'created_at', 'updated_at',
        ],
        'material' => [
            'id', 'name', 'status', 'created_at', 'updated_at',
        ],
        'warehouse_import' => [
            'id', 'admin_id', 'created_at', 'updated_at',
        ],
        'warehouse_import_detail' => [
            'id', 'import_id', 'product_var_id', 'quantity', 'price', 'created_at', 'updated_at',
        ],
        'customer' => [
            'id', 'name', 'phone', 'email', 'address', 'password', 'secret_key',
            'status', 'created_at', 'updated_at',
        ],
        'orders' => [
            'id', 'customer_id', 'subtotal', 'discount', 'total',
            'order_status', 'payment_status', 'order_type', 'created_at', 'updated_at',
        ],
        'order_detail' => [
            'id', 'order_id', 'product_id', 'product_var_id', 'product_name',
            'quantity', 'price', 'discount', 'total_price', 'suborder_status',
            'created_at', 'updated_at',
        ],
        'admin' => [
            'id', 'secret_key', 'email', 'password', 'status', 'created_at', 'updated_at',
        ],
        'media' => [
            'id', 'name', 'path', 'mime_type', 'size', 'created_at', 'updated_at',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Column name notes (same table may use different semantic names)
    |--------------------------------------------------------------------------
    |
    | product_var.prices     → giá bán SKU (variant selling price)
    | order_detail.price     → giá tại thời điểm đặt hàng (order snapshot)
    | warehouse_import_detail.price → giá nhập kho (import unit cost)
    |
    | customer               → bảng khách hàng (KHÔNG dùng "user")
    | orders                 → bảng đơn hàng (KHÔNG dùng "order")
    | order_detail           → chi tiết đơn (KHÔNG dùng "orderDetail")
    | product_var            → biến thể SKU (KHÔNG dùng "ProVariant")
    | category               → danh mục (KHÔNG dùng "categories")
    | warehouse_import       → phiếu nhập kho (KHÔNG dùng "stock_import")
    */

    'aliases' => [
        'legacy' => [
            'categories' => 'category',
            'ProVariant' => 'product_var',
            'stock_import' => 'warehouse_import',
            'stock_import_detail' => 'warehouse_import_detail',
            'order' => 'orders',
            'orderDetail' => 'order_detail',
            'user' => 'customer',
        ],
    ],

];
