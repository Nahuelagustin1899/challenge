<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static DEFAULT_LIST_REGS_PER_PAGE
 * @method static static DEFAULT_LIST_ORDER_FIELD
 * @method static static DEFAULT_LIST_ORDER_MODE
 */
final class App extends Enum
{
    // List
    const DEFAULT_LIST_REGS_PER_PAGE = 10;
    const DEFAULT_LIST_ORDER_FIELD = 'id';
    const DEFAULT_LIST_ORDER_MODE = 'ASC';
    const DEFAULT_LIST_REGS_PER_PAGE_KEY = 'list_regs_per_page';
    const DEFAULT_LIST_ORDER_FIELD_KEY = 'list_order_field';
    const DEFAULT_LIST_ORDER_MODE_KEY = 'list_order_mode';
    const DEFAULT_LIST_REQUEST_RULES = [
        'list_order_field' => [ 'nullable', 'string', 'max:255' ],
        'list_order_mode' => [ 'nullable', 'string' ],
        'list_regs_per_page' => [ 'nullable', 'string' ],
        'trashed' => [ 'nullable', 'boolean' ],
    ];

    // Status
    const STATUS_AVAILABLE = 1;
    const STATUS_CANCELED = 2;
    const STATUS_RESERVED = 3;
}
