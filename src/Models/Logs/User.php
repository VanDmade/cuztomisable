<?php

namespace VanDmade\Cuztomisable\Models\Logs;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use VanDmade\Cuztomisable\Models\Users;
use Auth;

class User extends Model
{

    use HasFactory;

    protected $table = 'user_logs';

    protected $fillable = [
        'user_id',
        'description',
        'parameters',
        'created_by',
    ];

    protected $casts = [];

    protected $hidden = [
        'user_id',
        'created_by',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            $model->created_by = Auth::check() ? Auth::user()->id : null;
        });
    }

    public function parameters(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => json_decode($value) ? json_decode($value, true) : [],
            set: fn (array $value) => !is_null($value) ? json_encode($value) : null
        );
    }

    public function user()
    {
        return $this->belongsTo(Users\User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Users\User::class, 'created_by');
    }

}
