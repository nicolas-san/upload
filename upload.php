<?php

/**
 * @author Bouteillier Nicolas <contact@kaizendo.fr>
 * Date: 13/01/16
 *
 * @Param string $varName Name of the form input var
 *
 * @Param string $directoryImagePath Path of the directory where to save the image ex: '../images/' - The final / is needed
 * 
 * @Return string|int $pictureName string name of the new image or error code
 *
 * Error codes are :
 * -1 if varName ! isset
 * -2 if more than one extention ex: image.php.jpg
 * -3 extension not allowed (other than jpg, gif and png)
 * -4 mime type not allowed (other than image/gif, image/jpeg,image/png )
 * -5 imagecreate* failed, image not valid
 * -6 $directoryImagePath is not writable
 * -7 move_uploaded_file error
 * -8 can't delete moved file (you have to check if the file is still there and delete it)
 * -9 can't save GD created image, or destroy the return of imagecreate*
 *
 */

function saveUploadedFile($varName, $directoryImagePath)
{
    if (isset($_FILES[$varName])) {
        $file = $_FILES[$varName];
        /* first test only to block more than one extension file */
        if (count(explode('.', $file['name'])) > 2) {
            return -2;
        } elseif (preg_match("`\.([^.]+)$`", $file['name'], $match)) {
            /* here file has just one extension */
            $ext = strtolower($match[1]);
            if ($ext != 'jpg' && $ext != 'png' && $ext != 'gif') {
                return -3;
            } else {
                /**
                 * extension is ok
                 * third test with fileinfo to get the mime type with magic bytes
                 */
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileType = finfo_file($finfo, $file['tmp_name']);
                /* fourth test depends on the extension, imagecreate with gd, to check if the image is valid and remove all exif data to avoid code injection */
                switch ($fileType) {
                    case 'image/gif':
                        $logoRecreated = @imagecreatefromgif($file['tmp_name']);
                        /* fix for transparency */
                        imageAlphaBlending($logoRecreated, true);
                        imageSaveAlpha($logoRecreated, true);
                        $extSafe = 'gif';
                        break;
                    case 'image/jpeg':
                        $logoRecreated = @imagecreatefromjpeg($file['tmp_name']);
                        $extSafe = 'jpg';
                        break;
                    case 'image/png':
                        $logoRecreated = @imagecreatefrompng($file['tmp_name']);
                        /* fix for transparency */
                        imageAlphaBlending($logoRecreated, true);
                        imageSaveAlpha($logoRecreated, true);
                        $extSafe = 'png';
                        break;
                    default:
                        return -4;
                        break;
                }
                if (!$logoRecreated) {
                    /* imagecreate* failed, the image is not good */
                    return -5;
                } else {
                    /** 
                     * valid image, good mime type
                     * destination is writable ?
                    */
                    /* generate random failename */
                    $randName = md5(uniqid(rand(), true));
                    $pictureName = $randName.'.'.$extSafe;
                    $picturePath = $directoryImagePath.$pictureName;

                    if (is_writable($directoryImagePath)) {
                        /* usage of move_uploaded_file to check on more time if the file is good (is it too much ?) */
                        $moveUploadReturn = move_uploaded_file($file['tmp_name'], $picturePath);
                        if (!$moveUploadReturn) {
                            return -7;
                        } else {
                            /* move_uploaded_file return is ok, I delete the file, and use the GD created exif free file instead */
                            $unlinkReturn = unlink($picturePath);
                            if (!$unlinkReturn) {
                                return -8;
                            } else {
                                /* the file is deleted, saving the new image */
                                switch ($extSafe) {
                                    case 'gif':
                                        $retourSaveImage = imagegif($logoRecreated, $picturePath);
                                        break;
                                    case 'jpg':
                                        $retourSaveImage = imagejpeg($logoRecreated, $picturePath);
                                        break;
                                    case 'png':
                                        $retourSaveImage = imagepng($logoRecreated, $picturePath);
                                        break;
                                }
                                $retourDestroy = imagedestroy($logoRecreated);
                                if (!$retourSaveImage || !$retourDestroy) {
                                    return -9;
                                }
                                /*
                                 * All tests are passed, the function return the path of the new image
                                 * */
                                return $pictureName;
                            }
                        }
                    } else {
                        return -6;
                    }
                }
            }
        }
    } else {
        return -1;
    }
}

var_dump($_FILES);
echo "<br>";

echo saveUploadedFile('file', './');