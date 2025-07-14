<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rahlety App Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings specific to the Rahlety travel booking app.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Commission Settings
    |--------------------------------------------------------------------------
    |
    | Commission rate for agents and withdrawal settings.
    |
    */
    'commission_rate' => env('RAHLETY_COMMISSION_RATE', 0.05), // 5% default
    'min_withdrawal_amount' => env('RAHLETY_MIN_WITHDRAWAL', 100), // Minimum withdrawal amount
    
    /*
    |--------------------------------------------------------------------------
    | Booking Settings
    |--------------------------------------------------------------------------
    |
    | Settings related to booking policies and restrictions.
    |
    */
    'cancellation_hours' => env('RAHLETY_CANCELLATION_HOURS', 24), // Hours before trip for cancellation
    'max_booking_persons' => env('RAHLETY_MAX_BOOKING_PERSONS', 10), // Max persons per booking
    
    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    |
    | Settings for file uploads (images, etc.)
    |
    */
    'max_image_size' => env('RAHLETY_MAX_IMAGE_SIZE', 2048), // KB
    'allowed_image_types' => ['jpeg', 'png', 'jpg', 'gif'],
    
    /*
    |--------------------------------------------------------------------------
    | Review Settings
    |--------------------------------------------------------------------------
    |
    | Settings for the review system.
    |
    */
    'review_after_trip_hours' => env('RAHLETY_REVIEW_AFTER_HOURS', 2), // Hours after trip completion to allow reviews
    'max_review_days' => env('RAHLETY_MAX_REVIEW_DAYS', 30), // Max days after trip to allow reviews
];