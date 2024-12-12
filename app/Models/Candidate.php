<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'user_id',
        'job_id',
        'cv',
        'experience',
        'skills',
        'phone',
        'date_of_birth',
        'status',
        'category'
    ];

    // Cast date fields to date format
    protected $casts = [
        'date_of_birth' => 'date',
        'is_process' => 'int',
    ];




    // Relationship with User (assuming you have a User model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Optionally, you can add a relationship to the Job model if required
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    // Relationship with Applications
    public function applications()
    {
        return $this->hasMany(Application::class , 'candidate_id');
    }

    // Get all jobs through applications
    public function jobs()
    {
        return $this->hasManyThrough(Job::class, Application::class, 'candidate_id', 'id', 'id', 'job_id');
    }



}
