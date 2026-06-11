<?php

namespace App\Support;

final class DbSchema
{
    public const CATEGORIES = 'categories';
    public const BRAND = 'brand';
    public const PRODUCT = 'product';
    public const PRO_VARIANT = 'ProVariant';
    public const COLOR = 'color';
    public const SIZE = 'size';
    public const MATERIAL = 'material';
    public const STOCK_IMPORT = 'stock_import';
    public const STOCK_IMPORT_DETAIL = 'stock_import_detail';
    public const USER = 'user';
    public const ORDER = 'order';
    public const ORDER_DETAIL = 'orderDetail';
    public const PAYMENT = 'payment';
    public const ADMIN = 'admin';
    public const MEDIA = 'media';

    public static function table(string $name): string
    {
        $tables = config('schema.tables', []);
        if (!isset($tables[$name])) {
            throw new \InvalidArgumentException("Unknown table: {$name}");
        }

        return $name;
    }

    public static function columns(string $table): array
    {
        $columns = config("schema.tables.{$table}");

        if ($columns === null) {
            throw new \InvalidArgumentException("Unknown table: {$table}");
        }

        return $columns;
    }
}
