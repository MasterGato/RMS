<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;


    protected $fillable=[
        'Firstname',
        'Lastname',
        'Middlename',
        'Gender',
        'Contact',
        'Email',
        'Dateofbirth',
        'Citizenship',
        'Region',
        'Province',
        'City',
        'Brgy',
        'Zipcode',
        'Username',
        'Password',
        'Status'
    ];

    public function workExperiences(){
        return $this->hasMany(WorkExperience::class);
    }


    public function education(){
        return $this->hasMany(EducationalAttainment::class);
    }


}
