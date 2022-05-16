<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CVDetail extends Model
{
    use HasFactory;

    /**
     * The name of table mapped to the model.
     *
     * @var string
     */
    protected $table = 'cv_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cv_id',
        'candidate_id',
        'key',
        'value',
    ];

    public function cv(): BelongsTo
    {
        return $this->belongsTo(CV::class, 'cv_id', 'id');
    }
    
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
