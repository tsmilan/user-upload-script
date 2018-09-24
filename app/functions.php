<?php

/**
 * Helper functions file
 * @author Trisha Milan <tshmilan@gmail.com>
 */
/**
 * Parse CSV file
 * @param string $filename The name of the file
 * @return array Returns an array of users or boolean
 */
function parseCSV($filename) {
    if(!file_exists($filename) || !is_readable($filename)) return false;
        $csv = array_map("str_getcsv", file($filename)); 
        $keys = array_shift($csv);
        $trimmedKeys = array_map('trim', $keys);
        foreach ($csv as $i=>$row) {
            $csv[$i] = array_combine($trimmedKeys, $row);
        }
    return $csv;
}