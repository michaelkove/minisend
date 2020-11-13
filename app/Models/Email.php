<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Email extends Model
{
    use HasFactory, Notifiable;


    /**
     * @var string[]
     */
    protected $fillable = [
        'subject',
        'body_html',
        'body_text',
        'user_id',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments(){
        return $this->hasMany(Attachment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipients(){
        return $this->belongsToMany(Recipient::class, 'emails_recipients')->withPivot(['status']);
    }



}
