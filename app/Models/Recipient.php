<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable =[
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function emails(){
        return $this->belongsToMany(Email::class,'emails_recipients')->withPivot(['status']);
    }

}
