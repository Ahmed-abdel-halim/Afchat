<?php

function strip_comments($source) {
    $tokens = token_get_all($source);
    $output = '';
    foreach ($tokens as $token) {
        if (is_array($token)) {
            if ($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                // Remove the comment
                continue;
            }
            $output .= $token[1];
        } else {
            $output .= $token;
        }
    }
    return $output;
}

function process_directory($dir) {
    $it = new RecursiveDirectoryIterator($dir);
    $it = new RecursiveIteratorIterator($it);
    foreach ($it as $file) {
        if ($file->isFile() && $file->getExtension() == 'php') {
            $path = $file->getRealPath();
            // Skip vendor, .git, storage, bootstrap/cache
            if (strpos($path, 'vendor') !== false || 
                strpos($path, '.git') !== false || 
                strpos($path, 'storage\framework') !== false ||
                strpos($path, 'storage\logs') !== false ||
                strpos($path, 'bootstrap\cache') !== false) {
                continue;
            }
            
            echo "Processing: $path\n";
            $content = file_get_contents($path);
            $newContent = strip_comments($content);
            if ($content !== $newContent) {
                file_put_contents($path, $newContent);
            }
        }
    }
}

$root = dirname(__FILE__);
process_directory($root);
echo "Finished removing comments from PHP files.\n";
