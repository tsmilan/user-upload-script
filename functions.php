<?php

/**
 * Helper functions file
 * @author Trisha Milan <tshmilan@gmail.com>
 */
/**
 * Get the field names from the CSV file
 * @param string $filename The name of the file
 * @return array Returns an array of table fields or boolean
 */
function getFieldNamesFromCSV($filename) {
    $tableFields = array();
    if(!file_exists($filename) || !is_readable($filename)) return false;
    $row = 1;
    if (($handle = fopen($filename, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $num = count($data);
            if ($row === 1) {
                for ($c=0; $c < $num; $c++) {
                    $tableFields[$c] = trim($data[$c]);
                }
            }
        break;
        }
        fclose($handle);
    }
    return $tableFields;
}
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