<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLogo extends Model
{
    use HasFactory;
    protected $appends = ['logo_url'];

    public function getImagePathAttribute()
    {
        if ($this->logo)
        {
            return $this->logo;
        } else {
            return 'uploads/default/no-image-found.png';
        }
    }
   
    public function getLogoUrlAttribute()
    {
        if ($this->logo)
        {
            return asset($this->logo);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }
}
