<?php

// File: app/Helpers/LogHelper.php
namespace App\Helpers;

use App\Models\ActivityLogModel;

class LogHelper
{
    /**
     * Log activity to file and/or database
     *
     * @param string $level - Log level (e.g., 'info', 'error')
     * @param string $message - Log message
     * @param array $data - Additional context (optional)
     */
    public static function logActivity(string $level, string $message, array $data = [])
    {
        // Log to file using CodeIgniter's log_message
        $context = !empty($data) ? json_encode($data) : '';
        log_message($level, $message . ' ' . $context);

        // Log to database
        $logModel = new ActivityLogModel();
        $logModel->insert([
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Log activity based on user role
     *
     * @param string $role - Role of the user (e.g., 'Admin', 'Pejabat', 'User')
     * @param string $message - Log message
     * @param array $data - Additional context (optional)
     */
    public static function logRoleActivity(string $role, string $message, array $data = [])
    {
        $data['role'] = $role; // Include role in context
        self::logActivity('info', "$role: $message", $data);
    }
}
