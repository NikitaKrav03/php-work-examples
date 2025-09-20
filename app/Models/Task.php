<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELED = 3;

    protected $attributes = [
        'status' => '0'
    ];

    protected $fillable = ['title', 'description', 'status'];
    public function getStatusAttribute($value)
    {
        $statuses = [
            self::STATUS_PENDING => 'pending',
            self::STATUS_IN_PROGRESS => 'in_progress',
            self::STATUS_COMPLETED => 'completed',
            self::STATUS_CANCELED => 'canceled',
        ];

        return $statuses[$value] ?? 'unknown';
    }

    public function setStatusAttribute($value)
    {
        if (is_string($value)) {
            $statuses = [
                'pending' => self::STATUS_PENDING,
                'in_progress' => self::STATUS_IN_PROGRESS,
                'completed' => self::STATUS_COMPLETED,
                'canceled' => self::STATUS_CANCELED,
            ];

            $this->attributes['status'] = $statuses[$value] ?? self::STATUS_PENDING;
        } else {
            $this->attributes['status'] = $value;
        }
    }

    public function getStatusValue()
    {
        return $this->getAttributes()['status'] ?? self::STATUS_PENDING;
    }

    public static function parseStatus($statusText)
    {
        $statuses = [
            'pending' => self::STATUS_PENDING,
            'in_progress' => self::STATUS_IN_PROGRESS,
            'completed' => self::STATUS_COMPLETED,
            'canceled' => self::STATUS_CANCELED,
        ];

        return $statuses[$statusText] ?? self::STATUS_PENDING;
    }
}
