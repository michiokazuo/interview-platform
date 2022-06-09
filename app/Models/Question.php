<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    /**
     * The name of table mapped to the model.
     *
     * @var string
     */
    protected $table = 'questions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'content',
        'title',
        'others',
        'page_crawled'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'others' => 'array'
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(QuestionTag::class, 'question_tags', 'question_id', 'tag_id');
    }
    
    public function answers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class, 'question_id');
    }
}
