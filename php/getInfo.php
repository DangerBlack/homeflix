<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    require_once dirname(__DIR__).'/vendor/autoload.php';
    require_once 'config.php';
    require_once 'query.php';

    $PATH="../archive/tmdb";

    $url='https://image.tmdb.org/t/p/w640';

    $hash = $_GET['hash'];
    $m=getMovie($hash);
    if((!isset($m['confirmed']))||($m['confirmed']<0)){

        $token  = new \Tmdb\ApiToken($TMDB_API_KEY);
        $client = new \Tmdb\Client($token);
        $result = $client->getSearchApi()->searchMovies($m['title']);
        //var_dump($result);
        if(!empty($result['results'])){
            $movie = $client->getMoviesApi()->getMovie($result['results'][0]['id']);
            if(!file_exists($PATH.$movie['backdrop_path'])){
                $content = file_get_contents($url.$movie['backdrop_path']);
                file_put_contents($PATH.$movie['backdrop_path'],$content);
            }
            $confirmed = 1;
            if($m['confirmed']<0){
                $m['confirmed']=-$m['confirmed'];
            }
            updateMovie($hash,
                        $movie['title'],
                        $m['url'],
                        $movie['id'],
                        $movie['overview'],
                        $movie['vote_average'],
                        $movie['vote_count'],
                        $movie['release_date'],
                        $movie['runtime'],
                        $movie['backdrop_path'],
                        $m['folder'],
                        1
                    );

        }


    }

    $movie = getMovie($hash);
    $movie['key']=getKey();
    echo json_encode($movie);

    /*$query = new \Tmdb\Model\Search\SearchQuery\MovieSearchQuery();
    $query->page(1);
    $repository = new \Tmdb\Repository\SearchRepository($client);

    //$movie = $client->getMoviesApi()->getMovie(550);
    $find = $repository->searchMovie('batman', $query);

    //$movie = $client->getMoviesApi()->getMovie($find[0]->id);*/

    /*foreach($result['results'] as $r){
        var_dump($r);
        echo "<hr />";
    }*/
    //var_dump($result['results'][0]);
    //echo "<hr />";
 ?>
