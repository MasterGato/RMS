<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne; // Correct import for HasOne

class EducationalAttainment extends Model
{
    use HasFactory;

    protected $fillable=[
        'Level',
        'Institution',
        'InclusiveDate'
    ];
    public function Applicants()
    {
        return $this->belongsTo(Applicant::class);
    }
}
