<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relasi: log milik 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi polymorphic ke model terkait
    public function subject()
    {
        return $this->morphTo('model');
    }

    // Helper: catat aktivitas
    public static function log(
        string $action,
        ?Model $model = null,
        string $description = '',
        array $oldValues = [],
        array $newValues = []
    ): self {
        return self::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model?->id,
            'description' => $description,
            'old_values'  => $oldValues ?: null,
            'new_values'  => $newValues ?: null,
            'ip_address'  => request()->ip(),
        ]);
    }
}
