<?php
// CSV to Array Function
// Copyright (c) 2014-2021, Ink Plant
// https://inkplant.com/code/csv-to-array
// this version was last updated August 3, 2021

ini_set('auto_detect_line_endings', true);

// You can replace this with your own error handler.
if (!function_exists('csv_to_array_error')) {
    function csv_to_array_error($error_text)
    {
        return false;
    }
}

function csv_to_array($file, $args = array())
{

    $fields = array(
        'header_row' => true, // is there a row of headers before the actual data?
        'remove_header_row' => true, // if there's a header row, this will remove it (after using it)
        'numeric_headers' => false, // if true, ignore the names in the header row and use integers as array keys instead
        'allow_mismatched_headers' => false, // if true, allow rows that are shorter than header row to exist
        'trim_headers' => true, // trim whitespace around header row values
        'trim_values' => true, // trim whitespace around all non-header row values
        'debug' => false, // set to true while testing if you run into troubles
        'require_at_least_two_columns' => false, // this helps prevent errors from an almost empty line up top, but it requires that your data has at least two columns
    );
    foreach ($fields as $key => $default) {
        if (array_key_exists($key, $args)) {
            $$key = $args[$key];
        } else {
            $$key = $default;
        }
    }

    if ($debug) {
        echo '<p>Opening ' . htmlspecialchars($file) . '&hellip;</p>';
    }
    $data = array();

    $row = 0;
    if (($handle = fopen($file, 'r')) !== false) {
        while (!feof($handle)) {
            $line = fgetcsv($handle, 10240);
            //echo '<pre>$line: '.print_r($line,true).'</pre>';
            if (!empty($line) && (!$require_at_least_two_columns || (count($line) > 1))) {
                $row++;
                if ($header_row && ($row == 1)) {
                    $data['headers'] = array();
                } else {
                    if ($header_row && (count($line) != count($data['headers']))) {
                        if ($allow_mismatched_headers) {
                            // fill in the empty headers, if necessary
                            while (count($line) > count($data['headers'])) {
                                $data['headers'][] = '?';
                            }
                        } else {
                            return csv_to_array_error('There is a mismatch in column quantity on row ' . $row . '. The header row has ' . count($data['headers']) . ' columns, but row ' . $row . ' has ' . count($line) . '.');
                        }
                    }
                    $data[$row] = array();
                }

                for ($c = 0; $c < count($line); $c++) {
                    $value = $line[$c];
                    if ($header_row && ($row == 1)) { // if this is part of the header row
                        if ($trim_headers) {
                            $value = trim($value);
                        }
                        if (in_array($value, $data['headers'])) {
                            return csv_to_array_error('There are duplicate values in the header row: ' . htmlspecialchars($value) . '.');
                        } else {
                            $data['headers'][$c] = (string)$value;
                        }
                    } elseif ($header_row && !$numeric_headers) { // if this isn't part of the header row, but there is a header row
                        $key = $data['headers'][$c];
                        if ($trim_values) {
                            $value = trim($value);
                        }
                        $data[$row][$key] = $value;
                    } else { // if there's not a header row at all
                        if ($trim_values) {
                            $value = trim($value);
                        }
                        $data[$row][$c] = $value;
                    }
                }
            }
        }
        fclose($handle);
        if ($remove_header_row) {
            unset($data['headers']);
        }
        if ($debug) {
            echo '<pre>' . print_r($data, true) . '</pre>';
        }
        return $data;
    } else {
        return csv_to_array_error('There was an error opening the file.');
    }
}
