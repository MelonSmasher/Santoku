<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomizationSpec extends Model
{
    protected $fillable = [
        'name',
        'vm_template_id',
        'network_id',
        'vm_name_prefix',
        'node_name_postfix',
        'provision_command'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vmTemplate()
    {
        return $this->belongsTo(VmTemplate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deploySessions()
    {
        return $this->hasMany(DeploySession::class);
    }
}
