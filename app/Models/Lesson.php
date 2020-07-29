<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Lesson
 * @package App\Models
 * @property-read Slide[] slides
 * @property string name
 * @property string description
 */
class Lesson extends Model
{
    /**
     * @var array
     */
    public static $formFields = [
        'name' => [
            'label' => 'Lesson Name',
            'required' => true,
        ],
        'description' => [
            'label' => 'Lesson Description',
            'required' => true,
            'type' => 'textarea',
        ],
    ];

    protected $with = ['students', 'slides'];

    protected $fillable = ['course_id', 'name', 'description', 'position'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sorted', function (Builder $builder) {
            $builder->orderBy('position');
        });
    }

    public function students()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['completed_at', 'answers']);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function rawSlides()
    {
        return $this->hasMany(Slide::class);
    }

    public function slides()
    {
        return $this->rawSlides()
            ->orderBy('position');
    }

    public function getVideoCountAttribute(): int
    {
        return 0;
    }
}
