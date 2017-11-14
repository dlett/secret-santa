<?php

namespace App;

use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Member
 *
 * @package App
 *
 * @property int            $id
 * @property int            $group_id
 * @property int            $connection_id
 * @property bool           $has_seen_connection
 * @property string         $slug
 * @property string         $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Member extends Model
{
    protected $fillable = [
        'name',
        'has_seen_connection'
    ];

    public function group(): Relations\BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function connection(): Relations\BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function connected(): Relations\HasOne
    {
        return $this->hasOne(Member::class, 'connection_id');
    }

    public function unconnectables(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_unconnectables', 'member_id', 'unconnectable_id');
    }

    public function setNameAttribute(string $value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
