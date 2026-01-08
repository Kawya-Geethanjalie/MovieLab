<?php
// include/functions.php

/**
 * Database Backup Function
 */
function createDatabaseBackup($pdo, $backupDir = '../backups/') {
    // Create backup directory if not exists
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d_H-i-s');
    $backupFile = $backDir . 'movielab_backup_' . $timestamp . '.sql';
    
    // Get all tables
    $tables = [];
    $result = $pdo->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    
    $backupContent = "-- Movie Lab Database Backup\n";
    $backupContent .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $backupContent .= "-- Database: movielab\n\n";
    
    foreach ($tables as $table) {
        // Table structure
        $backupContent .= "--\n-- Table structure for table `$table`\n--\n";
        $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_NUM);
        $backupContent .= $createTable[1] . ";\n\n";
        
        // Table data
        $backupContent .= "--\n-- Dumping data for table `$table`\n--\n";
        
        $rows = $pdo->query("SELECT * FROM `$table`");
        $rowCount = 0;
        
        while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
            if ($rowCount == 0) {
                $backupContent .= "INSERT INTO `$table` VALUES \n";
            } else {
                $backupContent .= ",\n";
            }
            
            $values = array_map(function($value) use ($pdo) {
                if ($value === null) {
                    return 'NULL';
                }
                return $pdo->quote($value);
            }, array_values($row));
            
            $backupContent .= "(" . implode(", ", $values) . ")";
            $rowCount++;
        }
        
        if ($rowCount > 0) {
            $backupContent .= ";\n\n";
        }
    }
    
    // Save to file
    if (file_put_contents($backupFile, $backupContent)) {
        return [
            'success' => true,
            'file' => $backupFile,
            'size' => filesize($backupFile),
            'timestamp' => $timestamp
        ];
    }
    
    return ['success' => false, 'error' => 'Failed to write backup file'];
}

/**
 * Get all backup files
 */
function getBackupFiles($backupDir = '../backups/') {
    if (!is_dir($backupDir)) {
        return [];
    }
    
    $files = [];
    $backupFiles = glob($backupDir . 'movielab_backup_*.sql');
    
    foreach ($backupFiles as $file) {
        $files[] = [
            'name' => basename($file),
            'path' => $file,
            'size' => filesize($file),
            'modified' => filemtime($file),
            'date' => date('Y-m-d H:i:s', filemtime($file))
        ];
    }
    
    // Sort by modified date (newest first)
    usort($files, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    return $files;
}

/**
 * Restore database from backup
 */
function restoreDatabase($pdo, $backupFile) {
    if (!file_exists($backupFile)) {
        return ['success' => false, 'error' => 'Backup file not found'];
    }
    
    $sql = file_get_contents($backupFile);
    
    try {
        $pdo->beginTransaction();
        
        // Disable foreign key checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        // Execute SQL queries
        $queries = explode(";\n", $sql);
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                $pdo->exec($query);
            }
        }
        
        // Enable foreign key checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        $pdo->commit();
        
        return ['success' => true, 'message' => 'Database restored successfully'];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
?>