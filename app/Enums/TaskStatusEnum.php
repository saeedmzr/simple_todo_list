<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case CREATED = 'created';
    case COMPLETED = 'completed';
    case SYSTEM_COMPLETED = 'system-completed';

    /**
     * Get all status values as an array.
     *
     * @return array
     */
    public static function all(): array
    {
        return array_map(fn(self $status) => $status->value, self::cases());
    }

    public static function getDefaultStatus(): self
    {
        return self::CREATED;
    }
}
