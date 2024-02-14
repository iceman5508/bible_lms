<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsGeneral extends Model
{
    use HasFactory;
    
    protected $appends = ['upgrade_skill_logo_url', 'team_member_logo_url', 'gallery_second_image_url', 'gallery_third_image_url', 'gallery_first_image_url'];

    public function getUpgradeSkillLogoPathAttribute()
    {
        if ($this->upgrade_skill_logo) {
            return $this->upgrade_skill_logo;
        } else {
            return 'uploads/default/no-image-found.png';
        }
    }

    public function getTeamMemberLogoPathAttribute()
    {
        if ($this->team_member_logo) {
            return $this->team_member_logo;
        } else {
            return 'uploads/default/no-image-found.png';
        }
    }
    
    public function getUpgradeSkillLogoUrlAttribute()
    {
        if ($this->upgrade_skill_logo) {
            return asset($this->upgrade_skill_logo);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }

    public function getTeamMemberLogoUrlAttribute()
    {
        if ($this->team_member_logo) {
            return asset($this->team_member_logo);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }
   
    public function getGalleryFirstImageUrlAttribute()
    {
        if ($this->gallery_third_image) {
            return asset($this->gallery_third_image);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }
   
    public function getGalleryThirdImageUrlAttribute()
    {
        if ($this->gallery_third_image) {
            return asset($this->gallery_third_image);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }

    public function getGallerySecondImageUrlAttribute()
    {
        if ($this->gallery_second_image) {
            return asset($this->gallery_second_image);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }
}
