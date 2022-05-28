<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruitmentNews extends Model
{
    use HasFactory;

    /**
     * The name of table mapped to the model.
     *
     * @var string
     */
    protected $table = 'recruitment_news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'benefits',
        'requirements',
        'description',
        'start_time',
        'end_time',
        'project_id',
        'company_id',
        'salary',
        'job_position',
        'working_form',
        'gender',
        'experience',
        'workplace',
        'number_of_recruits'
    ];
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    
    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }
}
