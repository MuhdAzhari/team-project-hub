<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

final class AnalyticsFilters
{
    private const SESSION_KEY_ADMIN  = 'analytics.filters.admin';
    private const SESSION_KEY_MEMBER = 'analytics.filters.member';

    public static function get(): array
    {
        $user = auth()->user();

        $key = ($user?->role === 'admin') ? self::SESSION_KEY_ADMIN : self::SESSION_KEY_MEMBER;

        $defaults = [
            'range'      => '30',     // 7|30|90|custom
            'date_from'  => null,     // Y-m-d
            'date_to'    => null,     // Y-m-d
            'project_id' => null,     // nullable int
        ];

        $filters = array_merge($defaults, (array) Session::get($key, []));

        return self::normalize($filters);
    }

    public static function set(array $filters): void
    {
        $user = auth()->user();

        $key = ($user?->role === 'admin') ? self::SESSION_KEY_ADMIN : self::SESSION_KEY_MEMBER;

        Session::put($key, self::normalize($filters));
    }

    public static function normalize(array $filters): array
    {
        $range = (string) ($filters['range'] ?? '30');

        if (in_array($range, ['7', '30', '90'], true)) {
            $to = Carbon::today();
            $from = Carbon::today()->subDays(((int) $range) - 1);

            $filters['date_from'] = $from->toDateString();
            $filters['date_to']   = $to->toDateString();
        } elseif ($range === 'custom') {
            $filters['date_from'] = $filters['date_from'] ?: Carbon::today()->subDays(29)->toDateString();
            $filters['date_to']   = $filters['date_to'] ?: Carbon::today()->toDateString();
        } else {
            // fallback
            $filters['range'] = '30';
            return self::normalize($filters);
        }

        $filters['range'] = $range;

        $filters['project_id'] = $filters['project_id'] !== null && $filters['project_id'] !== ''
            ? (int) $filters['project_id']
            : null;

        return $filters;
    }
}
