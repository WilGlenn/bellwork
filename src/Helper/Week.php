<?php
namespace App\Helper;

class Week {
    // Returns Y-m-d (Monday of current week, Central Time)
    public static function currentWeekStart(): string {
        $dt = new \DateTime('now', new \DateTimeZone('America/Chicago'));
        $dt->modify('Monday this week');
        return $dt->format('Y-m-d');
    }
}
