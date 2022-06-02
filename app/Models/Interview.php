<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Interview extends Model
{  
    use HasFactory;

    /**
     * The name of table mapped to the model.
     *
     * @var string
     */
    protected $table = 'interview';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'record',
        'result',
        'address',
        'form',
        'time',
        'candidate_id',
        'company_id',
        'news_id'
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
    
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    
    public function news(): BelongsTo
    {
        return $this->belongsTo(RecruitmentNews::class);
    }
    
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'interview_has_questions', 'interview_id', 'question_id');
    }
}
