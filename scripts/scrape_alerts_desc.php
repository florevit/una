<?php

require_once (__DIR__ . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

$baseDir = __DIR__ . DIRECTORY_SEPARATOR . '..';

$foldersRecursive = [
    'inc',
    'modules',
    'periodic',
    'studio',
    'template'
];

// skip any path containing /modules/*/*/updates
function shouldSkip($path) {
    // normalize for safety
    $path = str_replace('\\', '/', $path);

    // matches: modules/anything/anything/updates
    if (preg_match('#/modules/[^/]+/[^/]+/updates(/|$)#', $path)) {
        return true;
    }

    return false;
}

$pattern = '/@hookdef\s+hook-[^\s]+\s+\'([^\']+)\'\s*,\s*\'([^\']+)\'\s*-\s*(.+)/';

$result = [];

function processFile($baseDir, $filePath, $pattern, &$result) {
    $handle = fopen($filePath, 'r');
    if (!$handle) return;

    $lineNumber = 0;

    while (($line = fgets($handle)) !== false) {
        $lineNumber++;

        if (strpos($line, '@hookdef') === false) continue;

        if (preg_match($pattern, $line, $m)) {
            $result[] = [
                'file'   => str_replace($baseDir . DIRECTORY_SEPARATOR, '', $filePath),
                'line'   => $lineNumber,
                'unit'   => $m[1],
                'action' => $m[2],
                'desc'   => trim($m[3]),
            ];
        }
    }

    fclose($handle);
}

/**
 * Base dir (non-recursive)
 */
foreach (scandir($baseDir) as $file) {
    $path = $baseDir . DIRECTORY_SEPARATOR . $file;

    if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
        processFile($baseDir, $path, $pattern, $result);
    }
}

/**
 * Recursive folders with skip logic
 */
foreach ($foldersRecursive as $folder) {
    $dir = $baseDir . DIRECTORY_SEPARATOR . $folder;

    if (!is_dir($dir)) continue;

    $directory = new RecursiveDirectoryIterator(
        $dir,
        FilesystemIterator::SKIP_DOTS
    );

    $filter = new RecursiveCallbackFilterIterator(
        $directory,
        function ($current, $key, $iterator) {

            $path = $current->getPathname();

            // skip unwanted subtree
            if (shouldSkip($path)) {
                return false;
            }

            return true;
        }
    );

    $iterator = new RecursiveIteratorIterator($filter);

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') continue;

        processFile($baseDir, $file->getPathname(), $pattern, $result);
    }
}

/**
 * Output
 */
BxDolDb::getInstance()->query("TRUNCATE TABLE sys_alerts_desc");
foreach ($result as $row) {
    echo "{$row['unit']} | {$row['action']} | {$row['desc']}\n";
    echo "  -> {$row['file']}:{$row['line']}\n\n";
    BxDolDb::getInstance()->query("INSERT IGNORE INTO sys_alerts_desc SET `unit` = :unit, `action` = :action, `description` = :desc", [
        'unit' => $row['unit'],
        'action' => $row['action'],
        'desc' => $row['desc']
    ]);
}