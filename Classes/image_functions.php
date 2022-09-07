<?php
function thumbnail( $img, $source, $dest, $maxw, $maxh ) {      
    $jpg = $source.$img;

    if( $jpg ) {
        list( $width, $height,$type  ) = getimagesize( $jpg ); //$type will return the type of the image
        if( $maxw >= $width && $maxh >= $height ) {
            $ratio = 1;
        }elseif( $width > $height ) {
            $ratio = $maxw / $width;
        }else {
            $ratio = $maxh / $height;
        }

        $thumb_width = round( $width * $ratio ); //get the smaller value from cal # floor()
        $thumb_height = round( $height * $ratio );
        $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
		
		
		switch($type)
		{
		case 1:
		$source = imagecreatefromgif($jpg);
        imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height );
		$path = $dest.$img;
        imagejpeg( $thumb, $path, 75 );
		imagedestroy( $thumb );
   		 imagedestroy( $source );
		break ;
		
		case 2:
		$source = imagecreatefromjpeg($jpg);
        imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height );
		$path = $dest.$img;
        imagejpeg( $thumb, $path, 75 );
		imagedestroy( $thumb );
        imagedestroy( $source );
		break;
		
		case 3:
		//png
		$source = imagecreatefrompng($jpg);
        imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height );
		$path = $dest.$img;
        imagejpeg( $thumb, $path, 75 );
		imagedestroy( $thumb );
   		 imagedestroy( $source );
		break;
		
		/*case 6:
		$source = imagecreatefromwbmp($jpg);
        imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height );
		$path = $dest.$img;
		imagewbmp( $thumb, $path, 75);
        //imagejpeg( $thumb, $path, 75 );
		imagedestroy( $thumb );
   		 imagedestroy( $source );
		break;*/
		default:
		echo '<p>Unknown file format</p>';
		break;
		}
    }
    
}


?>