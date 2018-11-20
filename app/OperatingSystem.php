<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperatingSystem extends Model
{
    protected $fillable = [
        'name',
        'version',
        'codename',
        'platform',
        'logo'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vmTemplate()
    {
        return $this->hasMany(VmTemplate::class);
    }
}
