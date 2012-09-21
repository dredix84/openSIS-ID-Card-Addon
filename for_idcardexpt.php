<?php
	session_start();
	function setput($varname, $attrib="width")
	{
		if(isset($varname))
		{
			return "$attrib='$varname'";
		}
	}
	
	function cardround($r=0){
		if($r <> 0){
			echo "; border-radius: $r";
		}
	}
	
	include("data.php");	
	$con = mysql_connect($DatabaseServer,$DatabaseUsername,$DatabasePassword);
	if (!$con){die('Could not connect: ' . mysql_error());}	
	mysql_select_db($DatabaseName, $con);

	$result = mysql_query("SELECT `value` FROM program_user_config WHERE program = 'IDCardGen' AND title = 'intname'");
	$row = mysql_fetch_array($result); 	
	$intname = $row['value'];
	
	$result = mysql_query("SELECT `value` FROM program_user_config WHERE program = 'IDCardGen' AND title = 'intname_css'");
	$row = mysql_fetch_array($result); 	
	$intname_css = $row['value'];
	
	$result = mysql_query("SELECT `value` FROM program_user_config WHERE program = 'IDCardGen' AND title = 'header_css'");
	$row = mysql_fetch_array($result); 	
	$header_css = $row['value'];
	
	$result = mysql_query("SELECT `value` FROM program_user_config WHERE program = 'IDCardGen' AND title = 'cardi'");
	$row = mysql_fetch_array($result); 	
	$cardi = json_decode($row['value'],true);
	
	$result = mysql_query("SELECT `value` FROM program_user_config WHERE program = 'IDCardGen' AND title = 'cardb'");
	$row = mysql_fetch_array($result); 	
	$cardb = json_decode($row['value'],true);
	
	$result = mysql_query("SELECT `value` FROM program_user_config WHERE program = 'IDCardGen' AND title = 'cardimg'");
	$row = mysql_fetch_array($result); 	
	$cardimg = json_decode($row['value'],true);
	
	$result = mysql_query("SELECT `value` FROM program_user_config WHERE program = 'IDCardGen' AND title = 'cardlogo'");
	$row = mysql_fetch_array($result); 	
	$cardlogo = json_decode($row['value'],true);
	
	$result = mysql_query("SELECT `value` FROM program_user_config WHERE program = 'IDCardGen' AND title = 'cardsign'");
	$row = mysql_fetch_array($result); 	
	$cardsign = json_decode($row['value'],true);
	
	mysql_close($con);
	
	
	
	
	$spaceb = $cardi["spaceb"];
	$card_h = $cardi["card_h"];
	$card_w = $cardi["card_w"];
	$card_gr1 = $cardi["card_gr1"];
	$card_gr2 = $cardi["card_gr2"];
	
	$card_borderc = $cardb["card_borderc"];
	$card_bordersize = $cardb["card_bordersize"];
	$card_radius = $cardb["card_radius"];

	$img_h = $cardimg["img_h"];
	$img_w = $cardimg["img_w"];
	$img_round = $cardimg["img_round"];
	$img_borderc = $cardimg["img_borderc"];	//HTMl color code or color word
	
	
	//JSON Array
	$cardi["spaceb"] = "50px";
	$cardi["card_h"] = "185px";
	$cardi["card_w"] = "300px";
	$cardi["card_borderc"] = "black";
	$cardi["card_bordersize"] = "1px";
	$cardi["card_radius"] = "10px";
	
	$cardi["card_gr1"] = "#AAD4FF";
	$cardi["card_gr2"] = "#FFFFFF";
	$cardi["img_h"] = "200";
	$cardi["img_w"] = "100";
	$cardi["img_round"] = 1;
	$cardi["img_borderc"] = "black";	//HTMl color code or color word
	
