<?php
/**
 * Created by PhpStorm.
 * User: Bustommy Maulana
 * Date: 02/11/2018
 * Time: 16.56
 */

if (! function_exists('elo2ComboBox')) {//eloquent to combobox
    /**
     * merubah data eloquent menjadi data combobox
     * @param $data
     * @param $value
     * @param $title
     * @param bool $noTitleCase
     * @return array
     */
    function elo2ddl($data, $title, $value = 'id', $titleAppend ="", $noTitleCase = false) //dont use first() replace to take(1)
    {
        if (!empty($data)){
            $data = $data->toArray();

            foreach ($data as $datum){
                $tempTitle = !empty($titleAppend) ? $datum[$title].' ('.$datum[$titleAppend].')' : $datum[$title];
                $tempTitle = $noTitleCase ? title_case($tempTitle) : $tempTitle;

                $res[$datum[$value]] = $tempTitle;
            }

        }
        return  isset($res) ? $res : [];
    }
}

if (! function_exists('randomDate')) {
    /**
     * @param $sStartDate
     * @param $sEndDate
     * @param string $sFormat
     * @return false|string
     */
    function randomDate($sStartDate, $sEndDate, $sFormat = 'Y-m-d H:i:s')
    {
        // Convert the supplied date to timestamp
        $fMin = strtotime($sStartDate);
        $fMax = strtotime($sEndDate);
        // Generate a random number from the start and end dates
        $fVal = mt_rand($fMin, $fMax);
        // Convert back to the specified date format
        return date($sFormat, $fVal);
    }

    if (! function_exists('toRp')) {
        /**
         * @param $sStartDate
         * @param $sEndDate
         * @param string $sFormat
         * @return false|string
         */
        function toRp($angka, $withoutrp = false)
        {
            $rp = number_format($angka,0,'','.');
            return !$withoutrp ? 'Rp. '.$rp : ''.$rp;
        }
    }
}

if (! function_exists('reziseImage')) {
    /*
     *
     */
    function reziseImage($file, $folderName, $fileName = null, $dimension = '215' )
    {
        if(is_null($fileName))
            $fileName = $file->getClientOriginalName();

        $path = storage_path('app/public/foto/'.$folderName);
        //$canvas = Image::canvas($dimension, $dimension);
        $resizeImage  = Image::make($file)->resize($dimension, $dimension);
       /* $img = \Intervention\Image\Facades\Image::make(asset('dist/img/user1-128x128.jpg'));*/
        return $resizeImage->save($path. '/' . $fileName);
/*        Intervention\Image\

        $rp = number_format($angka,0,'','.');
        return !$withoutrp ? 'Rp. '.$rp : ''.$rp;*/
    }
}

if (! function_exists('sekarang')) {
    /*
     *
     */
    function sekarang()
    {
        return date('Y-m-d H:i:s');
    }
}

if (! function_exists('hariIni')) {
    /*
     *
     */
    function hariIni()
    {
        return date('Y-m-d');
    }
}

if (! function_exists('bulan')) {
    /*
     *
     */
    function bulan()
    {
        for($m=1; $m<=12; ++$m){
            $bulan[$m] = date('F', mktime(0, 0, 0, $m, 1));
        }

        return $bulan;
    }
}

if (! function_exists('tahun')) {
    /*
     *
     */
    function tahun()
    {
        for($m=date('Y'); $m >= 2015; $m-- ){
            $tahun[$m] = $m;
        }
        return $tahun;
    }
}