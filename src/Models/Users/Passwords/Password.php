<?php

namespace VanDmade\Cuztomisable\Models\Users\Passwords;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use VanDmade\Cuztomisable\Models\Users\User;
use Auth;

class Password extends Model
{

    use HasFactory;

    protected $table = 'user_passwords';

    protected $fillable = [
        'password',
        'user_id',
        'created_by',
    ];

    protected $casts = [];

    protected $hidden = [
        'password',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