/*
function by Wes Edling .. http://joedesigns.com
feel free to use this in any project, i just ask for a credit in the source code.
a link back to my site would be nice too.
*/
function resize($imagePath,$opts=null){
	# start configuration
	$cacheFolder = 'assets/StudentPhotos/'; # path to your cache folder, must be writeable by web server
	$remoteFolder = $cacheFolder; # path to the folder you wish to download remote images into
	$quality = 90; # image quality to use for ImageMagick (0 - 100)
	
	$cache_http_minutes = 20; 	# cache downloaded http images 20 minutes

	$path_to_convert = 'assets/'; # this could be something like /usr/bin/convert or /opt/local/share/bin/convert
	
	## you shouldn't need to configure anything else beyond this point
	$purl = parse_url($imagePath);
	$finfo = pathinfo($imagePath);
	$ext = $finfo['extension'];

	# check for remote image..
	if(isset($purl['scheme']) && $purl['scheme'] == 'http'):
		# grab the image, and cache it so we have something to work with..
		list($filename) = explode('?',$finfo['basename']);
		$local_filepath = $remoteFolder.$filename;
		$download_image = true;
		if(file_exists($local_filepath)):
			if(filemtime($local_filepath) < strtotime('+'.$cache_http_minutes.' minutes')):
				$download_image = false;
			endif;
		endif;
		if($download_image == true):
			$img = file_get_contents($imagePath);
			file_put_contents($local_filepath,$img);
		endif;
		$imagePath = $local_filepath;
	endif;

	if(file_exists($imagePath) == false):
		$imagePath = $_SERVER['DOCUMENT_ROOT'].$imagePath;
		if(file_exists($imagePath) == false):
			return 'image not found';
		endif;
	endif;

	if(isset($opts['w'])): $w = $opts['w']; endif;
	if(isset($opts['h'])): $h = $opts['h']; endif;

	$filename = md5_file($imagePath);

	if(!empty($w) and !empty($h)):
		$newPath = $cacheFolder.$filename.'_w'.$w.'_h'.$h.(isset($opts['crop']) && $opts['crop'] == true ? "_cp" : "").(isset($opts['scale']) && $opts['scale'] == true ? "_sc" : "").'.'.$ext;
	elseif(!empty($w)):
		$newPath = $cacheFolder.$filename.'_w'.$w.'.'.$ext;	
	elseif(!empty($h)):
		$newPath = $cacheFolder.$filename.'_h'.$h.'.'.$ext;
	else:
		return false;
	endif;

	$create = true;

	if(file_exists($newPath) == true):
		$create = false;
		$origFileTime = date("YmdHis",filemtime($imagePath));
		$newFileTime = date("YmdHis",filemtime($newPath));
		if($newFileTime < $origFileTime):
			$create = true;
		endif;
	endif;

	if($create == true):
		if(!empty($w) and !empty($h)):

			list($width,$height) = getimagesize($imagePath);
			$resize = $w;
		
			if($width > $height):
				$resize = $w;
				if(isset($opts['crop']) && $opts['crop'] == true):
					$resize = "x".$h;				
				endif;
			else:
				$resize = "x".$h;
				if(isset($opts['crop']) && $opts['crop'] == true):
					$resize = $w;
				endif;
			endif;

			if(isset($opts['scale']) && $opts['scale'] == true):
				$cmd = $path_to_convert." ".$imagePath." -resize ".$resize." -quality ".$quality." ".$newPath;
			else:
				$cmd = $path_to_convert." ".$imagePath." -resize ".$resize." -size ".$w."x".$h." xc:".(isset($opts['canvas-color'])?$opts['canvas-color']:"transparent")." +swap -gravity center -composite -quality ".$quality." ".$newPath;
			endif;
						
		else:
			$cmd = $path_to_convert." ".$imagePath." -thumbnail ".(!empty($h) ? 'x':'').$w."".(isset($opts['maxOnly']) && $opts['maxOnly'] == true ? "\>" : "")." -quality ".$quality." ".$newPath;
		endif;

		$c = exec($cmd);
		
	endif;

	# return cache file path
	return str_replace($_SERVER['DOCUMENT_ROOT'],'',$newPath);
}

/**
 * find files matching a pattern
 * using PHP "glob" function and recursion
 *
 * @return array containing all pattern-matched files
 *
 * @param string $dir     - directory to start with
 * @param string $pattern - pattern to glob for
 */
function find($dir, $pattern){
    // escape any character in a string that might be used to trick
    // a shell command into executing arbitrary commands
    $dir = escapeshellcmd($dir);
    // get a list of all matching files in the current directory
    $files = glob("$dir/$pattern");
    // find a list of all directories in the current directory
    // directories beginning with a dot are also included
    foreach (glob("$dir/{.[^.]*,*}", GLOB_BRACE|GLOB_ONLYDIR) as $sub_dir){
        $arr   = find($sub_dir, $pattern);  // resursive call
        $files = array_merge($files, $arr); // merge array with files from subdirectory
    }
    // return all found files
    return $files;
}


