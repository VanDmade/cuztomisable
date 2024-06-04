<?php

namespace VanDmade\Cuztomisable\Models\Logs;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use VanDmade\Cuztomisable\Models\Users;
use Auth;

class Email extends Model
{

    use HasFactory;

    protected $table = 'email_logs';

    protected $fillable = [
        'user_id',
        'to',
        'cc',
        'bcc',
        'from',
        'subject',
        'template',
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
            if (Auth::check()) {
                $model->created_by = Auth::user()->id;
            }
        });
    }

    public function to(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => json_decode($value) ? json_decode($value, true) : [],
            set: fn (string|array $value) => !is_null($value) ? (is_array($value) ? json_encode($value) : $value) : null
        );
    }

    public function cc(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => json_decode($value) ? json_decode($value, true) : [],
            set: fn (string|array $value) => !is_null($value) ? (is_array($value) ? json_encode($value) : $value) : null
        );
    }

    public function bcc(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => json_decode($value) ? json_decode($value, true) : [],
            set: fn (string|array $value) => !is_null($value) ? (is_array($value) ? json_encode($value) : $value) : null
        );
    }

    public function parameters(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => json_decode($value) ? json_decode($value, true) : [],
            set: fn (string|array $value) => !is_null($value) ? (is_array($value) ? json_encode($value) : $value) : null
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