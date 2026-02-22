<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'newsletter', 'status', 'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'newsletter' => 'boolean',
        ];
    }

    public static function subjectOptions(): array
    {
        return [
            'news-tip' => 'News Tip',
            'complaint' => 'Complaint',
            'suggestion' => 'Suggestion',
            'feedback' => 'Feedback',
            'advertisement' => 'Advertisement',
            'career' => 'Career',
            'technical' => 'Technical Issue',
            'other' => 'Other',
        ];
    }
}
