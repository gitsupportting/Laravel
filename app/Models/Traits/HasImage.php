<?php


namespace App\Models;


use Spatie\MediaLibrary\Models\Media;

trait HasImage
{
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->crop('crop-center', 343, 215);
        $this->addMediaConversion('preview')
              ->width(1468);
    }

    public function getImageAttribute(): ?Media
    {
        return $this->getFirstMedia();
    }

    public function getImageUrlAttribute(): ?string
    {
        return optional($this->image)->getUrl();
    }

    public function getImageThumbUrlAttribute(): ?string
    {
        return optional($this->image)->getUrl('thumb');
    }

    public function getImagePreviewUrlAttribute(): ?string
    {
        return optional($this->image)->getUrl('preview');
    }
}
