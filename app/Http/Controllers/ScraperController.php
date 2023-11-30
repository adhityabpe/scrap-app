<?php

namespace App\Http\Controllers;


use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Filesystem\Filesystem;   

class ScraperController extends Controller
{
    function dateKurs($date)
    {
        $date = str_replace('Kurs Dollar Hari Ini - ', '', $date);
        // remove whitespace &nbsp
        $dateArray = explode(",\xC2\xA0", $date);

        return $dateArray;
    }

    function word($text)
    {
        $text = str_replace(
            array('<br>', '<nobr>', '</nobr>'),
            array('', ' ', ''),
            $text,
        );
        return $text;
    }

    public function jsonDollar()
    {

        $client = new Client(HttpClient::create(['timeout' => 60]));

        $url = 'https://kursdollar.org/';

        $crawler = $client->request('GET', $url);

        $table = $crawler->filter('table.in_table')->first();

        $date = $table->filter('.in_table tr:not([id]):not([class]) td')->text();

        $meta = $table->filter('.in_table tr:nth-child(2)')->each(function ($row) use ($date) {
            return [
                'date' => $this->dateKurs($date)[1],
                'day' => $this->dateKurs($date)[0],
                'indonesia' => $row->filter('td:nth-child(2)')->text(),
                'word' => $this->word($row->filter('td:nth-child(3)')->html()),

            ];
        });

        $rates = $table->filter('.in_table tr[id]')->each(function ($row) {
            return [
                'currency' => $row->filter('td a strong')->text(),
                'buy' => $row->filter('td')->eq(1)->text(),
                'sell' => $row->filter('td')->eq(2)->text(),
                'average' => $row->filter('td')->eq(3)->text(),
                'word_rate' => $row->filter('td')->eq(4)->text(),

            ];
        });
        date_default_timezone_set('Asia/Jakarta');
        $fileNames='rate-'.date('d-m-Y-h-i-a').'.json'; 
        
        $echo_time = time();
        $interval = 4*60;
        while(true){
            if ($echo_time + $interval >= time()){
                $file = new Filesystem;
                $file->cleanDirectory('storage/app/backgrounds');
                   $echo_time = time(); // set up timestamp for next interval
                   Storage::disk('public')->put($fileNames,json_encode(['meta' => $meta, 'rates' => $rates]));
                   return response()->json(['meta' => $meta, 'rates' => $rates]);
                }
        }
       
    }
}