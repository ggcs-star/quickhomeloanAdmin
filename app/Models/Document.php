<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'documents';

    protected $fillable = [
        'loan_id',     // Mongo Loan _id
        'file_path',
        'status',
    ];

    protected $casts = [
        'loan_id' => 'string',
    ];

    /* =============================
       Relation (future ready)
    ============================== */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id', '_id');
    }
}
