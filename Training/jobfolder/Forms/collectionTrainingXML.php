<?php
$data = $_POST;
$training_location = $data['loc'];
$training_col = $data['col'];
$training_user = $data['user'];

$training_parent = "../Training_Collections";
//Collection directory
$training_collection_dir = $training_parent.'/'.$training_col;
//User directory
$training_user_dir = $training_collection_dir.'/'.$training_user;


//The parent attribute refers to the parent directory, with the user name as file name, which will store the
// users' XML files


    $training_user = $data['user'];

    if (file_exists($training_collection_dir))
        $training_collection_dir;

    else
        mkdir($training_collection_dir, 07000);

    if (file_exists($training_user_dir))
        $training_user_dir;

    else
        mkdir($training_user_dir, 07000);




    $training_user = $data['user'];
    $training_newbie = $data['ntype'];
    $training_inter = $data['itype'];

    if ($training_newbie == 'newbie') {
        $training_XML = $training_user_dir.'/'.$training_user . "_newbie.xml";
        if (file_exists($training_XML)) {
            echo "Welcome Back";
        }
        else{
            $xml = new DOMDocument();
            $xml->save($training_XML);
            $myfile = fopen($training_XML, 'w') or die("Cannot create training log!");
            fclose($myfile);
            copy( "newbie.xml", $training_XML);
        }
    }

    if ($training_inter == 'inter') {
        $training_XML = $training_user_dir.'/'.$training_user . "_inter.xml";
        if (file_exists($training_XML)) {
            echo "Welcome Back";

        }
        else{
            $xml = new DOMDocument();
            $xml->save($training_XML);
            $myfile = fopen($training_XML, 'w') or die("Cannot create training log!");
            fclose($myfile);
            copy( "inter.xml", $training_XML);
        }
}


?>