<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $fillable = [
        'name',
        'subnet_mask',
        'dhcp'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customizationSpec()
    {
        return $this->hasMany(CustomizationSpec::class);
    }
}
