<?php
include 'vendor/autoload.php';
include 'config.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
if($_FILES["import_excel"]["name"] != ''){
    $allowed_extension = array('xlsx', 'csv', 'xls');
    $file_array = explode(".", $_FILES["import_excel"]["name"] );
    $file_extension = end($file_array);
    if(in_array($file_extension, $allowed_extension)){
        $file_type = PhpOffice\PhpSpreadsheet\IOFactory::identify($_FILES["import_excel"]["name"]);
        $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
        $spreadsheet = $reader -> load($_FILES["import_excel"]["tmp_name"]);
        $data = $spreadsheet ->getActiveSheet()->toArray();
        foreach( $spreadsheet -> getWorksheetIterator() as $worksheet){
           $highestRow = $worksheet -> getHighestRow();
           for($row=2; $row<=$highestRow; $row++){
            $id = mysqli_real_escape_string($conn, $worksheet ->getCellByColumnAndRow(1, $row)->getValue());
            $question_no = mysqli_real_escape_string($conn, $worksheet ->getCellByColumnAndRow(2, $row)->getValue());
            $question = mysqli_real_escape_string($conn, $worksheet ->getCellByColumnAndRow(3, $row)->getValue());
            $opt1 = mysqli_real_escape_string($conn, $worksheet ->getCellByColumnAndRow(4, $row)->getValue());
            $opt2 = mysqli_real_escape_string($conn, $worksheet ->getCellByColumnAndRow(5, $row)->getValue());
            $opt3 = mysqli_real_escape_string($conn, $worksheet ->getCellByColumnAndRow(6, $row)->getValue());
            $opt4 = mysqli_real_escape_string($conn, $worksheet ->getCellByColumnAndRow(7, $row)->getValue());
            $answer = mysqli_real_escape_string($conn, $worksheet ->getCellByColumnAndRow(8, $row)->getValue());
            $category = mysqli_real_escape_string($conn, $worksheet ->getCellByColumnAndRow(9, $row)->getValue());

            $query = "
            INSERT INTO tbl_info (question_no, question, opt1, opt2, opt3, opt4, answer, category)
            VALUES('$question_no', '$question', '$opt1', '$opt2', '$opt3', '$opt4', '$answer', '$category')
            ";
            $stmt = $conn->prepare($query);
            $stmt->execute();
           }
        }
        $message = '<div class="alert alert-success alert-dismissible  text-center del-msg " id="del">
        <button type="button" class="close" data-dismiss="alert">&times; 
        </button>
        <strong>Done!</strong> Record imported Successfully
</div>';
        
        

    }else{
        $message ='<div>Invalid file extension... only .xlsx .csv .xls files allowed</div>';
       
    }

} else{
    $message = '<div>Please Select file. </div>';
    
}



echo $message;
?>