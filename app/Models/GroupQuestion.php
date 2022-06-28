<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GroupQuestion extends Model
{
    use HasFactory;

    /**
     * The name of table mapped to the model.
     *
     * @var string
     */
    protected $table = 'group_questions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'topics',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'result' => 'array',
        'record' => 'array',
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'group_has_questions', 'group_id', 'question_id');
    }
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    
}
