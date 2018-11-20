<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeploySession extends Model
{
    protected $fillable = [
        'customization_spec_id',
        'token'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customizationSpec()
    {
        return $this->belongsTo(CustomizationSpec::class);
    }

}
