<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
require '../../Library/DBHelper.php';
    $DB = new DBHelper();
    $collection_array = $DB->GET_COLLECTION_FOR_DROPDOWN();
?>
<!doctype html>
<html lang="en">
<!-- HTML HEADER -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/all.css" integrity="sha384-aOkxzJ5uQz7WBObEZcHvV5JvRW3TUc2rNPA7pe3AwnsUohiw1Vj2Rgx2KSOkF5+h" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Submit Ticket</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../../Master/bandocat_custom_bootstrap.css">
</head>
<body>
<?php include "../../Master/bandocat_mega_menu.php"; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Put Page Contents Here -->
            <h1 class="text-center">Error reporting</h1>
            <hr>

            <!-- Center Card on the user's screen -->
            <div class="d-flex justify-content-center">
                <div class="card" style="width: 40em;">
                    <div class="card-body">
                        <form name="frm_ticket" id="frm_ticket" method="post">
                            <!-- Database -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="ddlDBname">Database:</label>
                                <div class="col-sm-9">
                                    <select name="ddlDBname" id="ddlDBname" class="form-control" required>
                                        <option value="">Select</option>
                                        <?php
                                        foreach($collection_array as $col)
                                            echo "<option value='" . $col['collectionID'] .  "'>$col[displayname]</option>";
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Library Index -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="txtSubject1">Library Index 1:</label>
                                <div class="col-sm-7">
                                    <input type = "text" name = "txtSubject" id = "txtSubject1" size="32" class="form-control" required/>
                                </div>
                                <div class="col-sm-1">
                                    <input type="button" onclick="add_fields()" value="+" class="btn btn-primary">
                                </div>
                                <div class="col-sm-1">
                                    <input type="button" onclick="remove_fields()" value="-" class="btn btn-danger">
                                </div>
                            </div>
                            <!-- Additional Library Index's if generated by the user -->
                            <div id="divSubject">

                            </div>
                            <!-- What's Wrong? -->
                            <div class="form-group">
                                <label for="txtDesc">What's Wrong?</label>
                                <textarea name = "txtDesc" id="txtDesc" rows = "10" cols = "70" class="form-control" required/></textarea>
                            </div>
                            <input type = "submit" name = "btnSubmit" value = "Submit" class="btn btn-primary"/>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- col -->
    </div> <!-- row -->
</div><!-- Container -->
<?php include "../../Master/bandocat_footer.php" ?>


<!-- Complete JavaScript Bundle -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- JQuery UI cdn -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<!-- Our custom javascript file -->
<script type="text/javascript" src="../../Master/master.js"></script>

<!-- This Script Needs to Be added to Every Page, If the Sizing is off from dynamic content loading, then this will need to be taken away or adjusted -->
<script>
    $(document).ready(function() {

        var docHeight = $(window).height();
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;

        if (footerTop < docHeight)
            $('#footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
    });
</script>
<!-- Page's Script -->
<script>

    var libArray = [];
    var length;
    $( document ).ready(function() {
        // Setting length = 2
        length = 2;

        /* attach a submit handler to the form */
        $('#frm_ticket').submit(function (event) {
            // Making sure libarray is empty
            libArray = [];

            /* stop form from submitting normally */
            event.preventDefault();
            var subject = "";
            /*Converts the list of Library indexes into a JSON format*/
            $.each($("input[name*='txtSubject']"), function (index, libIndex) {
                libArray.push({"libraryIndex": libIndex.value});
                console.log(libArray);
            });

            // Building Subject String
            if(libArray.length > 1)
            {
                for(var i = 0; i < libArray.length; i++)
                {
                    // Checking if this is the last element
                    if(i === libArray.length - 1)
                    {
                        console.log("if");
                        subject += libArray[i].libraryIndex;
                    }

                    else
                    {
                        console.log("else");
                        subject += libArray[i].libraryIndex + ", ";
                    }
                }
            }

            else
            {
                // Building subject string
                subject += libArray[0].libraryIndex;
            }

            var strLib = JSON.stringify(libArray);
            console.log(subject);

            /* Send the data using post */
            $.ajax({
                type: 'post',
                url: 'index_processing.php',
                data: {dbname: $('#ddlDBname :selected').val(), subject: subject, description: $('#txtDesc').val(), libIndex: strLib},
                success:function(data){

                    if(data == "true")
                    {
                        alert("Ticket Submitted!");
                        window.close();
                    }
                    else alert("Ticket failed to submit!");
                }
            });
        });
    });

    /**********************************************
     * Function: add_fields
     * Description: adds more fields for authors
     * Parameter(s): length (integer) Length of Author's cells
     * val (String ) - name of the author
     * Return value(s): None
     ***********************************************/

    function add_fields() {
        /*$('#divSubject' + (length - 1)).after('' +
            '<div class="divSubject" id="divSubject' + length + '" style="margin-top: 2%">' +
            '<input type = "text" class="txtSubject" name = "txtSubject" id = "txtSubject0" size="32" value="' + val + '" required/>' +
            '</div>');*/
        var html = '<div class="form-group row" id="length' + length + '">' +
            '<label class="col-sm-3 col-form-label" for="txtSubject'+ length + '">Library Index ' + length + ':</label>' +
            '<div class="col-sm-9">\n' +
            '<input type = "text" name = "txtSubject" id = "txtSubject' + length + '" size="32" class="form-control" required/>' +
            '</div>' +
            '</div>';

        // Appending html
        $('#divSubject').append(html);

        // Incrementing counter
        length++;
    }

    function remove_fields() {
        // Remove library index n and decrement length
        if(length > 2)
        {
            length--;
            $('#length' + length).remove();
        }

        // Length should not be lower than two, so we still remove library index 2 but we don't decrement
        else if(length === 2)
        {
            $('#length' + length).remove();
            length = 2;
        }
    }

</script>
</body>
</html>
