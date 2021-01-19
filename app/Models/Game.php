<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date_added'];

    /**
     * Game has many versions relation
     * 
     * @return mixed
     */
    public function versions()
    {
        return $this->belongsToMany(Version::class, 'game_versions')->withTimestamps();
    }
}
