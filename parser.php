<?php
    $curl = curl_init("https://www.mit.edu/~ecprice/wordlist.10000");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $list_of_words = curl_exec($curl);
    $words = explode("\n", $list_of_words);

    $number_of_words = 1000;

    for($i = 0; $i < $number_of_words; $i++){
        $word = $words[$i];
        $curl = curl_init("https://wooordhunt.ru/word/{$word}");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($curl);
        curl_close($curl);
    
        $pattern_string = "/<div class=\"t_inline_en\">.*?<\/div>/";
        preg_match_all($pattern_string, $content, $translation_matches);

        if(!isset($translation_matches[0][0])) continue;

        $str = preg_replace("/<.*?>/", "", $translation_matches[0][0]);
        $translations = explode(", ", $str);
        

        $pattern_string = "/<p class=\"ex_o\">.*?<span.*?>/";
        preg_match_all($pattern_string, $content, $examples_matches);
    
        $examples = [];
        foreach($examples_matches[0] as $example){
            array_push($examples, preg_replace("/<.*?>/", "", $example));
        }

        echo "<b>Слово: ${word}</b><br><br>";
        echo "Перевод: <br>";

        foreach($translations as $translation){
            echo "${translation}<br>";
        }
        echo "<br>";

        if(!isset($examples_matches[0][0])) continue;

        echo "Примеры:<br>";
        foreach($examples as $example){
            echo "${example}<br>";
        }

        echo "<br><br>";
    }
?>