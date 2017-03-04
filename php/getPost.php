<?php
	include("query.php");
	function get_url($request_url) {
		  $ch = curl_init();
		  curl_setopt($ch, CURLOPT_URL, $request_url);
		  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		  $response = curl_exec($ch);
		  curl_close($ch);

		  return $response;
	}
	if(!isLogged())
		die("Non sei loggato");

    @$idmovie = $_GET['id'];

	$post=getPost($idmovie);

	//EXPERIMENTAL
	function getRemote($idmovie,$post){
		$hash=getMovieHashFromId($idmovie);

		$fed=getFed();
		foreach ($fed as $f) {
			$postRes = get_url($f['url'].'/php/getRemotePost.php?hash='.$hash.'&secret='.$f['secret']);
			if($postRes!=="403"){
				if($postRes!=="404"){
					$postT=json_decode($postRes);
					array_push($post,$postT);
				}
			}
		}
		return $post;
	}

	//$post = getRemote($idmovie,$post); //TODO ENABLE THIS FEATURE
	echo json_encode($post);
?>
