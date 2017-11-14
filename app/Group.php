<?php

namespace App;

use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Group
 *
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Illuminate\Support\Collection $members
 */
class Group extends Model
{
    protected $fillable = [
        'name'
    ];

    public function members(): Relations\HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function setNameAttribute(string $value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
