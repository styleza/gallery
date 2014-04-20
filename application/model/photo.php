<?php

class model_photo {
    private $photoTable;
    private $tagModel;
    
    
    public function __construct(){
        $this->photoTable = new model_table_photo();
        $this->tagModel = new model_tag();
    }
    
    public function addImage($uploadFileArray,$description,$tags){

        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($uploadFileArray['error']) ||
            is_array($uploadFileArray['error'])
        ) {
            throw new RuntimeException('invalid_parameters');
        }

        // Check $_FILES['upfile']['error'] value.
        switch ($uploadFileArray['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('no_file_sent');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('oversized_file');
            default:
                throw new RuntimeException('unknown_error');
        }

        // You should also check filesize here. 
        if ($uploadFileArray['size'] > 1000000) {
            throw new RuntimeException('oversized_file');
        }

        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($uploadFileArray['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ),
            true
        )) {
            throw new RuntimeException('invalid_file_format');
        }

        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.


         $dbEntry = $this->createDbRow(md5(sha1_file($uploadFileArray['tmp_name']).mt_rand().time()),
                 $uploadFileArray['name'],
                 resources::get('session')->user->id,
                 0,
                 $description);
         
        if (!move_uploaded_file(
            $uploadFileArray['tmp_name'],
            resources::get('config')->get('uploadDir',APPLICATION_PATH . '/upload') . '/' . $dbEntry->file_id
        )) {
            $dbEntry->delete();
            throw new RuntimeException('failed_to_move_file');
        }
        if(!empty($tags)){
            $this->tagPhoto($dbEntry->id,$tags);
        }
        
        return $dbEntry;

    }
    private function createDbRow($file_id,$file_name,$user_id,$visibility,$description){
        $dbEntry = $this->photoTable->createRow();
        $dbEntry->file_id = $file_id;
        $dbEntry->file_name = $file_name;
        $dbEntry->user_id = $user_id;
        $dbEntry->visibility = $visibility;
        $dbEntry->description = $description;
        $dbEntry->short_url_id = auth::generateSalt(8);
        $dbEntry->rating_sum = 0;
        $dbEntry->rating_count = 0;
        $dbEntry->save();
        return $dbEntry;
    }
    
    private function tagPhoto($photoId,$tagString){
        $tagIds = $this->tagModel->solveTags($tagString);
        $this->tagModel->tagPhoto($photoId, $tagIds);
    }
    
    public function getPhotosByUserId($userId){
        return $this->photoTable->getPhotoObjects(array("u.id = ?" => $userId));
    }
    
    public function getPhotosByUsername($username){
        $userTable = new model_table_users();
        $user = $userTable->fetchRow(array("username = ?" => $username));
        
        if($user){
            return $this->getPhotosByUserId($user->id);
        } else {
            return array();
        }
    }
    
    public function isCurrentUserValidToView($fileId){
        return true;
    }
    
    public function outputImage($fileId,$width,$height,$cacheKey){
        $fileLocation = resources::get('config')->get('uploadDir',APPLICATION_PATH . '/upload') . '/' . $fileId;
        header('Content-Type: image/jpeg');
        
        if(!file_exists($fileLocation . $cacheKey)){
            $image = new Imagick($fileLocation);
            $image->thumbnailimage($width, $height);
            $image->setImageFormat('jpeg');
            $image->writeimage($fileLocation . $cacheKey);
            echo $image;
        } else {
            readfile($fileLocation . $cacheKey);
        }
        
        exit(0);
    }
    
    public function getPhotoByShortUrlId($shortUrlId){
        $photoObjects = $this->photoTable->getPhotoObjects(array("p.short_url_id = ?" => $shortUrlId));
        if(!$photoObjects){
            return null;
        }
        return $photoObjects[0];
 
    }
    
    public function commentPhoto($fileId,$userId,$comment){
        $commentTable = new model_table_comment();
        
        $photoRow = $this->photoTable->fetchRow(array("file_id = ?" => $fileId));
        
        if(!$photoRow){
            throw new Exception('photo_not_found');
        }
        
        $commentRow = $commentTable->createRow();
        $commentRow->photo_id = $photoRow->id;
        $commentRow->comment = $comment;
        $commentRow->user_id = $userId;
        $commentRow->comment_added = date("Y-m-d H:i:s");
        
        $commentRow->save();
    }
    
    public function remove($fileId){
        $commentTable = new model_table_comment();
        $phototagTable = new model_table_phototag();
        $toBeRemoved = $this->photoTable->fetchRow(array(
            "file_id = ?" => $fileId,
            "user_id = ?" => resources::get('session')->user->id
        ));
        if(!$toBeRemoved){
            throw new Exception("403");
        }
        $commentTable->delete(array("photo_id = ?" => $toBeRemoved->id));
        $phototagTable->delete(array("photo_id = ?" => $toBeRemoved->id));
        $toBeRemoved->delete();

    }
    
    public function getPhotosByTagId($tagId){
        return $this->photoTable->getPhotoObjectsByTag($tagId);
    }
    
    public function rateImage($fileId,$rating){
        list($visCond,$user) = $this->photoTable->getVisibilityCondition();
        $photoRow = $this->photoTable->fetchRow(array("file_id = ?" => $fileId, $visCond => $user));
        if(!$photoRow){
            throw new Exception("photo_not_found");
        }
        $photoRow->rating_sum += intval($rating);
        $photoRow->rating_count++;
        $photoRow->save();
    }
    
    public function changeVisibility($fileId,$visibility){
        $photoRow = $this->photoTable->fetchRow(array("file_id = ?" => $fileId,"user_id = ?" => resources::get('session')->user->id));
        
        if(!$photoRow){
            throw new Exception("photo_not_found");
        }
        
        $photoRow->visibility = $visibility;
        $photoRow->save();
    }
    public function changeDescription($fileId,$description){
        $photoRow = $this->photoTable->fetchRow(array("file_id = ?" => $fileId,"user_id = ?" => resources::get('session')->user->id));
        
        if(!$photoRow){
            throw new Exception("photo_not_found");
        }
        
        $photoRow->description = $description;
        $photoRow->save();
    }
    
    public function retag($fileId,$tags){
        $photoRow = $this->photoTable->fetchRow(array("file_id = ?" => $fileId,"user_id = ?" => resources::get('session')->user->id));
        $photoTagTable = new model_table_phototag();
        
        if(!$photoRow){
            throw new Exception("photo_not_found");
        }
        
        $photoTagTable->delete(array('photo_id = ?' => $photoRow->id));
        
        $this->tagPhoto($photoRow->id,$tags);
    }
    
    public function searchByDescription($description){
        return $this->photoTable->getPhotoObjects(array("description like ?" => "%".$description."%"));
    }
    
    public function searchByTags($tags){
        $solvedTags = $this->tagModel->solveTags($tags);
        return $this->photoTable->getPhotoObjectsByTag($solvedTags);
    }
    
}
