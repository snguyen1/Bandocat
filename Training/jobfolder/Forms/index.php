<?php
include '../../../Library/SessionManager.php';
require('../../../Library/DBHelper.php');
require '../../../Library/ControlsRender.php';

$Render = new ControlsRender();
$session = new SessionManager();

//User name
$username = $_GET["user"];
//Collection
$collection = $_GET['col'];
//Training type
$type = $_GET['type'];
//User's privilege
$priv = $_GET['priv'];

//Include Training type class
switch ($type) {
    case 'newbie':
        include 'newbieClass.php';
        break;
    case 'answer':
        include 'newbieClass.php';
        break;
    case 'inter':
        include 'interClass.php';
        break;
}

include 'config.php';
//Classification information
include 'main.php';

//Document ID
$doc_id = $_GET["id"];
//Filename
$XMLname = $username . '_' . $type;
$XMLfile = XMLfilename($XMLname);

//Loads file
if($type == 'newbie' || $type == 'inter')
    $file = simplexml_load_file('../../Training_Collections/' . $collection . '/'.$username.'/'. $XMLfile) or die("Cannot open file!");
else
    $file = simplexml_load_file('newbie_Answers.xml');
//Loops through every document tag in the file
foreach ($file->document as $a) {
    //Conditions the document's Id
    if ($a->id == $doc_id) {
        //Future Collections Implementations: $collection -> Collection Name (string)
        if ($a["collection"] == $collection) {
            //JobFolder class
            switch ($type) {
                case 'newbie':
                    $doc1 = new JobFolder($collection,'../../Training_Collections/' . $collection . '/'.$username.'/'. $XMLfile, $username, $doc_id);
                    break;
                case 'inter':
                    $doc1 = new JobFolder($collection,'../../Training_Collections/' . $collection . '/'.$username.'/'. $XMLfile, $username, $doc_id);
                    break;
                case 'answer':
                    $doc1 = new JobFolder($collection, 'newbie_Answers.xml', $username, $doc_id);
                    break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>[Training] Job Folder</title>
    <!--Training CSS-->
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <!--Master CSS-->
    <link rel = "stylesheet" type = "text/css" href = "../../../Master/master.css" >
    <!--JQuery UI CSS-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!--JQuery Javascript-->
    <script type="text/javascript" src="../../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <!--JQuery UI Javascript-->
    <script type="text/javascript" src="../../../ExtLibrary/jQueryUI-1.11.4/jquery-ui.js"></script>
    <!--Master Javascript-->
    <script type="text/javascript" src="../../../Master/master.js"></script>
</head>
<body>
    <div id="wrap">
        <div id="main">
            <div id="divleft">
                <?php include '../../trainingMaster.php' ?>
                <div id="descriptionBox">
                    <table id="tableClass">
                        <tr>
                            <th>
                                <h3 id="txtClass" style="margin: 2% 4% 0% 4%; background-color: #0067C5; color: white; font-size: 1em">Classification Description</h3>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <h4 id="className" style="text-align: center; margin: 0%;font-size: 0.95em"></h4>
                            </td>
                        </tr>
                        <tr>
                            <td><p id="tdClass" style="text-align: center; margin: -1% 0% 4% 0%; font-size: 0.85em"></p></td>
                        </tr>
                    </table>
                </div>
                </div>
        </div>


        <div id="divright">
            <h2> Input Training Session </h2>
            <div id="divscroller" style="height: 700px">
                <form id="form" name="form" method="post">
                    <table class="Account_Table">
                        <td id="col1">
                            <!-- LIBRARY INDEX -->
                            <div class="cell">
                                <span class="labelradio">
                                    <mark class="label">
                                        <span style = "color:red;"> * </span>
                                        Library Index:
                                    </mark>
                                    <p hidden>
                                        <b></b>
                                        <strong>Library Index: </strong>The library index is the name of a scanned document. Copy and paste the image’s name into the textbox exactly as you see it.<br><i>Example: </i><?php echo $doc1->libraryindex; ?>
                                    </p>
                                </span>
                                <input type = "text" name = "txtLibraryIndex" id = "libraryindex" size="26" value='<?php echo $doc1->libraryindex; ?>' required />
                            </div>
                            <!-- TITLE -->
                            <div class="cell">
                                <span class="labelradio"><mark class="label"><span style = "color:red;"> * </span>Document Title: </mark><p hidden><b></b><strong>Document Title: </strong>This can be printed or hand written, but it is typically found across the top of the document. If one cannot be found, enter the library index.</br><strong>Envelopes: </strong>An envelope will always be given the title of the library index.</p></span>
                                <input type = "text" name = "txtTitle" id = "title" size="26" required="true" value='<?php echo $doc1->title; ?>' />
                            </div>
                            <!-- NEEDS REVIEW -->
                            <div class="cell">
                                <span class="labelradio" >
                                <mark>Needs Review: </mark>
                                <p hidden><b></b>This is to signal if a review is needed, and always keep selection as yes</p>
                                </span>
                                <input type = "radio" name = "rbNeedsReview" id = "needsreview" size="26" value="1" <?php if($doc1->needsreview == 1) echo "checked"; ?> />Yes
                                <input type = "radio" name = "rbNeedsReview" id = "needsreview" size="26" value="0" <?php if($doc1->needsreview == 0) echo "checked"; ?>  />No
                            </div>
                            <!-- SUB FOLDER -->
                            <div class="cell">
                                <span class="labelradio" >
                                <mark>In A Subfolder: </mark>
                                <p hidden><b></b>Select if the document belongs to a subfolder. This will be indicated in the library index by a ".number" </br>Ex. 142-_038.1</p>
                                </span>
                                <input type = "radio" name = "rbInASubfolder" id = "inasubfolder" size="26" value="1" <?php if($doc1->inasubfolder == 1) echo "checked"; ?> />Yes
                                <input type = "radio" name = "rbInASubfolder" id = "inasubfolder" size="26" value="0" <?php if($doc1->inasubfolder == 0) echo "checked"; ?> />No
                            </div>
                            <!-- SUBFOLDER COMMENTS -->
                            <div class="cell">
                                <span class="labelradio">
                                    <mark class="label">
                                        Subfolder Comments:
                                    </mark>
                                    <p hidden>
                                        <b></b>
                                        <strong>Subfolder Comments: </strong>The first document of a subfolder will say what to expect within the subfolder. Looking at the first document, type exactly what is written on the document into this box. If you are cataloging a consecutive document, copy the text from the first document into this box. All documents within the subfolder will have the subfolder comments from the first document.
                                    </p>
                                </span>
                                <textarea cols = "30" name="txtSubfolderComments" id="subfoldercomments"/><?php echo $doc1->subfoldercomments; ?></textarea>
                            </div>
                            <!-- CLASSIFICATION -->
                            <div class="cell">
                                <span class="labelradio">
                                    <mark class="label">
                                        Classification:
                                    </mark>
                                    <p hidden>
                                        <b></b>
                                        <strong>Classification: </strong>Classify the type of document. If assistance is needed, consult the Classification Description box.
                                    </p>
                                </span>
                                <select id="classification" name="ddlClassification" style="width:215px">
                                    <?php
                                    classification($classification_arr, $doc1->classification);
                                    ?>
                                </select>
                            </div>
                            <!-- CLASSIFICATION COMMENTS-->
                            <div class="cell">
                                <span class="labelradio">
                                    <mark class="label">
                                        Classifications Comments:
                                    </mark>
                                    <p hidden>
                                        <b></b>
                                        <strong>Classifications Comments: </strong>This is reserved only for the folder’s envelope. Copy what is seen on the envelope into this box.
                                    </p>
                                </span>
                                <textarea rows = "2" cols = "30" id="classificationcomments" name="txtClassificationComments"/><?php echo $doc1->classificationcomments; ?></textarea>
                            </div>
                            <!-- GET START DDL MONTH -->
                            <div class="cell">

                                <select name="ddlStartMonth" id="startmonth" style="width:60px">
                                    <?php $Render->GET_DDL_MONTH($doc1->startmonth); ?>
                                </select>
                                <span class="labelradio">
                                    <mark class="label">
                                        Document Start Date:
                                    </mark>
                                    <p hidden>
                                        <b></b>
                                        <strong>Document Start Date: </strong>The earliest date on the document- as it pertains to the creation of that document.</br><i>*If there is one date on the document, only fill out the Document End Date boxes.</i>
                                    </p>
                                </span>
                                <!-- GET START DDL DAY -->
                                <select name="ddlStartDay" id="startday" style="width:60px">
                                    <?php $Render->GET_DDL_DAY($doc1->startday); ?>
                                </select>
                                <!-- GET START DDL YEAR -->
                                <select id="ddlStartYear" name="startyear" style="width:85px">
                                    <?php $Render->GET_DDL_YEAR($doc1->startyear); ?>
                                </select>

                            </div>
                            <!-- GET END DDL MONTH -->
                            <div class="cell">
                                <select name="ddlEndMonth" id="endmonth" style="width:60px">
                                    <?php $Render->GET_DDL_MONTH($doc1->endmonth); ?>
                                </select>
                                <span class="labelradio">
                                    <mark class="label">
                                        Document End Date:
                                    </mark>
                                    <p hidden>
                                        <b></b>
                                        <strong>Document End Date: </strong>The latest date on the document- as it pertains to the creation of that document.
                                    </p>
                                </span>
                                <!-- GET END DDL DAY -->
                                <select name="ddlEndDay" id="endday" style="width:60px">
                                    <?php $Render->GET_DDL_DAY($doc1->endday); ?>
                                </select>
                                <!-- GET END DDL YEAR -->
                                <select name="ddlEndYear" id="ddlEndYear" style="width:85px">
                                    <?php $Render->GET_DDL_YEAR($doc1->endyear); ?>
                                </select>
                            </div>
                            <!-- DOCUMENT AUTHOR -->
                            <div class="cell">
                                <div class='authorsCell' id="author0">
                                    <span class="labelradio">
                                    <mark class="label">
                                        Document Author:
                                    </mark>
                                    <p hidden>
                                        <b></b>
                                        <strong>Document Author: </strong>Who created the document. This can be found at the top of the document or at the end. However, if there are documents grouped together in sequence, with the author’s name on the last page, all the documents have the same author. If there are multiple authors, press the “+” to create more input boxes.
                                    </p>
                                </span>
                                    <div style="width: 110%">
                                    <input type="text" class="txtAuthor" name="txtAuthor[]" size="26" list="lstAuthor" value="<?php echo $doc1->author->name[0]?>"/>
                                    <span style="padding-right:5px"></span>

                                        <input type="button" id="more_fields" onclick="add_fields($('.authorsCell').length, null)" value="+">
                                        <input type="button" id="less_fields" onclick="remove_fields($('.authorsCell').length)" value="-">
                                    </div>
                                    </div>

                                <?php $lenAuthors = count($doc1->author->name);
                                for ($d = 1; $d < $lenAuthors; $d++) {
                                    echo '<div class="authorsCell" id="author'.$d.'" style="margin: 1% 0% 0% -1.5%"><span class="label">Document Author: </span>
                                    <input type="text" id="txtAuthor" name="txtAuthor[]" size="26" list="lstAuthor" value="'.$doc1->author->name[$d].'"/></div>';
                                }
                                ?>

                            </div>
                        </td>

                        <td id="col2" style="padding-left:40px">
                            <!-- COMMENTS-->
                            <div class="cell">
                                <span class="labelradio">
                                    <mark class="label">
                                        Comments:
                                    </mark>
                                    <p hidden>
                                        <b></b>
                                        <strong>Comments: </strong>Any additional information that needs to be included from the document. This can include the individuals involved in a correspondence, metadata from a map, sheet number, and job folder number.
                                    </p>
                                </span>
                                <textarea rows = "4" cols = "30" id="comments" name="txtComments"/><?php echo $doc1->comments; ?></textarea>
                                <br><br><br>
                            </div>
                            <!-- THUMBNAIL LINKS -->
                            <div class="cell" id="scanThumbnails">
                                <table>
                                    <tr>
                                        <!--SCAN OF FRONT-->
                                        <td style="text-align: center">
                                            <span class="label" style="text-align: center">Scan of Front</span><br>
                                            <?php
                                            $frontImage = realpath($doc1->frontimage);
                                            $backImage = realpath($doc1->backimage);
                                            echo "<a id='download_front' href=\"download.php?file=$frontImage\"><br><img src='". $doc1->frontthumbnail . " ' alt = Error /></a>";
                                            echo "<br>Size: " . round(filesize($doc1->frontimage)/1024/1024, 2) . " MB";
                                            echo "<br><a href=\"download.php?file=$frontImage\">(Click to download)</a>";
                                            ?>
                                        </td>
                                        <!--SCAN OF BACK-->
                                        <td style="text-align: center">
                                            <?php
                                            if($doc1->backimage != '../Images/Intermediate/Documents/' && $doc1->backimage != '../Images/Newbie/Documents/'){ //has Back Scan
                                                echo '<span class="label" style="text-align: center">Scan of Back</span><br>';
                                                echo "<a id='download_front' href=\"download.php?file=$backImage\"><br><img src='". $doc1->backthumbnail . " ' alt = Error /></a>";
                                                echo "<br>Size: " . round(filesize($doc1->backimage) / 1024 / 1024, 2) . " MB";
                                                echo "<br><a href=\"download.php?file=$backImage\">(Click to download)</a>";
                                            }

                                            else
                                                echo '<span class="label" style="text-align: center">No Scan of Back</span><br>';

                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>

                        <tr>
                            <td colspan="2">
                                <div class="cell" style="text-align: center;padding-top:20px">
                                    <!-- Hidden inputs that are passed when the update button is hit -->
                                    <input type = "hidden" id="txtDocID" name = "txtDocID" value = "<?php echo $doc_id;?>" />
                                    <input type = "hidden" id="txtAction" name="txtAction" value="catalog" />  <!-- catalog or review -->
                                    <input type = "hidden" id="txtCollection" name="txtCollection" value="<?php echo $collection; ?>" />
                                    <span>
                                        <?php if($session->hasWritePermission() && $type != 'answer'){
                                            echo "<input type='submit' id='btnSubmit' name='submit' value='Update' action='index.php' class='bluebtn'/>";
                                        }
                                        ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</body>


<?php
$data = file_get_contents('php://input')
?>


<script>
    //Window Height
    var windowHeight = window.innerHeight;
    $('#divscroller').height(windowHeight - (windowHeight * 0.1));

    $(window).resize(function (event) {
        windowHeight = event.target.innerHeight;
        $('#divscroller').height(windowHeight - (windowHeight * 0.1));
    });


    var formJSON = {};
    var answersJSON = '';
    $(document).ready(function () {
        //object to request data for the newbie_Answers.xml
        var xhttp_answers = new XMLHttpRequest();

        //Loads XML
        xhttp_answers.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                //XML object
                var xmlAnswers = this.responseXML;
                answersJSON = xmlToJson(xmlAnswers);
                //JSON document{[documents array[fields]]}
                ansDataJSON = answersJSON.data;
                table2JSON();

                $(':input').change(function (event) {
                    table2JSON();
                    var targetID = event.currentTarget.id;
                    var IDProperty = JSON.parse(JSON.stringify(targetID));
                    var targetValue = event.target.value;
                    //valid document ID
                    var docID = formJSON.document;
                    var answerValue = ansDataJSON.document[docID][IDProperty]['#text'];
                    if (jQuery.isEmptyObject(ansDataJSON.document[docID][IDProperty])) {
                        answerValue = ''
                    }

                    if (answerValue.toLowerCase() == targetValue.toLowerCase()) {
                        $("#" + String(targetID)).removeAttr('style').css('-webkit-animation', 'correctFade 2s linear');
                    }

                    else
                        $("#" + String(targetID)).css('outline', 'red').css('outline-style', 'solid')
                })
            }
        };
        xhttp_answers.open("GET", "newbie_Answers.xml");
        xhttp_answers.send();


        //Disables the labels' description marks
        if ("<?php echo $type?>" == "inter") {
            $(".labelradio > p").remove();
        }

    });

    /**********************************************
     *Function: xmlToJSON
     *Description: Changes XML to JSON
     * Parameter(string): XML object
     * Return value(string): JSON object
     ***********************************************/
    function xmlToJson(xml) {
        // Create the return object
        var obj = {};

        if (xml.nodeType == 1) { // element
            // do attributes
            if (xml.attributes.length > 0) {
                obj["@attributes"] = {};
                for (var j = 0; j < xml.attributes.length; j++) {
                    var attribute = xml.attributes.item(j);
                    obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
                }
            }
        } else if (xml.nodeType == 3) { // text
            obj = xml.nodeValue;
        }

        // do children
        if (xml.hasChildNodes()) {
            for(var i = 0; i < xml.childNodes.length; i++) {
                var item = xml.childNodes.item(i);
                var nodeName = item.nodeName;
                if (typeof(obj[nodeName]) == "undefined") {
                    obj[nodeName] = xmlToJson(item);
                } else {
                    if (typeof(obj[nodeName].push) == "undefined") {
                        var old = obj[nodeName];
                        obj[nodeName] = [];
                        obj[nodeName].push(old);
                    }
                    obj[nodeName].push(xmlToJson(item));
                }
            }
        }
        return obj;
    };


        /**********************************************
         * Function: accountJSON
         * Description: adds more fields for authors
         * Parameter(s): length (integer) Length of Author's cells
         * val (String ) - name of the author
         * Return value(s): None
         ***********************************************/
        function accountJSON(json, elemID, value) {
            json["data"].push({"id": elemID, "value": value});
        }

        /**********************************************
         * Function: table2JSON
         * Description: Converts the table's columns input form fields and values into a structured JSON object
         * Parameter(string): None
         * Return value(string): None
         ***********************************************/
        function table2JSON() {
            var accountTable = $(".Account_Table").children();
            //Left column rows
            var accountInputsCol1 = accountTable[0].rows[0].cells.col1.children;
            //Right column rows
            var accountInputsCol2 = accountTable[0].rows[0].cells.col2.children;

            //Stores teh document id into a JSON property value
            formJSON["document"]= '<?php echo $doc_id?>';
            //Creates an empty property array
            formJSON["data"] = [];

            for (var i = 0; i < accountInputsCol1.length - 1; i++) {
                if ($("#" + accountInputsCol1[i].children[1].id).is(':radio'))
                    accountJSON(formJSON, accountInputsCol1[i].children[1].id, $("#" + accountInputsCol1[i].children[1].id + ":checked").val());
                else
                    accountJSON(formJSON, accountInputsCol1[i].children[1].id, accountInputsCol1[i].children[1].value);
            }

            for (var i = 0; i < accountInputsCol2.length - 1; i++) {
                if (accountInputsCol2[i].id == 'startDate') {
                    for (var d = 2; d < 4; d++)
                        accountJSON(formJSON, accountInputsCol2[i].children[d].id, accountInputsCol2[i].children[d].value);
                }

                else if (accountInputsCol2[i].id == 'endDate') {
                    for (var d = 2; d < 4; d++)
                        accountJSON(formJSON, accountInputsCol2[i].children[d].id, accountInputsCol2[i].children[d].value);
                }
                accountJSON(formJSON, accountInputsCol2[i].children[1].id, accountInputsCol2[i].children[1].value);
            }
        }

        /**********************************************
         * Function: add_fields
         * Description: adds more fields for authors
         * Parameter(s): length (integer) Length of Author's cells
         * val (String ) - name of the author
         * Return value(s): None
         ***********************************************/
        var max = 5;
        var author_count = 0;

        function add_fields(length, val) {
            console.log($('.authorsCell'));
            if (val == null)
                val = "";
            if (author_count >= max)
                return false;
            $('#author' + (length - 1)).after('' +
                '<div class="authorsCell" id="author' + length + '" style="margin: 1% 0% 0% -1.5%">' +
                '<span class="label">Document Author: </span>' +
                '<input type = "text" name = "txtAuthor[]" autocomplete="off" class="txtAuthor" size="26" value="' + val + '" list="lstAuthor">' +
                '</div>')
            author_count++;
        }

        /**********************************************
         *Function: remove_fields
         *Description: removes fields from the Document Author field
         * Parameter(s): length (integer) Length of Author's cells
         * Return value(s): None
         ***********************************************/
        function remove_fields(length) {
            //This will prevent for the function to delete all the authors' cells
            if (length < 2)
                return false;
            //Removes the las childrend of the authorsCell class
            $('.authorsCell').last().remove();
            author_count--;
        }



        /*Function that displays, when the Classification drop down value is changed, a brief summary of the description
         for that classification*/
        var clName = <?php echo json_encode($classification_arr); ?>;
        var clName_desc = <?php echo json_encode($classification_desc); ?>;

        $('#classification').change(function () {
            var classification = $('#classification option:selected').text();
            var classificationLenght = clName.length;
            $("#className").text(classification);
            for (var q = 0; q < classificationLenght; q++) {
                if (classification == clName[q])
                    $("#tdClass").text(clName_desc[q]);
            }
        });

        function dataComparison(id, value) {
            $.each(ansDataJSON.document[formJSON.document], function (ansID, ansVal) {
                if (id == ansID) {
                    var idProperty = JSON.parse(JSON.stringify(ansID));
                    if (jQuery.isEmptyObject(ansDataJSON.document[formJSON.document][idProperty])) {
                        ansDataJSON.document[formJSON.document][idProperty]['#text'] = ''
                    }

                    switch (id) {
                        case 'startday':
                            if (value == '00')
                                value = '';
                            break;
                        case 'startmonth':
                            if (value == '00')
                                value = '';
                            break;
                        case 'startyear':
                            if (value == '0000')
                                value = '';
                            break;
                        case 'endday':
                            if (value == '00')
                                value = '';
                            break;
                        case 'endmonth':
                            if (value == '00')
                                value = '';
                            break;
                        case 'endyear':
                            if (value == '0000')
                                value = '';
                            break;
                    }

                    if (value == ansVal['#text']) {
                        e = false;
                        return e;
                    }
                    else {
                        e = true;
                        return e;
                    }
                }
            });
            return e
        }


        //Input form array without authors
        formArray = [];
        //Authors Array
        authorArray = [];

        //Submit function that will convert the input form and author array into a single JSON
        $("#form").on("submit", function (e) {
            e.preventDefault();

            error = false;

          for(var d = 0; d < formJSON.data.length; d++){
              //User input id
              var formJSONID = formJSON.data[d].id;
              //User input value
              var formJSONValue = formJSON.data[d].value;
              //Compares User and Answer values
              error = dataComparison(formJSONID, formJSONValue);
              //If error, the user and answer values are different on submit the submission is stopped an the input
              //element's outline is highlighted with a orange color
              if(error){
                  alert("There is an error");
                  $("#"+formJSONID).css('outline', 'orange').css('outline', 'orange').css('outline-style', 'solid');
                  return
              }
          }

            //Creates an array of objects by form values
            var formSerialized = $(this).serializeArray();

            /*JQuery that iterates through the serialized array to create a JSON object that will be posted to save the
             training input data*/
            $.each(formSerialized, function (i, field) {
                //Creates an empty name instance of an Author
                if (field.name == 'txtAuthor[]')
                    authorArray.push('"txtAuthor":[]');

                //Obtains the name and value of each input and stores it into the Input form array in a JSON format
                else
                    formArray.push('"' + field.name + '":"' + field.value + '"')
            });
            //The Authors array is pushed to the Input Form array
            formArray.push(authorArray);
            //Training type attribute is pushed to the Input Form Array
            formArray.push('"type":"<?php echo $_GET['type']?>"');

            //The Authors Element is retrieved to obtain its values and store them into the JSON Input Form
            var authorsName = document.getElementsByName('txtAuthor[]');
            //Input Form is parsed into a JSON
            formJSON = JSON.parse("{" + formArray.toString() + "}");
            for (var e = 0; e < authorsName.length; e++) {
                formJSON.txtAuthor[e] = authorsName[e].value;
            }

          $.ajax({
              type: 'post',
              url: 'saveTrainingData.php',
              data: formJSON,
              success: function () {
                  alert('Success')
              },
              error: function (error) {
                  alert(error)
              }
          });

            /**********************************************
             *Function: winLocation
             *Description: Declares the location on which the window will be replaced when the Submission is completed,
             * which could be an admin page or trainee page.
             * Parameter(string): Training Type
             * Return value(string): URL that will replace the window
             ***********************************************/
            var trainLocation = function winLocation(type) {
                var trainLoc = "";
                if ("<?php echo $priv?>" == 'admin')
                    trainLoc = 'list.php?col=' + "<?php echo $collection ?>" + '&action=training&type=' + type + '&user=<?php echo $username?>&priv=admin';
                else
                    trainLoc = 'list.php?col=' + "<?php echo $collection ?>" + '&action=training&type=' + type;
                return trainLoc;
            };

            window.location.replace(trainLocation("<?php echo $type ?>"));
        })
</script>

</body>
<?php include '../../../Master/footer.php'; ?>
</html>