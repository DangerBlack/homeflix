<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    require_once("config.php");
    require 'Medoo.php';

    function connect(){
		$database = new Medoo([
                // required
                'database_type' => 'sqlite',
                'database_file' => '../archive/hf.sqlite'
        ]);
		return $database;
    }
    function login($name,$pswd){
        $salt = getSalt($name);
        $database=connect();
		$result=$database->has("user",[
			"AND" => [
				"name" => $name,
				"pswd" => hash("sha256",$pswd.$salt)
			]
		]);
		return $result;
	}
    function isLogged(){
		@$utente=$_COOKIE["name"];
		@$pswd=$_COOKIE["pswd"];
		if(login($utente,$pswd)){
			return true;
		}else{
			return false;
		}
	}
    function getIdFromName($name){
		$database=connect();
		$datas=$database->select("user",[
			"id"
		],[
			"name[=]"=>$name
		]);
		return $datas;
	}

    function getIdFromKey($key){
		$database=connect();
		$datas=$database->select("secret",[
			"iduser"
		],[
			"key[=]"=>$key
		]);
		return $datas[0]['iduser'];
	}
    function getId(){
		@$name=$_COOKIE["name"];
		return getIdFromName($name)[0]['id'];
	}
    function getUser($id){
		$database=connect();
		$res=$database->select("user",[
			"id",
			"mail",
			"name",
            "role",
            "img"
		],[
			"id[=]"=>$id
		]);
		return $res;
	}
    function getUserList(){
		$database=connect();
		$res=$database->select("user",[
			"id",
			"mail",
			"name",
            "role",
            "img"
		]);
		return $res;
	}
    function getRole(){
		$id=getId();
		$user=getUser($id);
		$role=$user[0]['role'];
		return $role;
	}
    function getSalt($name){
        $database=connect();
        $res=$database->select("user",[
			"id",
            "salt"
		],[
			"name[=]"=>$name
		]);
        if(empty($res))
            return "";
	    $salt=$res[0]['salt'];
		return $salt;
	}
    function getSecretKey(){
        $key = getKey();
        return $key;
    }
    function insertUser($name,$mail,$pswd,$role){
		$database=connect();
        $salt = md5(microtime().rand());
		$res=$database->insert("user",[
			"name"=>$name,
			"mail"=>$mail,
			"pswd"=>hash('sha256',$pswd.$salt),
            "salt"=>$salt,
			"role"=>$role
		]);
        $id=$database->id();
        if($id!=0){
            $key = md5(microtime().rand());
            $res2=$database->insert("secret",[
    			"iduser"=>$id,
    			"key"=>$key,
    			"active"=>true
    		]);
        }else{
            die("500");
        }
		return $res;
	}
    function updateUserInfo($id,$name,$mail,$role){
		$database=connect();
		$res=$database->update("user",[
			"name"=>$name,
			"mail"=>$mail,
			"role"=>$role
		],[
			"id[=]"=>$id
		]);
		return $res;
	}
    function updateUserImage($id,$img){
		$database=connect();
		$res=$database->update("user",[
			"img"=>$img
		],[
			"id[=]"=>$id
		]);
        //var_dump($database->error());
		return $res;
	}
    function updatePswd($id,$pswd,$newpswd){
		$database=connect();
		$res=$database->update("user",
		[
			'pswd'=>$newpswd
		],[
			'AND'=>[
					'id[=]'=>$id,
					'pswd[=]'=>$pswd,
			]
		]);
		return $res;
	}
    function deleteUser($id){
		$database=connect();
		$res=$database->delete("user",[
			'id[=]'=>$id
		]);
		return $res;
	}
    function getKey(){
        $id = getId();
        $database=connect();
        $res=$database->select("secret",[
			"key"
		],[
			"iduser[=]"=>$id //verificare che la chiave sia attiva e che sia l'ultima!
		]);
        if(empty($res))
            die("KEY not found");
        return $res[0]['key'];
    }
    function checkSecretKey($secret_key){
        $database = connect();
        $res=$database->has("secret",[
			"key[=]"=>$secret_key //verificare che sia attiva!
		]);
        return $res;
    }

    function insertMovie($hash,$title,$url,$folder,$filetime){
        $database=connect();
		$res=$database->insert("movie",[
			"hash"=>$hash,
			"title"=>$title,
			"url"=>$url,
            "folder"=>$folder,
            "filetime"=>$filetime
		]);
        //var_dump($database->error());
        return $res;
    }

    function insertGenres($id,$name){
        $database=connect();
		$res=$database->insert("genres",[
			"name"=>$name,
			"idmovie"=>$idmovie
		]);
        return $res;
    }
    function getGenres($idmovie){
        $database=connect();
		$res=$database->select("genres",[
			"name"
		],[
            "idmovie[=]"=>$idmovie
        ]);
        return $res;
    }
    function updateMovie($hash,$title,$url,$idapi,$overview,$vote_average,$vote_count,$release_date,$runtime,$backdrop_path,$folder,$confirmed){
        $database=connect();
		$res=$database->update("movie",[
			"title"=>$title,
			"url"=>$url,
            "idapi"=>$idapi,
            "overview"=>$overview,
            "vote_average"=>$vote_average,
            "vote_count"=>$vote_count,
            "release_date"=>$release_date,
            "runtime"=>$runtime,
            "backdrop_path"=>$backdrop_path,
            "folder"=>$folder,
            "confirmed"=>$confirmed
		],[
			"hash[=]"=>$hash
		]);
        //var_dump($database->error());
        return $res;
    }


    function getMovieFromUrl($url){
        $database=connect();
        $res=$database->select("movie",[
            "id"
		],[
			"url[=]"=>$url
		]);
        $res = $res[0];
        return $res['id'];
    }
    function getMovie($hash){
        $database=connect();
        $res=$database->select("movie",[
            "id",
            "hash",
			"title",
			"url",
            "idapi",
            "overview",
            "vote_average",
            "vote_count",
            "release_date",
            "runtime",
            "backdrop_path",
            "folder",
            "confirmed"
		],[
			"hash[=]"=>$hash
		]);
        $res = $res[0];
        $res["genres"]=getGenres($res['id']);
        $res["watched"]=getWatched($res['id']);
        $res["favorite"]=isFavorite($res['id']);
		return $res;
    }

    function checkMovie($hash){
        $database = connect();
        $res=$database->has("movie",[
			"hash[=]"=>$hash //verificare che sia attiva!
		]);
        return $res;
    }

    function deleteMovie($hash){
        $database = connect();
        $res=$database->delete("movie",[
			"hash[=]"=>$hash //verificare che sia attiva!
		]);
        return $res;
    }
    function getMovieList($folder,$order){
        $database=connect();
        $filter = [];
        if($folder!=null){
            $filter["folder[=]"]=$folder;
        }
        if($order==="time"){
            $filter["ORDER"]=["filetime"=>"DESC"];
            $filter["LIMIT"]=24;
        }else{
            $filter["ORDER"]=["title"=>"ASC"];
        }
        $res=$database->select("movie",[
            "id",
            "hash",
			"title",
			"url",
            /*"idapi",
            "overview",
            "vote_average",
            "vote_count",
            "release_date",
            "runtime",*/
            "filetime",
            "backdrop_path",
            "folder",
            "confirmed"
		],$filter);
		return $res;
    }
    function getMovieHash(){
        $database=connect();
        $res=$database->select("movie",[
            "id",
            "hash"
		]);
		return $res;
    }

    function insertWatched($idmovie,$key){
        $iduser=getIdFromKey($key);
        $database=connect();
		$res=$database->insert("watched",[
			"idmovie"=>$idmovie,
            "iduser"=>$iduser
		]);
        return $res;
    }

    function getWatched($idmovie){
        $database=connect();
		$res=$database->select("watched",
                ["[>]user"=>["iduser"=>"id"]
            ],[
			"user.id",
            "user.name",
            "watched.time"
		],[
            "idmovie[=]"=>$idmovie,
            "ORDER"=>["time"=>"DESC"],
            "LIMIT"=>10
        ]);
        return $res;
    }

    function addFavorite($idmovie){
        $iduser=getId();
        $database=connect();
		$res=$database->insert("favorite",[
			"idmovie"=>$idmovie,
            "iduser"=>$iduser
		]);
        return $res;
    }
    function deleteFavorite($idmovie){
        $iduser=getId();
        $database=connect();
        $res=$database->delete("favorite",[
			"AND"=>[
                "idmovie[=]"=>$idmovie,
                "iduser[=]"=>$iduser
            ]
		]);
        return $res;
    }
    function isFavorite($idmovie){
        $iduser=getId();
        $database=connect();
		$res=$database->has("favorite",[
			"AND"=>[
                "idmovie[=]"=>$idmovie,
                "iduser[=]"=>$iduser
            ]
		]);
        return $res;
    }

    function addPost($idmovie,$mex,$star){
        $iduser=getId();
        $database=connect();
		$res=$database->insert("post",[
			"idmovie"=>$idmovie,
            "iduser"=>$iduser,
            "mex"=>$mex,
            "star"=>$star
		]);
        return $res;
    }

    function getPost($idmovie){
        $database=connect();
		$res=$database->select("post",[
            "[>]user"=>["iduser"=>"id"]
        ],[
            "user.name",
            "user.img",
            "post.iduser",
            "post.iduser",
            "post.mex",
            "post.star",
            "post.time"
		],[
            "post.idmovie[=]"=>$idmovie,
            "ORDER"=>["time"=>"DESC"],
        ]);
        return $res;
    }
?>
