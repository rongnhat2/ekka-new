<?php

namespace App\Support;

final class DbSchema
{
    public const CATEGORY = 'category';
    public const BRAND = 'brand';
    public const PRODUCT = 'product';
    public const PRODUCT_VAR = 'product_var';
    public const COLOR = 'color';
    public const SIZE = 'size';
    public const MATERIAL = 'material';
    public const WAREHOUSE_IMPORT = 'warehouse_import';
    public const WAREHOUSE_IMPORT_DETAIL = 'warehouse_import_detail';
    public const CUSTOMER = 'customer';
    public const ORDERS = 'orders';
    public const ORDER_DETAIL = 'order_detail';
    public const ADMIN = 'admin';
    public const MEDIA = 'media';

    public static function table(string $name): string
    {
        $legacy = config('schema.aliases.legacy', []);
        if (isset($legacy[$name])) {
            return $legacy[$name];
        }

        $tables = config('schema.tables', []);
        if (!isset($tables[$name])) {
            throw new \InvalidArgumentException("Unknown table: {$name}");
        }

        return $name;
    }

    public static function columns(string $table): array
    {
        $table = self::table($table);
        $columns = config("schema.tables.{$table}");

        if ($columns === null) {
            throw new \InvalidArgumentException("Unknown table: {$table}");
        }

        return $columns;
    }

    public static function hasColumn(string $table, string $column): bool
    {
        return in_array($column, self::columns($table), true);
    }
}
