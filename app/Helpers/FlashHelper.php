<?php

namespace App\Helpers;

class FlashHelper
{
    /**
     * Get all flash messages as an array
     * 
     * @return array<string, string>
     */
    public static function getAll(): array
    {
        $messages = [];
        
        $types = ['success', 'error', 'warning', 'info', 'message'];
        
        foreach ($types as $type) {
            if (session()->has($type)) {
                $messages[$type] = session($type);
            }
        }
        
        return $messages;
    }

    /**
     * Check if any flash message exists
     * 
     * @return bool
     */
    public static function hasAny(): bool
    {
        return session()->has('success') 
            || session()->has('error') 
            || session()->has('warning') 
            || session()->has('info') 
            || session()->has('message');
    }
}

