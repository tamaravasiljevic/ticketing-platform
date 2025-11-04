<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use HasSlug;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';
    public const STATUS_EXPIRED = 'expired';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'start_time',
        'end_time',
        'min_tickets_per_customer',
        'max_tickets_per_customer',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public static array $allowedTransitions = [
        self::STATUS_DRAFT     => [self::STATUS_PENDING, self::STATUS_PUBLISHED],
        self::STATUS_PENDING   => [self::STATUS_PUBLISHED, self::STATUS_ARCHIVED],
        self::STATUS_PUBLISHED => [self::STATUS_ARCHIVED, self::STATUS_EXPIRED],
        self::STATUS_ARCHIVED  => [],
        self::STATUS_EXPIRED   => [],
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot Method
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::deleting(function (self $event) {
            if ($event->status === self::STATUS_PUBLISHED) {
                throw new Exception('Published events cannot be deleted.');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function occurrences(): HasMany
    {
        return $this->hasMany(EventOccurrence::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    public function canTransitionTo(string $newStatus): bool
    {
        $allowed = self::$allowedTransitions[$this->status] ?? [];
        return in_array($newStatus, $allowed, true);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function archive(): void
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            throw new \Exception('Only published events can be archived.');
        }

        $this->update(['status' => self::STATUS_ARCHIVED]);
    }

}
