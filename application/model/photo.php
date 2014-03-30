<?php

class model_photo {
    private $photoTable;
    
    
    public function __construct(){
        $this->photoTable = new model_table_photo();
    }
    
    public function addImage($uploadFileArray){

        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($uploadFileArray['error']) ||
            is_array($uploadFileArray['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }

        // Check $_FILES['upfile']['error'] value.
        switch ($uploadFileArray['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        // You should also check filesize here. 
        if ($uploadFileArray['size'] > 1000000) {
            throw new RuntimeException('Exceeded filesize limit.');
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
            throw new RuntimeException('Invalid file format.');
        }

        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.

        $dbEntry = $this->photoTable->createRow();
        $dbEntry->file_id = sha1_file($uploadFileArray['tmp_name']);
        $dbEntry->file_name = $uploadFileArray['name'];
        $dbEntry->user_id = resources::get('session')->user->id;
        $dbEntry->visibility = 0;
        
        $dbEntry->save();

        if (!move_uploaded_file(
            $uploadFileArray['tmp_name'],
            resources::get('config')->get('uploadDir',APPLICATION_PATH . '/upload') . '/' . $dbEntry->file_id
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
        
        return $dbEntry;

    }
}
