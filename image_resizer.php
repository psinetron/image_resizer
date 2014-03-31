<?php

/*
 * SlyResizer
 * Main classes
 * v 0.0.1
 * created by Fail Shahmayev
 * http://slybeaver.ru/
 * https://github.com/psinetron
 */
class sly_resizer{

/*
 * $file_input - file for resize
 * $needlyWidth - width of the final image
 * $needlyHeight - height of the final image
 * $file_output - path to record the final image
 * $fromX=false, $fromY=false - If you want to crop the image is not from the center, then enter the coordinates of the original image from which you want to start
 */



public function sly_imageResizer($file_input, $needlyWidth=100, $needlyHeight=100, $file_output=false, $fromX=false, $fromY=false) {
    list($realWidth, $realHeight, $file_type) = getimagesize($file_input);
    if (!$file_output){$file_output = __DIR__ . 'uploads\\'.md5(time()). basename($file_input);}
    if (!$realWidth || !$realHeight) {
        //'Unable to get the length and width of the image';
        return false;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$file_type];
    if ($ext) {
        $func = 'imagecreatefrom'.$ext;
        $img = $func($file_input);
    } else {
        // 'Inorrect file type';
        return false;
    }
    $origY=round($needlyHeight*$realWidth/$needlyWidth);
   if (($needlyWidth>=$needlyHeight)&&($origY<=$realHeight)){
       $origX=$realWidth;
   } else {
       $origY=$realHeight;
       $origX=round($needlyWidth*$realHeight/$needlyHeight);
   }
    if (!$fromX){$fromX=abs(round(($origX-$realWidth)/2));}
    if (!$fromY){$fromY=abs(round(($origY-$realHeight)/2));}

    if ($fromX+$origX>$realWidth){$fromX-=$fromX+$origX-$realWidth;}
    if ($fromY+$origY>$realHeight){$fromY-=$fromY+$origY-$realHeight;}


    $img_output = imagecreatetruecolor($needlyWidth, $needlyHeight);

    $transparent = imagecolorallocatealpha($img_output, 0, 0, 0, 127);
    imagefill($img_output, 0, 0, $transparent);
    imagesavealpha($img_output, true); // save alphablending setting (important);

    imagecopyresampled($img_output, $img, 0, 0, $fromX, $fromY, $needlyWidth, $needlyHeight, $origX, $origY);
    if ($file_type == 2) {
        imagejpeg($img_output,$file_output,100);
    } else {
        $func = 'image'.$ext;
        $func($img_output,$file_output);
    }
    return $file_output;
}



/* proportional resizing
 * $file_input - file for resize
 * $max_width - maximal width of the final image
 * $max_height - maximal height of the final image
 * $file_output - path to record the final image
 * $fromX=false, $fromY=false - If you want to crop the image is not from the center, then enter the coordinates of the original image from which you want to start
 */

    function prop_resize($file_input, $max_width=100, $max_height=100, $file_output=false){
    list($realWidth, $realHeight, $file_type) = getimagesize($file_input);
    if (!$file_output){$file_output = __DIR__ . 'uploads\\'.md5(time()). basename($file_input);}
    if (!$realWidth || !$realHeight) {
        //'Невозможно получить длину и ширину изображения';
        return false;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$file_type];
    if ($ext) {
        $func = 'imagecreatefrom'.$ext;
        $img = $func($file_input);
    } else {
        // 'Некорректный формат файла';
        return false;
    }

    if ($realWidth>=$realHeight){
        $needlyWidth=$max_width;
        $needlyHeight=round($realHeight/($realWidth/$needlyWidth));

    } else {
        $needlyHeight=$max_height;
        $needlyWidth=round($realWidth/($realHeight/$needlyHeight));

    }
    $img_output = imagecreatetruecolor($needlyWidth, $needlyHeight);
    $transparent = imagecolorallocatealpha($img_output, 0, 0, 0, 127);
    imagefill($img_output, 0, 0, $transparent);
    imagesavealpha($img_output, true); // save alphablending setting (important);

    imagecopyresampled($img_output, $img, 0, 0, 0, 0, $needlyWidth, $needlyHeight, $realWidth, $realHeight);
    if ($file_type == 2) {
        imagejpeg($img_output,$file_output,100);
    } else {
        $func = 'image'.$ext;
        $func($img_output,$file_output);
    }
    return $file_output;
}

}

?>