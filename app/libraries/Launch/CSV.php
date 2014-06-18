<?php namespace Launch;

class CSV {

    public static function flatten_collection($collection, $deep = false)
    {
        $data = [];
        foreach ($collection as $model) {
            $data[] = self::flatten_array($model->toArray(), $deep);
        }

        if (empty($data)) {
            die('No data to export.');
        }

        $firstRow = array_keys($data[0]);
        array_unshift($data, $firstRow);

        return $data;
    }

    public static function download_csv($data, $filename)
    {
        header("Content-type: text/csv");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Disposition: attachment; filename = \"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $outstream = fopen("php://output", 'w');

        array_walk($data, function (&$vals, $key, $filehandler) {
            fputcsv($filehandler, $vals);
        }, $outstream);

        fclose($outstream);

        exit; // don't want the rest of the page to download, just the CSV!
    }

    private static function flatten_array($array, $masterArray = [], $prependKey = '', $deep = false)
    {
        if (is_bool($masterArray)) {
            $deep = $masterArray;
            $masterArray = [];
            $prependKey = '';
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!$deep) continue;
                $append = self::flatten_array($value, $masterArray, "{$prependKey}{$key}_"); 
                $masterArray = array_merge($masterArray, $append);
            } else {
                if (!preg_match('/_(?:id|at)$/', $key)) {
                    $masterArray[$prependKey . $key] = $value;
                }
            }
        }

        return $masterArray;
    }

}