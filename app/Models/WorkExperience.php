<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkExperience extends Model
{

    use HasFactory;

    protected $fillable = [
        'ApplicantID',
        'Company',
        'Work',
        'Year'
    ];
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
