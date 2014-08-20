<?php
if(!isset($_POST["task"])) die;

function connect() {
	return mysqli_connect(HOST,USER,PASS,DATABASE);
}

//draw tools...

function saveImage($udata){
	global $userImage;
	$udata = json_decode($udata);
	$xml = simplexml_load_file("avatar.xml");
	$image = imagecreatetruecolor(600,600);
	imagefill($image,0,0,imagecolorallocatealpha($image,255,255,255,0));
	$postImages = array();
	foreach($xml->categories->children() as $category){
		$item = $category->xpath('item[@id="'.$udata->{$category->getName()}.'"]');
		$item = $item[0];
		$color = @$udata->{$category->getName()."Color"};
		if($category->getName()=="mouth" || $category->getName()=="nose" || $category->getName()=="body" ){
			$color = @$udata->headColor;
		}
		$obj = getItemImageData($item,$xml,$category->getName(),$color,@$udata->head);
		if(@$obj->bimage){
			imagecopy($image,$obj->bimage,0,0,0,0,600,600);
			imagedestroy($obj->bimage);
			$postImages[] = $obj;
		}else if($item->attributes()->post=="1"){
			$postImages[] = $obj;
		}else{
			imagecopy($image,$obj->image,0,0,0,0,600,600);
			imagedestroy($obj->image);
		}
	}
	foreach($postImages as $obj){
		imagecopy($image,$obj->image,0,0,0,0,600,600);
		imagedestroy($obj->image);
	}
	imagepng($image,$userImage);
	imagedestroy($image);
}

function getItemPosition($item,$xml,$catName,$size,$headid){
	$pos = new stdClass;
	$pos->x = (int)$item->attributes()->x;
	$pos->y = (int)$item->attributes()->y;
	$pos->sx = (float)$item->attributes()->sx;
	$pos->sy = (float)$item->attributes()->sy;
	$pos->sx = $pos->sx == 0 ? 1 : $pos->sx;
	$pos->sy = $pos->sy == 0 ? 1 : $pos->sy;
	
	switch($catName){
		case "head":
			$pos->x = 300-($size[0]/2-$pos->x)*$pos->sx;
			$pos->y = 300-($size[1]/2-$pos->y)*$pos->sy;
			break;
		case "background":
			$pos->x = -100;
			$pos->y = 0;
			break;
		case "body":
		case "clothes":
			$pos->x = 300-($size[0]/2-$pos->x)*$pos->sx;
			$pos->y = 380;
			break;
		default:
			$props = $xml->categories->head->xpath("item[@id='$headid']");
			$props = $props[0]->{$catName};
			$sx = (float)@$props->attributes()->sx;
			$sy = (float)@$props->attributes()->sy;
			$sx = $sx == 0 ? 1 : $sx;
			$sy = $sy == 0 ? 1 : $sy;
			$pos->sx*=$sx;
			$pos->sy*=$sy;
			if($size!==FALSE){
				$pos->x=300+((int)@$props->attributes()->x)-($size[0]/2-$pos->x)*$pos->sx;
				$pos->y=300+((int)@$props->attributes()->y)-($size[1]/2-$pos->y)*$pos->sy;
			}
	}
	if($size!==FALSE){
		$pos->width = $pos->sx*$size[0];
		$pos->height = $pos->sy*$size[1];
	}
	return $pos;
}

function getItemImageData($item,$xml,$catName,$color,$headid){
	$obj = new stdClass;
	$imageSrc = "images/$catName/".$item->attributes()->id.".png";
	$oimageSrc = "images/$catName/".$item->attributes()->id."_o.png";;
	$bimageSrc = "images/$catName/".$item->attributes()->id."_b.png";;
	$pos = getItemPosition($item,$xml,$catName,@getimagesize($imageSrc),$headid);
	$canvas = imagecreatetruecolor(600,600);
	imagefill($canvas,0,0,imagecolorallocatealpha($canvas,0,0,0,127));
	if($image = @imagecreatefrompng($imageSrc)){
		if($color){
			colorize($image,$color);
		}
		imagecopyresampled($canvas,$image,$pos->x,$pos->y,0,0,$pos->width,$pos->height,imagesx($image),imagesy($image));
	}
	if($image = @imagecreatefrompng($oimageSrc)){
		imagecopyresampled($canvas,$image,$pos->x,$pos->y,0,0,$pos->width,$pos->height,imagesx($image),imagesy($image));
	}
	if($image = @imagecreatefrompng($bimageSrc)){
		if($color){
			colorize($image,$color);
		}
		$canvasb = imagecreatetruecolor(600,600);
		imagefill($canvasb,0,0,imagecolorallocatealpha($canvas,0,0,0,127));
		imagecopyresampled($canvasb,$image,$pos->x,$pos->y,0,0,$pos->width,$pos->height,imagesx($image),imagesy($image));
		$obj->bimage = $canvasb;
	}
	
	$obj->position = $pos;
	$obj->image = $canvas;
	return $obj;
}

function colorize($image,$color){
	list($filter_r, $filter_g, $filter_b) = sscanf($color, "%02x%02x%02x");
	$imagex = imagesx($image);
    $imagey = imagesy($image);
    for ($x = 0; $x <$imagex; ++$x) {
        for ($y = 0; $y <$imagey; ++$y) {
            $rgb = imagecolorat($image, $x, $y);
            $TabColors=imagecolorsforindex ( $image , $rgb );
            $color_r=floor($TabColors['red']*$filter_r/255);
            $color_g=floor($TabColors['green']*$filter_g/255);
            $color_b=floor($TabColors['blue']*$filter_b/255);
            $newcol = imagecolorallocatealpha($image, $color_r,$color_g,$color_b,$TabColors['alpha']);
            imagesetpixel($image, $x, $y, $newcol);
        }
    }
}

//...draw tools

switch($_POST["task"]){
	case "getUserData":
		$conn = connect();
		$r = $conn->query("SELECT ".AVATARFIELD.",".UNLOCKEDFIELD." FROM ".TABLE." WHERE ".IDFIELD."='$userid'");
		$conn->close();
		if($r = $r->fetch_assoc()){
			echo "{\"sex\":$usersex,\"current\":".$r[AVATARFIELD].",\"unlocked\":".$r[UNLOCKEDFIELD]."}";
		}
		break;
	case "saveUserData":
		$conn = connect();
		$r = $conn->query("UPDATE ".TABLE." SET ".AVATARFIELD."='".$conn->real_escape_string($_POST["userdata"])."' WHERE ". IDFIELD ."='$userid'");
		$conn->close();
		saveImage($_POST["userdata"]);
		break;
}
?>