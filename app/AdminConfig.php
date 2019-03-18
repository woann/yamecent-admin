<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminConfig extends Model
{
    protected $fillable = ['name', 'config_key', 'config_value', 'type'];
    //
    public static function getValue($key)
    {
        $instance = new static;
        if (is_array($key)) {
            $result = $instance->whereIn('config_key', $key)->get();
            if ($result) {
                $data = $result->flatMap(function ($config) {
                    return [$config->config_key => $config->config_value];
                })->toArray();
            } else {
                $data = [];
            }
            return $data;
        } else {
            $result = $instance->where('config_key', $key)->first();
            if (!$result) {
                return null;
            }
            return $result->config_value;
        }
    }

    public function scopeSearchCondition($query, string $keyword = null)
    {
        if (is_null($keyword)) {
            return $query;
        } else {
            return $query->where("config_key", "like", "%{$keyword}%")
                ->orWhere("name", "like", "%{$keyword}%");
        }
    }
}