function getimage($student_id){
	global $img_h, $img_w;
	$finds = find("assets/StudentPhotos","$student_id.JPG");
	if(count($finds)> 0){
		//echo resize("PATH_TO_IMAGE",array("w"=>200,"h"=>200));
		return "<img src='" . $finds[count($finds) - 1] . "' alt=\"$student_id\" class=\"resize\" />";
	}else{
		if(file_exists("assets/StudentPhotos/".$_SESSION["UserSyear"]."/".$student_id.".JPG"))
		{
			return "<img src='" . "assets/StudentPhotos/".$_SESSION["UserSyear"]."/".$student_id.".JPG" . "' alt=\"$student_id\" class=\"resize\" />";
		}else{
		return '<img width="'.$img_w.'px"  class=\"resize\"  src="data:image/jpg;base64,/9j/4AAQSkZJRgABAQEB3QHdAAD/4TIraHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIg
aWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/Pgo8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRr
PSJBZG9iZSBYTVAgQ29yZSA1LjAtYzA2MCA2MS4xMzQ3NzcsIDIwMTAvMDIvMTItMTc6MzI6MDAgICAgICAgICI+CiAgIDxyZGY6
UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+CiAgICAgIDxyZGY6RGVz
Y3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICAgICAgICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyI+
CiAgICAgICAgIDx4bXA6Q3JlYXRvclRvb2w+QWRvYmUgRmlyZXdvcmtzIENTNSAxMS4wLjAuNDg0IFdpbmRvd3M8L3htcDpDcmVh
dG9yVG9vbD4KICAgICAgICAgPHhtcDpDcmVhdGVEYXRlPjIwMTEtMTEtMTRUMDM6Mjc6NDJaPC94bXA6Q3JlYXRlRGF0ZT4KICAg
ICAgICAgPHhtcDpNb2RpZnlEYXRlPjIwMTEtMTEtMTRUMDM6Mjg6MDRaPC94bXA6TW9kaWZ5RGF0ZT4KICAgICAgPC9yZGY6RGVz
Y3JpcHRpb24+CiAgICAgIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICAgICAgICAgIHhtbG5zOmRjPSJodHRwOi8v
cHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyI+CiAgICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2UvanBlZzwvZGM6Zm9ybWF0PgogICAg
ICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAog
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
IAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAK
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAog
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
IAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAK
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAKPD94cGFja2V0IGVuZD0idyI/Pv/bAEMABgQEBAUEBgUFBgkGBQYJCwgGBggLDAoKCwoKDBAM
DAwMDAwQDA4PEA8ODBMTFBQTExwbGxscICAgICAgICAgIP/bAEMBBwcHDQwNGBAQGBoVERUaICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgICAgICAgICAgICAgIP/AABEIAQABAAMBEQACEQEDEQH/xAAcAAEAAQUBAQAAAAAAAAAAAAAAAgEDBQYH
BAj/xABJEAABAwICBAgJCQYFBQAAAAABAAIDBAUGERIhMVEHExQiMkFhgRUjQlJxcpGhsTNUYpOiwcLR0iRDgpKy8BY0U8PxRGNk
s+L/xAAWAQEBAQAAAAAAAAAAAAAAAAAAAQL/xAAWEQEBAQAAAAAAAAAAAAAAAAAAARH/2gAMAwEAAhEDEQA/APqlAQEBAQEBAQEB
AQEBBirviiwWgHl9bHE8fuQdKT+RubkGpV/DJaYzlQ0M1T9KQtiHd8ofcpow03DNeCfE0FOxvUHl7/gWJotN4Y8RZ86kpCOxsg/3
Cmj30vDRKMhV2trt7opSPsuafimjY7Zwo4UrSGSSvopD1VDcm/zN0m+3JXRtUE8FREJYJGyxO6MjCHNPoI1ILiAgICAgICAgICAg
ICAgICAgICAgICAgxl9xFarHScouE2gD8nENcjzua3+wg5beuEXEt9qDR2hj6WF+psUGbp3DteNY/hyU0StPBdcqnKa6VApg7WY2
+Mk7z0R71BttBwd4WpAM6Y1Lx5c7i77Iyb7lRmoLJZoBlDQ08Y+jEwfcgvOt9vcMnU0Th2sb+SDw1WEsNVQPG22DXtLGCM+1miUG
t3TgotMoLrfPJSv6mv8AGM+53vQarLa8aYPnNTTPeyDyp4Dpwn12n8QURumFeFShriykvDW0dUdTagfIuPbn0Pgrqt+BB1jYqCAg
ICAgICAgICAgICAgICAgICAg1vGeNKPDtKBkJrhMP2en/G/c34oOX2yzX7GNxfXVszuJzylqn7B9CJuzVu2BZHTbNYrXZ4OKoogz
PpyHW9/rO/sKjJAkoLjQguBqCWigoWoIHNBbdkRk4ajtQaPirg5patrqq0NbT1W11Psjf6PNPuQYfBuPK6wVPgm8h7qFrtDn58ZT
ns6y3s9iDsEUsU0TJYnB8UgDmPacwQdhBVE0BAQEBAQEBAQEBAQEBAQEBAQYnE+IqWw2mSun5z+jTw9b5DsH3nsQchsdpuOLr1NX
XCRxh0tKqm37o2btXsCyOq08MFLAyCnYI4oxkxjdQAVF1qC81BeaEF1oQXNFURcEFpwUFlyC3nl6EGr4zwjDeaY1FOAy5RDmO2cY
PMd9xQYLg1xhLbqzwDc3FtNI7Qpy/wDdS59A57A4+wpB1tUEBAQEBAQEBAQEBAQEBAQEBBxLGl4qcUYpbQ0Z0qeF5p6QdROfPk78
vYFB0K022mtVvio6ccyMa3dbndbj6VB7Gqi61BeagvtQXmqi4Cgg5BacoLLkFhyCGlkckHO+EnDoY8XmmbkHENqwN/kv79hUG78H
uJTfLE3j3Z11JlDU73aua/8AiHvzWhtCAgICAgICAgICAgICAgICDXse3o2nDNVMx2jUTDk9OevSk6x6G5lBz/gztIJnukg1jxMH
xefgFkb6XZn0IJtVF5qC80oLzSgutcglpKihcoLTigtOKCy4oLLkFispYa2jlpZhnFM0sf3oOdYCrpbDjTkE7so53uo5t2ln4t38
2XtQdrVBAQEBAQEBAQEBAQEBAQEHLOGa4E1FutwOprHVDx6x0G/0uUozWGqPkNgo4csnCMPf6z+cfeVBkWlBeaUF1pVF1pQXWuQX
A5BXSQULkEC5BbcUFpxQWXFBFrtag5lwhUzqPEUdbFzTMxsgd/3Izl8AEHaLdWNrbfTVjOjUxMlH8bQ771oehAQEBAQEBAQEBAQE
BAQEHFeExxqscOpz5DYIR/EA78alHQDkGADZsUFGlBeaUF1pVFxrkFwOQTDkFdJA0kEC5BAuQWnFBacVBDPnBBpXChCDTUM3W172
fzAH8KDe+D6cz4OtjztEbo/q3uYP6VobCgICAgICAgICAgICAgICDieN+bwkyl2zjqU93FxqDf5Dze9QUaUF1pQXWuQXA5UTDkEt
JBXSQNJBEuQQLkFtzkFtxUFvPnBBqfCYR4KpR18f+ByDbuDIH/BVBn1mbL6561BtKAgICAgICAgICAgICAgIOL8KkLqXGIqBtmhi
mHpbmz8ClG8cYJIGyN6LgHD0FQUaUFxrkFwOQXA5BMOQV0lRXSQNJBEuQRLlBbLkEHOQQB54QaXwm1HNoYN5e892QHxKDouBabk2
EbXHvhEn1pMn4loZ1AQEBAQEBAQEBAQEBAQEHNeGa26VPb7k0dBzqeU+tzmf0uUouYRrRW4fpjnm+JvEv9LNQ+zkoMiDkUFwOQTD
kEw5BMOQV0kFdJA0kFNJBAuQQLkEC5BWLaSg5xit8l2xYyig5xDo6SP1idf2nIO5U8EcEEcEYyjiaGMHY0ZBaFxAQEBAQEBAQEBA
QEBAQEGKxRZheLDV2/VxkjM4SeqRvOZ7wg5LgG5uo7lLbKjmCfotdqylZ1d4WRvszcjnvQWwUEw5BMOQS0kFdJBXSQNJBTSQRLkE
C5BDPNBau1wjtdrmq3/u281u951NHtQaxwV2eW44hlu040o6PN+kfKnlzy9gzPsVg7CqCAgICAgICAgICAgICAgIKE5IOPcJ2H32
67tvVGC2GqfpSFvkTjXn/Ft9OalGwYcvcN4twk2VDObUR7nb/QepQe1zS05FBRBXSQV0kFdJBXSQNJBTSQR0kFM0F2JnlHuQc8xf
eZLzc47bQ5ywxv0Iw395KdWY+AQdawlYYbHZYaFuRl6dTIPKld0j6BsHYtDNICAgICAgICAgICAgICAgIMPiPElssVEamukyz+Sh
brfIdzR96DkVfdcVY3ruKgbxVAx2fFA5RM7Xu8pyg8z47thK8g9Jp6x0JWdY/vYojodrutDdqMVFM7MeWw9JjtxCKvOYW7UEUBBV
AQEFEFUF2OLrd7EGnYxxe0Nfbbc/N55tRO3q3sad+8oNYtF0rMM3yCqnotIhufFzAtJY/wAph6jl1ojuWHr9br1RNq6GTTZsew6n
sd5rh1FaVl0BAQEBAQEBAQEBAQEBAQa/i/GNBh2kzflLXSj9npQdZ+k7c1BzWz4dveMrg+63aVzaTPXJszA8iIdTQoOhRUNFbKVt
JRxCONmxo+JPWUGFvNsp7jA6GpbpA9F3W072oNAmpr1hqu4+B54snJso6Dx5rx/fYojc7HjS23ECKoIpao+Q88xx+i77iis66EeS
ggY3jqQRQUQVDXHYEExC7r1IIVdbQW+EzVUrYmDrdtPoG09yDRL/AI2q7iTR2xroYH80uHysnZq2BB7cLYS4iRlZcGB0o1xQHWG9
ru1UbjcbDbb5Rcnq2Z/6cg6bHb2lBzuakxFgW8snhfnE7oSj5KZnmvG/s9ig69hfFFvxBQCppjozNyFRTE86N33g9RWhmUBAQEBA
QEBAQEBAQEGExZiikw9bTUy8+okzbS0/W9/6R1lBzbDGHLji26yXm8Oc6lLs3E6uMI8hu5oUHUDxNPE2np2hjGDRa1uoAbgg8kkW
aDyS06DH1VAyVjo5GB7HanNcMwUGn3bAmZdJb3aB/wBB+zud+aDGQXfFVhIjfpthGoRzDTj/AIT+RURm6PhKj2VlGRvdC7P7Lsvi
isnFj7DrxznyR9jmH8OkguHHOGhsqSfRG/8AJB5KjhFszM+JimmPVqDR7zn7kGEreEK71Hi6KFtNpbD8o/uz1e5B5KfDmIbvNx9a
57A7bLPnpZdjdvwQbhZsL0NuAMbNOfrmd0u7cqM9FTIPbBG5hzCD0VtuoLtQyUdZGJIn7W9YPU5p6iEHJq+ivWBcQx1FM8uhPyEp
6EsflRvG/f7VEdfw7iChvttZXUh26pYj0o39bStKyaAgICAgICAgICAgsV9dTUFHNWVL9CCBpfI7sH3oOO08dxx7ih1RPnHQRbW9
UcWepg+k7rKg6m1kFFTR0tM0RxxtDWNHUAgthBMBBQxAoLT6QFBYfQ9iDzy21rwWuaHNO0FUYiqwTZag5upGtdvjzZ/TkFBjpODS
1OObZJ2dgc0j3tTBAcGNv8qon7tD9KYPVBwdWSPpMkm9d5/DooMxR4doaT/L07Iu1rRn7dqD3soOxBfZR5ILzYAEEtHJBQOLTmNq
CF2tVBfLbJR1bc2P2HymP6nN7Qg5TaLhc8DYnfBUgupyQ2pYNkkXkyM7RtHsUR22mqYKmnjqIHiSGZofG8bC12sFaVcQEBAQEBAQ
EBAQct4VL/NWV8GG6E6WRa6pa3ypHdBncNf/AApRtmG7JBYLNHTt1zHnTv8AOkP5IPVmScztKCQQXAgmEEwgloBBXiWlBXkzFRUU
rNyAaRm5BTkrEFOIaEDQaoIkBBAoIFBbKAyQsdmO8IMJjzDLb5aeOp2519KC+nI2vb5Uff1dqDCcEuKHaTsP1TtWuShJ9r4/xDvS
Dp6oICAgICAgICDx3i5Q2y11VfN0KaMvy3nqb3nUg5bwb22a63uqvtb4wxuLg49c0msnuCg6JUy6UmXU1BAIJhBMILgQTCCYQTCC
YKonmgZoIkoIFBAqCBQWyggUECgtlBfpZdfFnuQcsx5aprBiWK7UPi2VD+UQkeTM05vHoJ196g65ZbpDdbVS3CHoVDA7Lc7Y5vc7
MLQ9qAgICAgICAg5/wAMN04mz0tuaedVy6bx9CL/AOnD2KUZPCFuFqwxTMIyle3jJPWfr9yD2BBMIJgoJscCARsKC4EEwgmCgmCg
kCglmqGaChKggSgiUECggUFsoIFBAoIaRBzG0IMfjm1tuuGKjRbnNTt5RDvzj6Q725hBhuBy8adPWWiR2uIiogH0Xc1/sOXtSDpK
oICAgICAgIOScIzzccd0Vv2sibDG4eu7Td9khQdCq+ZDHENn5BB5goJhUeSap46pFJGdQ+VP3IMi1BMIJgoJgoJAoJZoK5oGaBmg
iSggSgiSggUECgtvdkM0ESUECg9VG4Ojcw68ursKDlmGs7BwkNpNkRnfS5b2S6o/i0oO1KggICAgICAg5FW/tHC67S2CZo/kgAHw
UHQa8+MaOxBYCg8d3uPI6bm/Ly82IfE9yohY6ctj4x2tx2k7ygzAKCYKCQKCYKCWaCuaCuaBmgpmgpmgiSgiSggSggSgg/WCN6Dx
01TpZxu6bTkgvFQXqF3jiN4VHMeEIcgxsytZ0nCCp72HR/21B2taBAQEBAQEBByKq8Rwuu0thmaf54AR8VB0Cv8AlGnsQecFQapy
k3S7OmGuBnMg9UdfftVG20zBHE1qC+CgkCgmCgkCglmgrmgrmgZoKZoKZoIkoIkoIkoIEoIkoMDcZjS3LPyZQHD07CgyVPUNlZn1
qD1Un+Yb3/BBznhaI8PUo/8AFH/seg7DS5imiB2hjc/YtC6gICAgICC292SDkeP/ANgxxR3HYyQRSOd6jtB32QFKOi1mUkEco2fq
Qa3iivNPbeKYcpao8W31fKPs1KCxhujDWgkbFRsoKCYKCQKCQKCWaCWaCuaBmgZoGaCmaCmaCJKCJKCBKCJKDX8XNIo4qkbYX5H1
X6vjkg8VquezWg2u2uEsgeNmWag5vwlP5Xi6Omi1vZFFCR9JxLvg8IOzMdqWhcQEBAQEFCg8lTJkEHOeEyl5Tb4qpvTpHa/UfqPv
AUozuB7q27YbiY52c8A4mXfm3Ye9Br17e+qv5h8ikAjA+kdbvy7kGzW2HiqcbyoPaCgkCqJAoJAoJZoJZoK5oGaBmgZoKZoKZoI5
oIkoIkoIkoPBeaflNrqodpdGS0drdY94UHPKKrdE8DPUqOkYYkytzp5Totdzsz1Nb1oOc2mbw3j8VuWcRndUDPqZF8nn7GhB2amk
zCo9YQVQEBAQRegxla/UUGpXktkjkjeNJjwWuHYUGoYSuxw7iJ0E7sqOfmPPVl5DlBvFTaWy1fhCPXxpzl+5yDIN1DJQXAUEgUEg
VRIFBXNBXNBXNBXNAzQUzQM0Ec0FM0ESUESVBElBEoOeW6xy1dzkhIIp4JC2Z/qnojtKoyeNMRMoLcbRSnKeoZovDfIh2ZfxZZeh
B5+D6h4iF9Y8eMqNTPUH5lIOlUT9QVGTYgkgICAghJsQYuuGooNSuoOZQade6AVDdJvyzOid/YgzuAMZRMDbTcnaLhzYJXdf0Hdq
g3majPTh5zDry/JQebYgkCgkCglmgrmqK5oK5oGaBmgZoKZoKZoKZoI5qChKCOs6gg9ENDI7XJzW+9BqWKsS0Ni42npWtkrnucWQ
DY3SOenJl/yVRoVDS1N1r3VNU4v03aU8h8o7v72BB0S0sy0QBkBsCo26hGoIMpHsQTQEBAQReg8FXHmCg1i5051oNYrKcglBgrja
hNz2c2bfv9KDIWPHt6szhTV7TVUw2B3TA7Hdag3i347wrcmjTnbTyeZUcz7Wz3oMzFFQVDdOnkD2Hyo3Bw+9BPkEfnFA5A3zygry
FvnlBXkTfOKByJvnoK8ib55QORN88oKciZ5xQORN84oKchb5xQOQM84oKcgj84oBpaVgzedQ63HL8kGKrsY4VtuYfWRukH7uHxjv
Rzc8u8oNLvnCpXVAdDaYeSsOrj386XuHRb71Bq9LaqqrlM9U53jDpPc7W9xO0nNXBtFvo2sDWMbk0bAqNptlOdSDZ6SPIBB72IJI
CAgIKFBYmjzCDDV1JpZ6kGvVtv1nUgw81Ac9iDyTWxsjdGRgc3cUGNnwtE/XC4x9nSH5qYPGcNXSJ2lC8E9RBLSmC6I8ZQ6o6mpa
PoVBH4kFeU45H/V1v17z+JBXlmOfndb9c/8AUgcsxz87rfrn/qQOWY5+d1v1z/1IHLMc/O6365/6kFOV45+d1v1z/wBSByvHPzut
+uf+pA5Xjn53W/XP/Ugcrxx87rfrn/qQOV44+dVv1z/1IKGTG0m2prD6Z3fqQWX2S/1TtKoJcd8sml+pMF+HCcn7+XuYPvP5Jgyd
LY6eDoR87zzrKoyENAc9iDMUVv1jUg2GhpNHLUgzMMeQQXwgqgICAgIIuCDzTQAoMdUUIPUgxs1r7EHmdaexBHwT2IHgnsQPBPYg
eCexA8E9iB4J7EDwT2IHgnsQPBPYgeCexA8E9iB4J7EDwT2IHgnsQPBPYgeCexBJtp7EHphtfYgyVPQgdSDIwwAIPS0IJICAgICA
gIIlqC26IFBadTAoLZpAgpyQbkDkg3IHJBuQOSDcgckG5A5INyByQbkDkg3IHJBuQOSDcgckG5A5INyByQbkDkg3IHJBuQOSDcgq
KQILjaYBBdbEAguBqCSAgICAgICAgICCmSBohA0QgaIQNEIGiEDRCBohA0QgaIQNEIGiEDRCBohA0QgaIQNEIGiEDRCBkgqgICAg
ICD/2Q==" />';
		}
	}
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ID Card</title>
        <style>
			div.card{
				height:<?php echo $card_h; ?>; 
				width:<?php echo $card_w; ?>; 
				border:solid <?php echo $card_bordersize; ?> <?php echo $card_borderc; ?><?php echo cardround($card_radius); ?>; 
				margin-left:auto; margin-right:auto;
				margin-top:35px;
				
				<?php
				if(isset($card_gr1) && isset($card_gr2)){	//Used for gradient background
				?>
				/* Safari 4-5, Chrome 1-9 */ 
				background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $card_gr2; ?>), to(<?php echo $card_gr1; ?>));
				/* Safari 5.1, Chrome 10+ */ 
				background: -webkit-linear-gradient(top, <?php echo $card_gr1; ?>, <?php echo $card_gr2; ?>); 
				/* Firefox 3.6+ */
				background: -moz-linear-gradient(top, <?php echo $card_gr1; ?>, <?php echo $card_gr2; ?>); 
				/* IE 10 */ 
				background: -ms-linear-gradient(top, <?php echo $card_gr1; ?>, <?php echo $card_gr2; ?>); 
				/* Opera 11.10+ */ 
				background: -o-linear-gradient(top, <?php echo $card_gr1; ?>, <?php echo $card_gr2; ?>);
				<?php
				}
				?>
			}
			
			img.resize{
				width:<?php echo $img_w; ?>px; /* you can use % */
				height: auto;
				<?php if(strlen($img_borderc)>0){ ?>border: solid 1px <?php echo $img_borderc; ?>;<?php } ?>
				<?php if($img_round){?>border-radius: 10px; <?php } ?>
			}
			img.logo{
				width:<?php echo $cardlogo["logo_w"]; ?>px; /* you can use % */
				height: auto;
			}
			img.signature{
				width:<?php echo $cardsign["sign_w"]; ?>px; /* you can use % */
				height: auto;
				<?php echo $cardsign["scss"]; ?>
			}
			
			span.lname
			{
				text-transform:uppercase;
				font-weight:bold;
			}
			span.small{
				font-size: 0.7em;
				font-family:Arial, Helvetica, sans-serif;
			}
			
			span.left
			{
				float: left;	
			}
			span.right
			{
				float: right;
				text-align:right;	
			}
			
			.txtright{
				text-align:right;
			}
			.txtleft{
				text-align:right;
			}
			span.intname{
				<?php echo $intname_css ; ?>
			}
			
			div.cardheader{
				<?php echo $header_css; ?>
			}
			td
			{
				padding:0px;
			}			
		</style>
    </head>

