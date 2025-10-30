<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Psy\Test\Fixtures\ImplicitUse\App\Traits\Sluggable;

class Event extends Model
{
    use Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_time',
        'end_time',
        'min_tickets_per_customer',
        'max_tickets_per_customer',
        ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title' // The field from which to generate the slug
            ]
        ];
    }
    public function occurrences() {
        return $this->hasMany(EventOccurrences::class);
    }

}
