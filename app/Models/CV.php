<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CV extends Model
{
    use HasFactory;

    /**
     * The name of table mapped to the model.
     *
     * @var string
     */
    protected $table = 'cv';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'link',
        'detail',
        'candidate_id'
    ];

    public function cvDetail(): HasMany
    {
        return $this->hasMany(CVDetail::class, 'cv_id', 'id');
    }
    
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
