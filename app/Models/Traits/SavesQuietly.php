<?php

namespace App\Models\Traits;

trait SavesQuietly
{
    /**
     * Сохраняет модель без запуска событий
     *
     * @param  array  $options
     * @return bool
     */
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
} 