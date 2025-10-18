<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Activity extends Model
{
    protected $fillable = ['name', 'description', 'user_id'];

    public function updates()
    {
        return $this->hasMany(ActivityUpdate::class);
    }

    public function latestUpdate($date)
    {
        return $this->updates()->where('date', $date)->latest()->first();
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}