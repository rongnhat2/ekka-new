<?php

return [

    'tables' => [
        'categories' => [
            'cateID', 'cateName', 'slug', 'status', 'created_at', 'updated_at',
        ],
        'brand' => [
            'brandID', 'brandName', 'brandDesc', 'status', 'created_at', 'updated_at',
        ],
        'product' => [
            'proID', 'cateID', 'brandID', 'proName', 'proDesc', 'IMG', 'slug', 'banner',
            'detail', 'status', 'created_at', 'updated_at',
        ],
        'ProVariant' => [
            'proVarID', 'proID', 'colorID', 'sizeID', 'mateID',
            'codeSKU', 'price', 'stock', 'minQuantity', 'status', 'created_at', 'updated_at',
        ],
        'color' => [
            'colorID', 'colorValue', 'hex', 'status', 'created_at', 'updated_at',
        ],
        'size' => [
            'sizeID', 'sizeValue', 'status', 'created_at', 'updated_at',
        ],
        'material' => [
            'mateID', 'mateName', 'status', 'created_at', 'updated_at',
        ],
        'stock_import' => [
            'importID', 'adminID', 'improtDate', 'totalQuantity', 'note', 'created_at', 'updated_at',
        ],
        'stock_import_detail' => [
            'importDetailID', 'importID', 'proVarID', 'Quantity', 'unitPrice', 'created_at', 'updated_at',
        ],
        'user' => [
            'userID', 'userName', 'userPhone', 'userAddress', 'userEmail', 'userPass',
            'secret_key', 'status', 'created_at', 'updated_at',
        ],
        'order' => [
            'ordID', 'userID', 'ordDate', 'ordPhone', 'ordReceiver', 'ordAddress',
            'totalPrice', 'staValue', 'subtotal', 'discount', 'order_type', 'created_at', 'updated_at',
        ],
        'orderDetail' => [
            'ordDetailID', 'ordID', 'proID', 'proVarID', 'proName',
            'quantity', 'basePrice', 'salePrice', 'discount', 'suborder_status',
            'created_at', 'updated_at',
        ],
        'payment' => [
            'payID', 'ordID', 'payDate', 'payStatus', 'amount', 'payMethod', 'created_at', 'updated_at',
        ],
        'admin' => [
            'adminID', 'adminName', 'adminPhone', 'adminAddress', 'adminEmail', 'adminPass',
            'secret_key', 'status', 'created_at', 'updated_at',
        ],
        'media' => [
            'id', 'name', 'path', 'mime_type', 'size', 'created_at', 'updated_at',
        ],
    ],

];
