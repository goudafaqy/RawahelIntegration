<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EMP extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'EmplTable';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'EMPLID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $fillable = [];
}
