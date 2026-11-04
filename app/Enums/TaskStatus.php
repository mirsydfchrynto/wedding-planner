<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Done = 'done';

    public function label(): string
    {
        return match ($this) {
            self::Todo => 'Belum Dimulai',
            self::InProgress => 'Sedang Berjalan',
            self::Done => 'Selesai',
        };
    }

    public function badgeTone(): string
    {
        return match ($this) {
            self::Todo => 'neutral',
            self::InProgress => 'ochre',
            self::Done => 'sage',
        };
    }
}
