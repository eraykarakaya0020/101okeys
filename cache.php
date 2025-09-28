<?php
// Basit cache sistemi
class SimpleCache {
    private static $cache = [];
    private static $cache_time = 300; // 5 dakika
    
    public static function get($key) {
        if (isset(self::$cache[$key])) {
            $data = self::$cache[$key];
            if (time() - $data['time'] < self::$cache_time) {
                return $data['data'];
            }
            unset(self::$cache[$key]);
        }
        return null;
    }
    
    public static function set($key, $data) {
        self::$cache[$key] = [
            'data' => $data,
            'time' => time()
        ];
    }
    
    public static function clear() {
        self::$cache = [];
    }
}
?>
