<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentProcess extends Model
{
    use HasFactory;

    /**
     * The name of table mapped to the model.
     *
     * @var string
     */
    protected $table = 'recruitment_process';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'project_id',
        'prev_step',
        'next_step',
    ];
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    
    public function prevStep(): BelongsTo
    {
        return $this->belongsTo(RecruitmentProcess::class, 'prev_step');
    }
    
    public function nextStep(): BelongsTo
    {
        return $this->belongsTo(RecruitmentProcess::class, 'next_step');
    }
}
