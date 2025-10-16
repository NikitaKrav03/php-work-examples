<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataEntity extends Model
{
    use HasFactory;

    protected $table = 'data_entities';
    protected $guarded = [];

    public function getAmountAttribute($value) // Accessor для форматирования суммы
    {
        if ($value == null) return null;

        $formatted = number_format((float)$value, (str_contains($value, '.') ? 2 : 0), '.', '');

        if (str_contains($formatted, '.')) {
            $formatted = rtrim(rtrim($formatted, '0'), '.');
        }

        return preg_replace('/\B(?=(\d{3})+(?!\d))/', ' ', $formatted);
    }

    public function getAdditionalDataAttribute($value) // Accessor для JSON-данных
    {
        return json_decode($value, true);
    }

    public function statuses(): HasMany // Отношение со статусами
    {
        return $this->hasMany(Status::class);
    }

    public function scopeForUser($query, int $userId) // Scope для поиска по пользователю
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSearch($query, string $searchTerm) // Scope для поиска по нескольким полям
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('code', 'LIKE', "%{$searchTerm}%")
                ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('identifier', 'LIKE', "%{$searchTerm}%");
        });
    }

    public $timestamps = false;
}
