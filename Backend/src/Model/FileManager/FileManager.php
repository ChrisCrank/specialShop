<?php
namespace App\Model\FileManager;

class FileManager
{
    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file)
                if ($file != "." && $file != "..") self::rrmdir("$dir/$file");
            rmdir($dir);
        }
        else if (file_exists($dir))
            unlink($dir);
    }

    // Function to Copy folders and files
    public static function rcopy($src, $dst) {
        if (file_exists($dst)) {
            throw new \Exception('Folder: '.$dst.' already exsisting!');
        }
        if (is_dir($src)) {
            mkdir($dst);
            $files = scandir($src);
            foreach($files as $file)
                if ($file != "." && $file != "..")
                    self::rcopy ("$src/$file", "$dst/$file");
        } else if (file_exists($src))
            copy ($src, $dst);
    }
}
