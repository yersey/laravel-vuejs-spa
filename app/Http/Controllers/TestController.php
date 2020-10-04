<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Entry;
use App\Http\Resources\CommentResource;

class TestController extends Controller
{
    // jwt.auth

    public function index(Request $request)
    {
        $linki = collect([]);

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile('https://www.gry-online.pl/hardware/elon-musk-nie-zaszczepi-sie-na-covid-19-i-nazywa-billa-gatesa-pol/z81e918');
        libxml_clear_errors();
        $xpath = new \DOMXpath($doc);
        //$imgs = $xpath->query('//*[@class="article row"]');

        $imgs = $xpath->query("//img");

        // foreach($imgs as $img)
        // {
        //     $linki->push($img->getAttribute('src'));
        // }

        //

        // $imgs = $xpath->query("//img");
        for ($i=0; $i < $imgs->length; $i++) {
            $img = $imgs->item($i);
            $src = $img->getAttribute("src");
            $linki->push($src);
        }


        return response()->json($linki);
    }
}
