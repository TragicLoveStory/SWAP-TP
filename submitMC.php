<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> -->
</head>
<body>
    <?php
    if(isset($_POST['Submit']) && $_POST['Submit'] === "Upload MC"){
        //insert file path into sql table for retrieval. To also move upload function to leaveFunctions.php to better accommodate the leave(MC & attendance) system
        $target_dir = "files/";
        $target_file = $target_dir . basename($_FILES["uploadMC"]["name"]);
        // TO DO: regex to defend against null byte file upload bypass. Secondly, change uploaded file's name to remove user-controlled factor
        
        //input validation 1) file type validation.
        $allowed = array('png', 'jpg', 'jpeg','pdf');
        $filename = basename($_FILES["uploadMC"]["name"]);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
        if (in_array($ext, $allowed)) {
            //input validation 2) check file size
            if ($_FILES["uploadMC"]["size"] > 1000000) {
                echo "File size is too large.";
                die();
            }
            //input validation 3) check if file exists
            if(file_exists($target_file)){
                echo "File already exists.";
                die();
            }
            // check if file was uploaded successfully
            if(!move_uploaded_file($_FILES["uploadMC"]["tmp_name"], $target_file)){
                echo "Sorry, an error occurred when uploading the file.";
                die();
            }
            echo "SUCCESS";
        }
        else{
            echo "Error: only JPG, JPEG, PNG & PDF files are allowed.";
        }
    } 
    ?>
    <form action="submitMC.php" method="post" enctype="multipart/form-data" style="text-align: center;">
        <label for='uploadMC'>Upload MC:</label>
        <input type="file" name="uploadMC">
        <input type="submit" value="Upload MC" name="Submit">
    </form>
</body>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://www.w3schools.com/lib/w3.js"></script> Include EVERY OTHER HTML Files to this file -->
    <!-- <script>
        //to bring in other HTML on the fly into this page
        w3.includeHTML();
    </script> -->
</html>