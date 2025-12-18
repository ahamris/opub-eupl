<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Trait for handling image retrieval with fallback options.
 *
 * Provides a flexible way to get image URLs with various fallback options.
 * Supports local storage, external URLs, and direct asset paths.
 */
trait ImageGetterTrait
{
    /**
     * Get the image URL with fallback options.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string|null, string>
     */
    public function getImage(): Attribute
    {
        return Attribute::get(function (): ?string {
            $image = $this->image ?? $this->cover_image;

            if (empty($image)) {
                return null;
            }

            if (Str::isUrl($image) || Str::startsWith($image, ['http://', 'https://'])) {
                return $image;
            }

            if (Str::startsWith($image, 'storage/')) {
                return Storage::url($image);
            }

            return asset($image);
        });
    }

    /**
     * Get the image URL (method version for direct calling).
     *
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        $image = $this->image ?? $this->cover_image ?? null;

        if (empty($image)) {
            return null;
        }

        if (Str::isUrl($image) || Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }

        if (Str::startsWith($image, 'storage/')) {
            return Storage::url($image);
        }

        return asset($image);
    }

    public function getAvatar(): Attribute
    {

        if (!auth()->check()) {
            return Attribute::get(
                fn () => 'https://ui-avatars.com/api/?name='.str_replace(' ', '+', $this->full_name).'&background=154273&color=f1f1f1&size=150'
            );
        }

        $avatar = 'https://ui-avatars.com/api/?name='.str_replace(' ', '+', $this->full_name).'&background=154273&color=f1f1f1&size=150';

        if (! empty($this->avatar)) {
            $avatar = asset('storage/'.$this->avatar);
        }

        return Attribute::get(
            fn () => $avatar
        );

    }
}
