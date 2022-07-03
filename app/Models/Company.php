<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    /**
     * The name of table mapped to the model.
     *
     * @var string
     */
    protected $table = 'company';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'is_active',
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
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'company_id');
    }
    
    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'company_id', 'id');
    }
    
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'company_id', 'id');
    }
    
    public function groupQuestions(): HasMany
    {
        return $this->hasMany(GroupQuestion::class, 'company_id', 'id');
    }
    
    public function news(): HasMany
    {
        return $this->hasMany(RecruitmentNews::class, 'company_id', 'id');
    }
}
