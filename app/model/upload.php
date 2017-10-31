<?php

/*echo "<pre>";echo print_r($_FILES)."<br/>PatientID : ".$_POST['patientId'];exit;*/

require_once 'pdocrudhandler.php';

if(isset($_POST['patientId']) && isset($_FILES['files'])){
    uploadMultipleFiles($_POST['prescribtionId']);
}

function uploadMultipleFiles($Id){
    $files = '';$status='';$existfiles = '';
    $path = '';
    $total = count($_FILES['files']['name']);
    for($i=0; $i<$total; $i++) {
        if ($_FILES["files"]["error"][$i] > 0) {
            echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
        } else {
            if (file_exists('../Project/uploads/'.$Id.'_'.$_FILES['files']['name'][$i])) {
                $existfiles .= $_FILES['files']['name'][$i].' ';
                continue;
            } else {
                $tmpFilePath = $_FILES['files']['tmp_name'][$i];
                if ($tmpFilePath != ""){
                    $newFilePath = '../Project/uploads/'.$Id.'_'.$_FILES['files']['name'][$i];
                    ($i == 0)? $path .= '../Project/uploads/'.$Id.'_'.$_FILES['files']['name'][$i] : $path .= '^../Project/uploads/'.$Id.'_'.$_FILES['files']['name'][$i];
                    if(move_uploaded_file($tmpFilePath,$newFilePath)) {

                    }
                }
            }
        }
        $files .= $_FILES['files']['name'][$i].' ';
    }
    $existfilesArray = explode(' ',$existfiles);
    $filesArray = explode(' ',$files);
    if (!empty($path)){
        $_pdo = new pdocrudhandler();
        $result = $_pdo->update('prescribtion',array('filePath' => $path),'where p_id = ?',array($Id));
        if ($result['status'] == 'success' && $result['rowsAffected'] > 0){
            if (count($existfilesArray) > 0 && $existfiles != ''){
                $status .= '<div class="alert alert-success alert-dismissible fade in" role="alert">';
                $status .= '<strong>Status: </strong>Files already shared <strong>'.$existfiles.'</strong> ';
                $status .= '</div><br/><br/><br/>';
                echo $status;
            }
            if (count($filesArray) > 0 && $files != ''){
                $status .= '<div class="alert alert-success alert-dismissible fade in" role="alert">';
                $status .= '<strong>Status: </strong>Files uploaded successfully <strong>'.$files.'</strong> ';
                $status .= '</div><br/><br/>';
                echo $status;
            }
        }
    }
    /*$filePathArray = explode("^",$path);*/
}



?>