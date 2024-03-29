<?php

namespace ccheng\eventmanager\common\libs\cos;

class Helper
{
    /**
     * Build custom post fields for safe multipart POST request for php before 5.5.
     *
     * @param $fields array of key -> value fields to post.
     *
     * @return $boundary and encoded post fields.
     */
    public static function buildCustomPostFields($fields)
    {
        // invalid characters for "name" and "filename"
        static $disallow = ["\0", "\"", "\r", "\n"];
        
        // initialize body
        $body = [];
        
        // build normal parameters
        foreach ($fields as $key => $value) {
            $key    = str_replace($disallow, "_", $key);
            $body[] = implode("\r\n", [
                "Content-Disposition: form-data; name=\"{$key}\"",
                '',
                filter_var($value),
            ]);
        }
        
        // generate safe boundary
        do {
            $boundary = "---------------------" . md5(mt_rand() . microtime());
        } while (preg_grep("/{$boundary}/", $body));
        
        // add boundary for each parameters
        foreach ($body as &$part) {
            $part = "--{$boundary}\r\n{$part}";
        }
        unset($part);
        
        // add final boundary
        $body[] = "--{$boundary}--";
        $body[] = '';
        
        return [$boundary, implode("\r\n", $body)];
    }
}