<body>

	<?php
		//echo getimage(1);
		
	function  getid($studid){
		
		global $img_w, $intname, $cardlogo, $cardsign;
		//Function used to create the ID card
		include("data.php");	
		$con = mysql_connect($DatabaseServer,$DatabaseUsername,$DatabasePassword);
		if (!$con)
		{die('Could not connect: ' . mysql_error());}
		mysql_select_db($DatabaseName, $con);
		
		$result = mysql_query("SELECT * FROM students s WHERE s.student_id = $studid");
		//echo "SELECT * FROM students s WHERE s.student_id = $studid";
		$row = mysql_fetch_array($result);
	
		if($cardlogo["showlogo"] && file_exists("assets/int_logo.jpg"))
		{
			$logoi = "<img src=\"assets/int_logo.jpg\" class='logo' alt='logo' />";
		}
		if($cardsign["text"])
		{
			$cardsign_text = "
	<td colspan='4'>
		<span>".$cardsign["text"]."</span><img src='assets/sign_.png' class='signature' />
	</td>
			";
		}
	
		return"
		
<table width=\"100%\" border=\"0\">
  <tr>
    <td colspan='4'>
		<div class='cardheader'>
			$logoi <span class='intname'>$intname</span>
		</div>
	</td>
  </tr>
  <tr>
    <!--<td colspan='4'>
		<div class='cardheader'>
		</div>
	</td> -->
  </tr>
  <tr>
  	<td width='". ($img_w + 4) ."' rowspan=\"5\">
		".getimage($studid)."
	</td>
    <td width=\"5px\">&nbsp;</td>
    <td colspan='1'>
		<span class='lname'>".$row['last_name'].",</span><br />
		".$row['first_name'].' '.$row['middle_name']."
	</td>
	<td>
		<span class=\"right\">
			<span class='small'>Date of Birth</span><br />
			".$row['birthdate']."
		</span>
	</td>
  </tr>
  <!--<tr>
    <td>&nbsp;</td>
    <td colspan='3'>
		".$row['first_name'].' '.$row['middle_name']."
	</td>
  </tr> -->
  <tr>
    <td>&nbsp;</td>
    <td colspan='1' valign=\"top\">
		<span class='small'>Student ID Number</span><br />
		<strong>".$row[$_REQUEST["id2use"]]."</strong>
	</td>
	<td>
		<span class=\"right\">
		<span class='small'>Expire Date</span><br />
		<span>".$_REQUEST["expire_year"]."-".str_pad($_REQUEST["expire_month"],2,"0",STR_PAD_LEFT)."</span>
		</span>
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
	$cardsign_text
  </tr>
</table>
		";
		
		//return $row['last_name'];
		
		mysql_close($con);
	
	}
	?>

	





    <table width="1000px" border="0">
    <?php
		for($r = 0; $r < count($_REQUEST["sel_students"]); $r++)	//Looping throught array of student ID numbers
		{
			$tr_close = 0;		//Used to determine is the TR tag has been closed
			if(!($r %2))
			{
				echo'
      <tr>
        <td>&nbsp;</td>
        <td>
		<div class="card">
        '.getid($_REQUEST["sel_students"][$r]).'
        </div>
		</td>
				';
			}
			else
			{
				echo'
        <td '. setput($spaceb) .'>&nbsp;</td>
        <td>
        <div class="card">
        '.getid($_REQUEST["sel_students"][$r]).'
        </div>
        </td>
        <td>&nbsp;</td>
      </tr>
				';
				$tr_close = 1;
			}
	?>
		
    <?php	
		}	//End ::: for($r = 0; $r < count($_REQUEST["sel_students"]); $r++)
		
		if(!$tr_close)		//Closing tr tag if it was not
		{echo "</tr>\n";}
	?>

    </table>

<br />

</body>
</html>