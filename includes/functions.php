<?php
function asset($relativePath)
{
    $fullPath = __DIR__ . '/../' . $relativePath;
    $version = file_exists($fullPath) ? filemtime($fullPath) : time();
    return $relativePath . '?v=' . $version;
}
