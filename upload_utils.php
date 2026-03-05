<?php
/**
 * Shared upload utilities for image compression and safe file cleanup.
 */

if (!function_exists('tlm_normalize_stored_path')) {
    function tlm_normalize_stored_path($path)
    {
        $path = (string)$path;
        $path = strtok($path, '?');
        $path = trim(str_replace('\\', '/', $path));

        while (strpos($path, '../') === 0) {
            $path = substr($path, 3);
        }
        while (strpos($path, './') === 0) {
            $path = substr($path, 2);
        }

        return ltrim($path, '/');
    }
}

if (!function_exists('tlm_resolve_storage_path')) {
    function tlm_resolve_storage_path($storedPath, $projectRoot)
    {
        $clean = tlm_normalize_stored_path($storedPath);
        if ($clean === '') {
            return null;
        }

        if (preg_match('/^[A-Za-z]:[\\\\\/]/', $clean) === 1 || strpos($clean, '/') === 0) {
            return $clean;
        }

        return rtrim($projectRoot, '\\/') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $clean);
    }
}

if (!function_exists('tlm_delete_storage_file')) {
    function tlm_delete_storage_file($storedPath, $projectRoot)
    {
        $abs = tlm_resolve_storage_path($storedPath, $projectRoot);
        if ($abs === null) {
            return false;
        }

        $rootReal = realpath($projectRoot);
        $dirReal = realpath(dirname($abs));
        if ($dirReal === false) {
            return false;
        }

        $safeTarget = $dirReal . DIRECTORY_SEPARATOR . basename($abs);
        if ($rootReal !== false && strpos($safeTarget, $rootReal) !== 0) {
            return false;
        }

        if (is_file($safeTarget)) {
            return @unlink($safeTarget);
        }

        return false;
    }
}

if (!function_exists('tlm_move_uploaded_fallback')) {
    function tlm_move_uploaded_fallback($tmpPath, $destination)
    {
        if (move_uploaded_file($tmpPath, $destination)) {
            return true;
        }

        if (@rename($tmpPath, $destination)) {
            return true;
        }

        return @copy($tmpPath, $destination);
    }
}

if (!function_exists('tlm_store_uploaded_with_compression')) {
    function tlm_store_uploaded_with_compression($tmpPath, $destination, $maxDimension = 1920, $jpegQuality = 78, $pngCompression = 6, $webpQuality = 80)
    {
        $dir = dirname($destination);
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            return false;
        }

        $info = @getimagesize($tmpPath);
        if ($info === false || empty($info['mime'])) {
            return tlm_move_uploaded_fallback($tmpPath, $destination);
        }

        $mime = strtolower($info['mime']);
        $src = null;

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $src = @imagecreatefromjpeg($tmpPath);
        } elseif ($mime === 'image/png') {
            $src = @imagecreatefrompng($tmpPath);
        } elseif ($mime === 'image/gif') {
            $src = @imagecreatefromgif($tmpPath);
        } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
            $src = @imagecreatefromwebp($tmpPath);
        }

        if (!$src) {
            return tlm_move_uploaded_fallback($tmpPath, $destination);
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);
        $ratio = 1.0;

        if ($maxDimension > 0 && max($srcW, $srcH) > $maxDimension) {
            $ratio = $maxDimension / max($srcW, $srcH);
        }

        $newW = max(1, (int)round($srcW * $ratio));
        $newH = max(1, (int)round($srcH * $ratio));

        $dst = imagecreatetruecolor($newW, $newH);

        if ($mime === 'image/png' || $mime === 'image/gif' || $mime === 'image/webp') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

        $saved = false;
        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $saved = imagejpeg($dst, $destination, $jpegQuality);
        } elseif ($mime === 'image/png') {
            $saved = imagepng($dst, $destination, $pngCompression);
        } elseif ($mime === 'image/gif') {
            $saved = imagegif($dst, $destination);
        } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
            $saved = imagewebp($dst, $destination, $webpQuality);
        }

        imagedestroy($src);
        imagedestroy($dst);

        if (!$saved) {
            return tlm_move_uploaded_fallback($tmpPath, $destination);
        }

        return true;
    }
}

if (!function_exists('tlm_is_uploaded_image')) {
    function tlm_is_uploaded_image($tmpPath)
    {
        $info = @getimagesize($tmpPath);
        return $info !== false;
    }
}
?>