<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VmTemplate extends Model
{
    protected $fillable = [
        'name',
        'vm_template_name',
        'operating_system_id',
        'image_url'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operatingSystem()
    {
        return $this->belongsTo(OperatingSystem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customizationSpec()
    {
        return $this->hasMany(CustomizationSpec::class);
    }
}
