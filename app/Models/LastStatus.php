<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LastStatus extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'BUSLASTSTATUS';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'RECID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $fillable = [];
}
