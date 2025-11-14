<?php
/**
 * View Helper
 * Utilities for rendering views
 */

class ViewHelper {
    /**
     * Format date
     */
    public static function formatDate($date, $format = 'Y-m-d H:i:s') {
        if (empty($date)) return '';
        return date($format, strtotime($date));
    }
    
    /**
     * Format currency
     */
    public static function formatCurrency($amount, $currency = 'KES') {
        return $currency . ' ' . number_format($amount, 2);
    }
    
    /**
     * Format time
     */
    public static function formatTime($time) {
        if (empty($time)) return '';
        return $time; // Could parse MM:SS format
    }
    
    /**
     * Get gender label
     */
    public static function genderLabel($gender) {
        $labels = [
            'M' => 'Male',
            'F' => 'Female',
            'mixed' => 'Mixed'
        ];
        return $labels[$gender] ?? $gender;
    }
    
    /**
     * Get status badge
     */
    public static function statusBadge($status) {
        $badges = [
            'completed' => '<span class="badge badge-success">Completed</span>',
            'dnf' => '<span class="badge badge-warning">DNF</span>',
            'dq' => '<span class="badge badge-danger">DQ</span>',
            'upcoming' => '<span class="badge badge-info">Upcoming</span>',
            'ongoing' => '<span class="badge badge-primary">Ongoing</span>'
        ];
        return $badges[$status] ?? '<span class="badge badge-secondary">' . $status . '</span>';
    }
    
    /**
     * Truncate string
     */
    public static function truncate($string, $length = 50) {
        if (strlen($string) <= $length) {
            return $string;
        }
        return substr($string, 0, $length) . '...';
    }
    
    /**
     * Active class
     */
    public static function activeClass($current, $target) {
        return $current === $target ? 'active' : '';
    }
    
    /**
     * Format position with suffix
     */
    public static function ordinal($number) {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            $suffix = 'th';
        } else {
            $suffix = $ends[$number % 10];
        }
        return $number . $suffix;
    }
    
    /**
     * Round to decimal places
     */
    public static function decimal($value, $places = 2) {
        return round($value, $places);
    }
}
