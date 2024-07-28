<?php

namespace App\Helpers;

use App\Enums\App;

abstract class ListHelper
{

    public static function setListFields( array $filters, array $inputs ) : array
    {
        foreach( $inputs as $input )
        {
            $filters[ $input ] = $filters[ $input ] ?? '';
        }
        $filters[ 'trashed' ] = $filters[ 'trashed' ] ?? null;
        $filters = self::setListOptions( $filters );
        return $filters;
    }

    public static function setListOptions( array $filters ) : array
    {
        $filters[ App::DEFAULT_LIST_ORDER_FIELD_KEY ] = $filters[ App::DEFAULT_LIST_ORDER_FIELD_KEY ] ?? null;
		$filters[ App::DEFAULT_LIST_ORDER_MODE_KEY ] = $filters[ App::DEFAULT_LIST_ORDER_MODE_KEY ] ?? null;
		$filters[ App::DEFAULT_LIST_REGS_PER_PAGE_KEY ] = $filters[ App::DEFAULT_LIST_REGS_PER_PAGE_KEY ] ?? null;
        return $filters;
    }

    public static function perPage( $perPage, $default = App::DEFAULT_LIST_REGS_PER_PAGE ) : int
    {
        return $perPage ?? $default;
    }

    public static function orderField( $orderField, $default = App::DEFAULT_LIST_ORDER_FIELD ) : string
    {
        return $orderField ?? $default;
    }

    public static function orderMode( $orderMode, $default = App::DEFAULT_LIST_ORDER_MODE ) : string
    {
        return $orderMode ?? $default;
    }
}
