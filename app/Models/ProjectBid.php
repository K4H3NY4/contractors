<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBid extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'user_id', 'bid_amount', 'status'];

    // Define the relationship with the Project model
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
