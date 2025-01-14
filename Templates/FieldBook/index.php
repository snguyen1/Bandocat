<?php
//Menu
include '../../Library/SessionManager.php';
$session = new SessionManager();
if(isset($_GET['col']))
{
    //get collection name passed in from side menu
    $collection = $_GET['col'];
    require('../../Library/DBHelper.php');
    $DB = new DBHelper();
    //get appropriate DB
    $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
    $userRole = $session -> getRole();
    //If the user is a reader automatically redirect them to the edit page because only one button remains, and is pointless
    //to have 1 button to go to one place.
    if($userRole == "Reader")
    {
        header('Location: ./list.php?col='.$collection.'&action=review');
    }
}
else header('Location: ../../');
?>

<!doctype html>
<html lang="en">
<!-- HTML HEADER -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- The title of the page -->
    <title><?php echo $config["DisplayName"]." Menu"; ?></title>
    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>

</head>
<!-- HTML BODY -->
<body>
<table id = "thetable">
    <tr>
        <!-- Draw the header and Side Menu -->
        <td class="menu_left" id="thetable_left">
            <?php include '../../Master/header.php';
            include '../../Master/sidemenu.php' ?>
        </td>
        <td class="Collection" id="thetable_right">
            <h2>Menu</h2>
            <table class="Collection_Table">
                <tr>
                    <td>
                        <!-- Title Displayed in Green style in master.css -->
                        <h4 class="Collection_Title"><?php echo $config["DisplayName"]; ?></h4>
                    </td>
                </tr>
                <tr>
                    <td class="Collection_data">
                        <!-- Upload Documents Button, Php code sends the collection name to upload.php -->
                        <a class="Collection_Button" id="uploadBtn" href="./upload.php?col=<?php echo $collection; ?>" style="text-decoration: none; color: white; display: block">Upload Documents</a>
                    </td>
                </tr>
                <tr>
                    <td class="Collection_data">
                        <!-- Catalog Documents Button, Php code sends the collection name to list.php and send variable action=catalog -->
                        <a class="Collection_Button" id="catalogBtn" href="./list.php?col=<?php echo $collection; ?>&action=catalog" style="text-decoration: none; color: white; display: block">Catalog Document</a>
                    </td>
                </tr>
                <tr>
                    <td class="Collection_data">
                        <!-- Edit/View Documents Button, Php code sends the collection name to list.php and send variable action=review -->
                        <a class="Collection_Button" id="editBtn" href="./list.php?col=<?php echo $collection; ?>&action=review" style="text-decoration: none; color: white; display: block">Edit/View Document</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>

<?php include '../../Master/footer.php'; ?>
</body>
<!-- END BODY -->
<style type="text/css">
    nav{margin: -1px 0px 40px 15px !important;}
</style>
<script>
    //Script checks the users role. If the role is set as "Reader" Then certain functionality is hidden
    $( document ).ready(function()
    {
        var userRole = '<?php echo $session -> getRole(); ?>';
        if(userRole == "Reader")
        {
            header('Location: ../../list.php?col=<?php echo $collection; ?>&action=review');
        }
    });
</script>
</html>
