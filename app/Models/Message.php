<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'content', 'type', 'file_path'];

    protected function casts(): array
    {
        return [
            // Mengenkripsi pesan secara otomatis di level database
            'content' => 'encrypted',
        ];
    }
}
