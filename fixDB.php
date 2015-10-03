<?php

$path = realpath('/var/www/j2/files/journals/');

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);



foreach($objects as $name => $object){
    if (is_file($object)){

        try {

            //connect as appropriate as above
            $pdoOptions = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            );
            $username='j2';
            $password='tempj2';
            $db = new PDO('mysql:host=localhost;dbname=j2', $username, $password, $pdoOptions);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("SELECT * FROM article_files  WHERE file_name = :file_name" );
            $stmt->bindValue (':file_name', basename($name));
            $stmt->execute();

//            $stmt->debugDumpParams();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            /* File type IDs */
            define('ARTICLE_FILE_SUBMISSION', 0x000001);
            define('ARTICLE_FILE_REVIEW',     0x000002);
            define('ARTICLE_FILE_EDITOR',     0x000003);
            define('ARTICLE_FILE_COPYEDIT',   0x000004);
            define('ARTICLE_FILE_LAYOUT',     0x000005);
            define('ARTICLE_FILE_SUPP',       0x000006);
            define('ARTICLE_FILE_PUBLIC',     0x000007);
            define('ARTICLE_FILE_NOTE',       0x000008);
            define('ARTICLE_FILE_ATTACHMENT', 0x000009);


            foreach($results as $row)
            {
//                var_dump($row['file_stage']);
                if ($row['file_stage']==0){

                    if (preg_match('/public/', $name)){
//                        $db->query("UPDATE article_files SET file_stage =7  WHERE file_stage=0 AND file_name=basename($name) ")
                        var_dump("UPDATE article_files SET file_stage =7  WHERE file_stage=0 AND file_name='".basename($name)."'");
                        $db->query("UPDATE article_files SET file_stage =7  WHERE file_stage=0 AND file_name='".basename($name)."'");
                    }

                    if (preg_match('/review/', $name)){
//                        $db->query("UPDATE article_files SET file_stage =7  WHERE file_stage=0 AND file_name=basename($name) ")
                        var_dump("UPDATE article_files SET file_stage =2  WHERE file_stage=0 AND file_name='".basename($name)."'");
                        $db->query("UPDATE article_files SET file_stage =2  WHERE file_stage=0 AND file_name='".basename($name)."'");
                    }

                    if (preg_match('/editor/', $name)){
//                        $db->query("UPDATE article_files SET file_stage =7  WHERE file_stage=0 AND file_name=basename($name) ")
                        var_dump("UPDATE article_files SET file_stage =3  WHERE file_stage=0 AND file_name='".basename($name)."'");
                        $db->query("UPDATE article_files SET file_stage =3  WHERE file_stage=0 AND file_name='".basename($name)."'");
                    }


                    if (preg_match('/copyedit/', $name)){
//                        $db->query("UPDATE article_files SET file_stage =7  WHERE file_stage=0 AND file_name=basename($name) ")
                        var_dump("UPDATE article_files SET file_stage =4  WHERE file_stage=0 AND file_name='".basename($name)."'");
                        $db->query("UPDATE article_files SET file_stage =4  WHERE file_stage=0 AND file_name='".basename($name)."'");
                    }
                    if (preg_match('/layout/', $name)){
//                        $db->query("UPDATE article_files SET file_stage =7  WHERE file_stage=0 AND file_name=basename($name) ")
                        var_dump("UPDATE article_files SET file_stage =5  WHERE file_stage=0 AND file_name='".basename($name)."'");
                        $db->query("UPDATE article_files SET file_stage =5  WHERE file_stage=0 AND file_name='".basename($name)."'");
                    }

                    if (preg_match('/supp/', $name)){
//                        $db->query("UPDATE article_files SET file_stage =7  WHERE file_stage=0 AND file_name=basename($name) ")
                        var_dump("UPDATE article_files SET file_stage =6  WHERE file_stage=0 AND file_name='".basename($name)."'");
                        $db->query("UPDATE article_files SET file_stage =6  WHERE file_stage=0 AND file_name='".basename($name)."'");
                    }

                    if (preg_match('/attachment/', $name)){
//                        $db->query("UPDATE article_files SET file_stage =7  WHERE file_stage=0 AND file_name=basename($name) ")
                        var_dump("UPDATE article_files SET file_stage =9  WHERE file_stage=0 AND file_name='".basename($name)."'");
                        $db->query("UPDATE article_files SET file_stage =9  WHERE file_stage=0 AND file_name='".basename($name)."'");
                    }





                }

            }



        } catch(PDOException $ex) {
            echo "An Error occured!"; //user friendly message
            echo $ex->getMessage();
        }

    }

}