<?php

namespace VanDmade\Cuztomisable\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Registration extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'user_registrations';

    protected $fillable = [
        'email',
        'phone',
        'code',
        'used_at',
        'user_id',
        'expires_at',
        'created_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'code',
        'user_id',
        'created_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            if (is_null($this->expires_at)) {
                $seconds = config('cuztomisable.account.registration.expires_in', 300);
                $model->expires_at = date('Y-m-d H:i:s', strtotime('+'.$seconds.' seconds'));
            }
            $model->created_by = Auth::user()->id;
        });
        self::created(function($model) {
            if (is_null($this->code)) {
                $model->code = generateCode(
                    config('cuztomisable.account.registration.length', 6),
                    'cuztomisable',
                    $this->id
                );
                $model->save();
            }
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

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

}
