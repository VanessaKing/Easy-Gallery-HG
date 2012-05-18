<?php
$title = "Easy Gallery HG";

// GET DATA/PHOTOS
$data = (array) json_decode( @file_get_contents("photos.json"));

// BUILD IMAGES
if(isset($_GET['action']) && $_GET['action'] == "build") { buildImages(); }
if(isset($_GET['download']) && $_GET['download'] != "") { downloadImage(); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width">
	<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
	<style>
		body { font-family: 'Oxygen', sans-serif; font-size: 12px; padding: 0; margin: 0; background: #f5f5f5; }
		.wrap { width: 85%; margin: 2% auto; background: white; padding: 0 5% 2% 5%; }
		.header { background: #ccc; padding: 15px 25px; margin-bottom: 25px; min-height: 30px; line-height: 30px; }
		.download { float: right; line-height: 30px; } .download a { color: #fff; text-decoration: underline; margin-left: 15px; }
		h1 { margin: 0; font-size: 2em; font-weight:normal; color: #666; color: #fff; }
		h1 a { color: #fff; }
		a { color: #399ae5; text-decoration: none; } a:hover { color: #206ba4; text-decoration: underline; }
		#media_container { position: relative; padding: 0; }
		#media_container ul { list-style: none; padding: 0; margin: 0; }
		#media_container ul li { width: 175px; height: auto; padding: 0; margin: 0; }
		#media_container ul li img, .wrap { -webkit-box-shadow:0 0 4px rgba(0, 0, 0, 0.3); -moz-box-shadow:0 0 4px rgba(0, 0, 0, 0.3); box-shadow:0 0 4px rgba(0, 0, 0, 0.3); }		
		.message { padding: 10px; margin: 0 0 25px 0; background: #8dc258; color: #fff; font-size: 1.5em; }
		.message.error { background: #d95252; }
		.image_single { text-align: center; margin-bottom: 20px;  }
		.image_single img { width: 100%; max-width: 900px; text-align: center; }
		.image_single_header { padding: 10px; text-align: center; margin-bottom: 25px; }
		.image_single_header a { padding: 5px 10px; background: #efefef; }
	
		/*! fancyBox v2.0.6 fancyapps.com | fancyapps.com/fancybox/#license */
		.fancybox-tmp iframe, .fancybox-tmp object { vertical-align:top;padding:0;margin:0;} .fancybox-wrap { position: absolute;top:0;left:0;z-index:8020;}.fancybox-skin {position: relative;padding:0;margin:0;background: #f9f9f9;color: #444;text-shadow: none; }
		.fancybox-opened {z-index: 8030;} .fancybox-opened .fancybox-skin { box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5); } .fancybox-outer, .fancybox-inner {padding:0;margin:0;position:relative;outline: none;}
		.fancybox-inner {overflow: hidden;} .fancybox-type-iframe .fancybox-inner { -webkit-overflow-scrolling: touch; }
		.fancybox-error {color: #444;font: 14px/20px "Helvetica Neue",Helvetica,Arial,sans-serif;margin: 0;padding: 10px;}
		.fancybox-image, .fancybox-iframe {display:block;width: 100%;height:100%;border:0;padding:0;margin:0;vertical-align: top;}
		.fancybox-image {max-width: 100%;max-height: 100%;}
		#fancybox-loading, .fancybox-close, .fancybox-prev span, .fancybox-next span {background-image: url(data:image/png;base64,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);} 
		#fancybox-loading {position: fixed;top: 50%;left: 50%;margin-top: -22px;margin-left: -22px;background-position: 0 -108px;opacity: 0.8;cursor: pointer;z-index: 8020;} #fancybox-loading div {width:44px;height:44px;background: url(data:image/gif;base64,R0lGODlhGAAYAPcIADo6OkVFRTQ0NMnJydPT04yMjC8vL7y8vB0dHXl5eX5+fu7u7lJSUqGhoQ0NDSsrK62treXl5ZSUlMLCwhgYGKenp7S0tLu7u/X19fr6+kNDQ5ycnBISEgQEBFtbW7GxsRcXFzAwMCMjI2ZmZoaGhktLS83NzQoKClxcXE1NTc7OzhQUFGVlZQUFBVRUVG9vbxkZGZWVlebm5tzc3NTU1OLi4jExMVlZWUBAQJ6eniYmJqKiopqamoeHh6+vr8PDwwkJCT8/P5OTkw8PDykpKRAQECEhIYmJiYiIiCQkJF1dXS0tLSAgILCwsAgICBERETMzM4WFhb29vSoqKgsLC6Ojo7+/v5mZmfT09Pn5+Tg4OLa2trq6utDQ0CIiIp2dnaurqwwMDD09PUdHR66urkRERExMTNXV1eHh4ZaWlhMTE9vb28/Pzzw8PKCgoBYWFqysrL6+vlpaWm5ubp+fn0ZGRsDAwJubmz4+Pg4ODjk5OZCQkAYGBicnJywsLDIyMnh4eAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/i1NYWRlIGJ5IEtyYXNpbWlyYSBOZWpjaGV2YSAod3d3LmxvYWRpbmZvLm5ldCkAIfkEBQoACAAsAAAAABgAGAAAB/+ACIKDhAg2NoWJiUAtgkMHB0OKkys8L44qKpIIMEaNkwgrBzQGCEOZkkA2RJ+gLjJCLaeanAJFlCuCQDs1AUCoRQI6gy2tLQU+KUAIADMxQDc3LUY2ki1FREbELD8EMQJAKcODQLdAOgI2MIVEPSoDAZNDh0bLiS04R9qKQEa3hUACMrIHSlAxQUaE8FiYI0jBIgaISCTSL8aOHBhxPCQSkYiOVEOAhGw1qdHBREM8Ter3bxE1Gy0LFaFHUBAMG8IkhSR0zRQ6dYSMECnSSJW2IreobVqRjWcrdLc4yrKxD8HJRDO1tZCI0FZBq0RsLNvKytQqkveMrBMUcVAntF8IdRD5+lXk10AAIfkEBQoABgAsAAAAABgAGAAAB/+ABoKDhC1LD4SJioItToJOTRAOjC2LiQUsgg5SUpMGCRUUloMQE4ibnQZQESqiowYpBFEtqA4tDRlKlk9PjxIqGk6cTikYTY6LChvBBgIDSE4MDE4NCxqCTw9MhEpNUlFLTgFJhA8MtElQUBSVg0kKFxYAu+pMyIoACgiWTkyehQbatXs1qJKTJA8SLuk16smhhEmcOEjy8AHDXQ8PTWpRaSBBj4mebLPUgsnFRE4QqDtJ6AkUAfYSUVCXZJItQhINTBQAZQUhJgs1QdnGywATKBtXaCOZRECvB0toCSBH0MATmIISCmIiwCdBqMi06lyypCoTVy0StqMwsuogsW4GFzm5ZykQACH5BAUKAAIALAAAAAAYABgAAAf/gAKCg4QCU0mFiYkdHYJUDQ1Ujo2KgxwKHoJhZGRhgi9XIJWCHF9kiJtgngY0XKKjAmNWCQJUZGCSMTIulYyOSFxatrgBNZGCMDCEXlMcgjZbCVRjY1QxMwACYR5dVZLINjZJYR1iCIRT1WIfWTIJlI4IWjaeigwLWA02lWFe34lTqqSAN4gSQUUGB1GZwpChs1EcDEyROIXKQoYSH1bi0FDiP1iLICL4SIgKgnqJwsijtzGcv0IwDIgjF+YjFU9hkmgx8EqQFwMPw9jwIoCDMwQG6nEkWpCgFk8Mi2oh5EsRBy1EO0QV4EWLxl4TvzFsREXiwUIdvCgToHXKIBheC87CSuIWJCyLIAMBACH5BAUKAAAALAIAAAAWABcAAAfegACCg4MiIoSIiYgSBYqKHSIwgg45V1SCIwUcjh1TIZdUlZdTExBqjgBqAoeUVx0dJARmqII6IQ6hljhsEoSnAIeDDiEiHTg4HUgDAgBULhOugiEhOg4dHB2EImVUAlURZy+XgiICt45ma2hpD4kOwYo6aWWJ2bSCVFQOHA4OAA8A29G6waYgGysPpkwRONAgmwkhBNmjpa9fP0IOJKGC4Q8RlXICOiqaBi9VCAHVrrkD4ECHOY06HvxyIEAjuXOpppQcZGsTIWr3AKxKJCLiPYaIkMajtZOWDkQTBwUCACH5BAUKAAEALAIAAAAWABcAAAfRgAGCg4NUVISIiYhTSYMdiogUg1NTjwFKCpCJHZSDPjwcmqGTlR0KF2WaAR1JIRwdD1NUbRc9gysrmggdHK8KFg+CZhZIghQhAo0BDoeDCABUITEEPyyWgiGqZQMqSFOqiiI9beCqDufMAZSdqgw/Fxc/FutTwe3w8MDlkM2DIteIHuwwA3BQCFeQGCzAsiOEJUkBlK0i1MFBhzIWsshI8EjEg1EBQiAQlEvQISpKVFToJ4gVIksiBlGASKikIHu3wIVwsC+RCJqRegpKkqQgokAAIfkEBQoAAAAsAAAAABgAGAAAB8uAAIKDhABAQIWJiokdi46FS4MUIo2Pgg6CS1ONQJqVlooiAhyghVOCHCFJg2+kjyKEIkuYQHV0Cpafhg4dIUhxEHKFiJZBW1wJq47EiQgJWo66ix2HDoeGU9lTkY8pEN9wblNA2pqWJd/f4oLSy4dA1omui1MxdYvMjikzaDHcpYKmuACipUqEM3MgFYIFgEMHNwsCGHJhIkY7QpgGpcAAgdibN4tADjplyE0GYaUQddgmKEQEExQAZtokKEGVmAA7JCFpKF9Kn4kCAQAh+QQFCgABACwAAAAAGAAYAAAH0YABgoOEAVRUhYmKhA+LjoIwgw+NkI+SIYgBk4JUBpSPK4yfloubgx2CHaiMlgaDHAYihJGEHIWZDkkCIaGEmY8cISEiv4rFhB0iDqSWVA7OiFSTk66PZXfYbkLE06aOAHc8PHfbzL6HVB3HAQ7EiyJIAIuZBsuKZQMEPaMBtIPPhJKkoBIiBgE7LAqNitXhxg0qMWbIo5LCRwFSDlSooFKmxg57K3olEplRhYMOQmS4IPUgxLKSywzQOCDSES0HBEwKesHDH0Y7duypMzdImKVAACH5BAUKACkALAAAAAAYABgAAAatwJRwSEydTsWkcslUUpbPJtGRREqFnKs26RBsmaEmxdDsZIUhA6hoTZ3LSw6irXwPO18l/coxPPwPR3kcfw+Ge1d4TRwiiEIICUyORQAWFwpJIAZhQgwPRCIBHQYkBxAoRWRCGgsNJwwMJyQDXicBG5h6HxglJwcHJxoEElYcdkQeGQ0dDr8nHSQEJVIUJhGcEwdCDxMQa0wUFZFCv0MjBcdJHVYnHx9til9/UkEAIfkEBQoAAAAsAAAAABgAGAAAB9mAAIKDhABUVIWJiouMiV6NSY2EiIVUD5KEj4WamACcgxwhjZ9DIZ9eIUODD58AogBDHUOUg7FDSQIhFJWtiUMPAl60hUPCi1S9nYUdgsyGD9DQmNHQVM7KgtFJqsuYHdeEHSk7l4odXtzLITtYCwyLoabDHQkyWR94VCWRgwh4HVRw6RpEpUIXD4i0zIihLwW9La84sCJEYZehHTUCUAEDh4qWA0gofVvEQEYMgBytJbASQNKbAzQuDQEDBlESOF84NHpz5YWgmWC4eVDQiRKVHTuGYRvEj1EgACH5BAUKAAAALAAAAAAYABgAAAfjgACCg4QAQECFiYqDLUkPi4stIoktgyKVkII2eQCYgkA2D56Zk4wAIgJqkZ5JNhyeeTaELaOlgrGTqqcGnJaFBrcAeaMtnHmtsoVAIjbJirE2IoiKHDDTlLaEedvbmYWYNnYq4yo33gYP6Q9ABlbk5d6D6MbcedeQo7MBaUmRIr2JDKRBs8YMpGbSZr04E6GKHiABsgHhIEzTLB52XCASMABJCxw4WsDYRCibLkNpVITkkQNIrH6CaC0yc8YjEJacUFHMpAaCnU8sEbETxbPACKA5eqm55A0IJiB70tyLVyjbokAAIfkEBQoAAQAsAAAAABgAGAAAB/eAAYKDhAFUVIWJioRERIuLfIWNkYOUkEQhiHyNglR+RJaPhH5+lCEcixQUgyIhYZtEVGEhfZR8lnwVXR5hAbOCqIKug46VCTJZH2J8YYiVqGF9AiErhSE7WAsMi7MCIs6FfGY7xYmhhGHpzeCPfFSUfh8XcfTbop9En54f9PWiAY0C9lK37l+AW+cOikEiYhEVBL0UEemhYkCZRyFCfCPEh8WPLjEEUAGAgJAsX9JCcCzgwwwiPxYS8OGACsGwACvKCVpRzVCPOAAAOuJAq1JCQWXiyOTz6egiDjx8FJtkqKkoDgo8CII1aIUIp0YH9dFpMNGhf4EAACH5BAUKAAAALAAAAAAYABgAAAj/AAEIHEgQwIkTBRMm5INQYJ8+CiMCoFAhwcApUwgykSiQgokIfwRi5GNwSUaOAFBkqIJwyhKBTP4UifjmjcAwTTCk4INRYEiJV+K4QBhkQRU+RWbGHFhkysaBc85EqKLlBAOIA0/MPNHnzx8KBZekQTOjRMQiXpmEiRggDdaEJ9QqPEgXpUA+eAFMqQIBDhwIZiUWMYmxD9cqfiEA5jgYI0aEdOvalagFEIKIcWcq7JOAy5YgEcP8EcCk4UA5EOIg+YPUNACkA70WVEAnAOQ/TJAq/QngjVOCRWw6/LPWJZ8wAiYDQNsHr0uYAjRL5Lmk4fOSJ6czAStyCsmJT5XrCH0rnuBBlAEBADs=) center center no-repeat;}
		.fancybox-close {position: absolute;top: -18px;right: -18px;width: 36px;height: 36px;cursor: pointer;z-index: 8040;}
		.fancybox-nav {	position: absolute;	top: 0;	width: 40%;	height: 100%; cursor: pointer; background: transparent url(data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==); /* helps IE */ -webkit-tap-highlight-color: rgba(0,0,0,0);	z-index: 8040; }
		.fancybox-prev {left: 0;}.fancybox-next {right: 0;} .fancybox-nav span {position: absolute;top: 50%;width: 36px;height: 34px;margin-top: -18px;cursor: pointer;z-index: 8040;visibility: hidden;}
		.fancybox-prev span {left:20px;background-position: 0 -36px;} .fancybox-next span {right:20px;background-position: 0 -72px;} .fancybox-nav:hover span {visibility: visible;}
		.fancybox-tmp {position:absolute;top:-9999px;left:-9999px;padding:0;overflow:visible;visibility:hidden;} #fancybox-overlay {position:absolute;top:0;left:0;overflow:hidden;display:none;z-index:8010;background:#000;} #fancybox-overlay.overlay-fixed {position:fixed;bottom:0;right:0;}
		.fancybox-title {visibility: hidden;font: normal 12px/20px "Helvetica Neue",Helvetica,Arial,sans-serif;position: relative;text-shadow: none;z-index: 8050;}
		.fancybox-opened .fancybox-title {visibility: visible;}
		.fancybox-title-float-wrap {position: absolute;bottom: 0;right: 50%;margin-bottom: -35px;z-index: 8030;text-align: center;}
		.fancybox-title-float-wrap .child {display: inline-block;margin-right: -100%;padding: 2px 20px;background: transparent;background: rgba(0, 0, 0, 0.8);text-shadow: 0 1px 2px #222;color: #FFF;font-weight: bold;line-height: 24px;white-space: nowrap;}
		.fancybox-title-outside-wrap { position: relative;margin-top: 10px;color: #fff; }.fancybox-title-inside-wrap { margin-top: 10px;} .fancybox-title-float-wrap .child a { padding: 0 5px; }
		.fancybox-title-over-wrap {position: absolute;bottom: 0;left: 0;color: #fff;padding: 10px;background: #000;background: rgba(0, 0, 0, .8);}
	</style>
	<script src="http://code.jquery.com/jquery-1.7.2.min.js" type="text/javascript"></script>
	<script src="http://dl.dropbox.com/u/6771946/jquery.wookmark.min.js" type="text/javascript"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/fancybox/2.0.6/jquery.fancybox.pack.js" type="text/javascript"></script>
	<script type="text/javascript"> $(document).ready(function() { $(".fancybox").fancybox(); if($('#media_container')) { $('#media_container ul li').imagesLoaded(function() { $('#media_container ul li').wookmark({container: $("#media_container"), offset: 15, itemWidth: 175, autoResize: true }); }); } });</script>
</head>
<body>
	<div class="wrap">
		<div class="header">
			<?php if( file_exists("Archive.zip")) { ?>
			<div class="download"><a href="Archive.zip">Download All Files</a></div>
			<?php } ?>
			<h1><a href="?all"><?php echo $title ?></a></h1>
		</div>
		
		<?php /*   MESSGAE  */ if(isset($_GET['msg']) && trim($_GET['msg']) != "") { echo "<div class=\"message\">" . stripslashes($_GET['msg']) . "</div>"; } ?>
		<?php /* BUILD LINK */ if(!$data['photos']) { echo "<b>No pictures found.</b><br />Make sure you have added your pictures to the 'photos' folder' and then click: <a href=\"?action=build\" onclick=\"this.innerHTML='Building Images... (this could take a few minutes)'\">Build Images</a>";  } ?>

		<?php 
			// PHOTO VIEW
			if(isset($_GET['view'])) 
			{ 
				$img_key = checkForImage($_GET['view']);
				
				// PHOTO NOT IN DATA, WE DONT JUST OPEN EVERYTHING
				if($img_key === FALSE)
				{ 
					echo "<div class=\"message error\">Photo Not Found</div>"; 
				}
				else
				{
					$prev = $data['photos'][$img_key-1];
					$next = $data['photos'][$img_key+1];
					
					$prevnext =  "<div class=\"image_single_header\">\n";
						if($img_key != 0) { $prevnext .= "	<a href=\"?view=$prev\">&laquo; prev</a>"; }
						$prevnext .= " <a href='?download=" . $_GET['view'] . "'>download</a> ";
						if($img_key != count($data['photos']) - 1) { $prevnext .= "	<a href=\"?view=$next\">next &raquo;</a>"; }
					$prevnext .=  "</div>\n\n";
					
					echo $prevnext;
					echo "<div class=\"image_single\"><img src=\"photos/_lrg/" . $_GET['view'] . "\"></div>";
					echo $prevnext;
				}
				
			} else { ?>	

		<div id="media_container">
			<ul>
				<?php foreach((array) $data['photos'] as $photo) { echo "<li><a href=\"photos/_lrg/$photo\" class=\"fancybox\" rel=\"group\" title=\"<a href='?view=$photo'>view</a> or <a href='?download=$photo'>download</a>\"><img src=\"photos/_sml/$photo\"></a></li>\n"; } ?>
			</ul>
		</div>
		
		<?php } ?>
		
	</div>
</body>
</html>
<?php

// FUNCTIONS

function getFileExt($filename) 
{
	$filename = strtolower($filename) ;
	$exts = split("[/\\.]", $filename) ;
	$n = count($exts)-1;
	$exts = $exts[$n];
	return $exts; 
}

function buildImages()
{
	global $data;
	
	// REBUILD ALL
	$rebuild_all = false;
	if($data && $data['rebuild_all']) { $rebuild_all = true; }
	
	// LOCKED
	if($data && $data['locked'])
	{
		die("<b>Building of images is locked.</b><br />Edit the file photos.json and set the variable 'locked' to 'false'");
	}
	
	set_time_limit(0);
	// MAKE DIRECTORIES
	if(!is_dir("photos/_sml")) { mkdir("photos/_sml"); }
	if(!is_dir("photos/_lrg")) { mkdir("photos/_lrg"); }
	if(!is_dir("photos/_sml")) { die("Cannot create directories on your file system. Place check your permissions"); }

	// RESIZE IMAGES
	$handle=opendir(dirname(__FILE__) . "/photos");
	while (($file = readdir($handle))!==false) 
	{ 
		$ext = getFileExt($file);
		if($ext != "jpeg" && $ext != "jpg") { continue; }
		
		if(!$rebuild_all)
		{
			if(file_exists("photos/_lrg/$file") && file_exists("photos/_sml/$file")) { continue; }  // ALREADY HAVE BOTH IMAGES
		}
		
		$sizes = getimagesize("photos/$file");
		$width = $sizes[0]; $height = $sizes[1];
			
		// LRG
		if($width == $height) { $new_width = 900; $new_height = 900; }
		else if($width > $height)  { $new_width = 900; $aspect_ratio = $width / $new_width; $new_height = abs($height/$aspect_ratio); }
		else { $new_height = 700; $aspect_ratio = $height/$width; $new_width = abs($new_height/$aspect_ratio); }
		resizeImage($file, "photos/_lrg", $new_width, $new_height);
			
		// SML
		if($width == $height) { $new_width = 175; $new_height = 175; }
		else { $new_width = 175; $aspect_ratio = $width / $new_width; $new_height = abs($height/$aspect_ratio);}
		resizeImage($file, "photos/_sml", $new_width, $new_height);
	}
	closedir($handle);
	
	// SAVE JSON FILE OF IMAGES
	$files = array();
	if(is_dir("photos/_lrg"))
	{
		$handle=opendir(dirname(__FILE__) . "/photos/_lrg");
		while (($file = readdir($handle))!==false) 
		{ 
			$fileExt = getFileExt($file);
			if(!in_array($fileExt, array('jpg','jpeg')) OR is_dir($file)) { continue; }
			if(!file_exists("photos/_sml/$file")) { continue; }
			$files[] = $file;
		}
		closedir($handle);	
	}
	sort($files);
	
	$data = array(
				'locked' => true,
				'rebuild_all' => false,
				'built' => true,
				'built_at' => time(),
				'photos' => $files
			);
	
	file_put_contents("photos.json", json_encode($data));
	
	header("Location: ?msg=Build Complete");
	exit;
}

function resizeImage($file, $folder, $new_width, $new_height)
{
	$img = "photos/$file";
	$destimg 	= ImageCreateTrueColor($new_width,$new_height);
	$srcimg 	= ImageCreateFromJPEG($img);
	$new_image	= dirname(__FILE__)."/" . $folder . "/" . $file;
	
	ImageCopyResampled($destimg, $srcimg, 0,0,0,0, $new_width, $new_height, ImageSX($srcimg), ImageSY($srcimg));
	ImageJPEG($destimg, $new_image, 90);
	imagedestroy($destimg);
	imagedestroy($srcimg);
}

function checkForImage($img)
{
	$ext = getFileExt($img);
	if($ext != "jpg" AND $ext != "jpeg") { return false; }

	global $data;
	return array_search($img, $data['photos']); 
}

function downloadImage()
{
	$img = checkForImage($_GET['download']);
	if($img === FALSE) { die("FILE COULD NOT BE DOWNLOADED"); }
	
	$download = "photos/" . $_GET['download'];
	
	// Set headers
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Disposition: attachment; filename=$download");
	header("Content-Type: image/jpeg");
	header("Content-Transfer-Encoding: binary");
	
	// Read the file from disk
	readfile($download);
	die;
}

if(!is_dir("photos")) { mkdir("photos"); }

?>