<?php
include '../../Library/SessionManager.php';
$session = new SessionManager();
    if(isset($_GET['col']))
    {
        $collection = $_GET['col'];
        require('../../Library/DBHelper.php');
        $DB = new DBHelper();
        //get appropriate db
        $config = $DB->SP_GET_COLLECTION_CONFIG($collection);
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

    <title>Welcome to BandoCat!</title>

    <link rel = "stylesheet" type = "text/css" href = "../../Master/master.css" >
    <link rel = "stylesheet" type="text/css" href="../../ExtLibrary/DataTables-1.10.12/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../../ExtLibrary/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="../../ExtLibrary/DataTables-1.10.12/js/jquery.dataTables.min.js"></script>
    <script>
        /**********************************************
         * Function:  DeleteDocument
         * Description: deletes the document from the database
         * Parameter(s):
         * col (in string) - name of the collection
         * id (in Int) - document id
         * Return value(s):
         * $result true if success, false if failed
         ***********************************************/
        function DeleteDocument(col,id)
        {
            $response = confirm('Are you sure you want to delete this document?');
            if($response)
            {
                $.ajax({
                    type: 'post',
                    url: 'form_processing.php',
                    data: {"txtAction": "delete", "txtCollection": col, "txtDocID": id},
                    success:function(data){
                        var json = JSON.parse(data);
                        var msg = "";
                        for(var i = 0; i < json.length; i++)
                            msg += json[i] + "\n";
                        alert(msg);
                        $('#dtable').DataTable().ajax.reload();
                    }
                });
            }
        }
        //When the document is ready, begin the rendering of the datatable
        $(document).ready(function()
        {
            var collection_config = <?php echo json_encode($config); ?>;
            $('#page_title').text(collection_config.DisplayName);

            var table = $('#dtable').DataTable( {
                "processing": true,
                "serverSide": true,
                "lengthMenu": [20, 40 , 60, 80, 100],
                "bStateSave": false,
                "order": [[ 0, "desc" ]],
                "columnDefs": [
                    //column Document Index: Replace with Hyperlink
                    {
                        "render": function ( data, type, row ) {
                            return "<a target='_blank' href='review.php?doc=" + data + "&col=" + collection_config['Name'] + "'>Edit/View</a>" ;
                        },
                        "targets": 0
                    },
                    { "searchable": false, "targets": 0 },
                    //column Title
                    {
                        "render": function ( data, type, row ) {
                            return data;
                        },
                        "targets": 2
                    },
                    //column Subtitle
                    {
                        "render": function ( data, type, row ) {
                            if(data.length > 38)
                                return data.substr(0,38) + "...";
                            return data;
                        },
                        "targets": 3
                    },
                    //{ "searchable": false, "targets": 3 },
                    //column : Date
                    {
                        "render": function ( data, type, row ) {
                            if(data == "00/00/0000")
                                return "";
                            return data;
                        },
                        "targets": 6
                    },
                    //column : HasCoast
                    {
                        "render": function ( data, type, row ) {
                            if(data == 1)
                                return "Yes";
                            return "No";
                        },
                        "targets": 7
                    },
                   // { "searchable": false, "targets": 6 },
                    //column : NeedsReview
                    {
                        "render": function ( data, type, row ) {
                            if(data == 1)
                                return "Yes";
                            return "No";
                        },
                        "targets": 8
                    },
                   // { "searchable": false, "targets": 7 },
                    {
                        "render": function ( data, type, row ) {
                        return "<a href='#' onclick='DeleteDocument(" + JSON.stringify(collection_config.Name) + "," + row[0] + ")'>Delete</a>";
                        },
                        "targets": 9
                    },

                ],
                "ajax": "list_processing.php?col=" + collection_config.Name,
                "initComplete": function()
                {
                    this.api().columns().every( function () {
                        var column = this;
                        switch(column[0][0]) //column number
                        {
                            //case: use dropdown filtering for column that has boolean value (Yes/No or 1/0)
                            case 7: //column hascoast
                            case 8: //column needsreview
                                var select = $('<select style="width:100%"><option value="">Filter...</option><option value="1">Yes</option><option value="0">No</option></select>')
                                    .appendTo( $(column.footer()).empty() )
                                    .on( 'change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );

                                        column
                                            .search(val)
                                            .draw();
                                    } );
                                break;
                            //case: DROP DOWN LIST
//                            case ?:
//                                var select = $('<select style="width:100%"><option value="">Filter...</option></select>')
//                                    .appendTo( $(column.footer()).empty() )
//                                    .on( 'change', function () {
//                                        var val = $.fn.dataTable.util.escapeRegex(
//                                            $(this).val()
//                                        );
//
//                                        column
//                                            .search(val)
//                                            .draw();
//                                    } );
//
//                                column.data().unique().sort().each( function ( d, j ) {
//                                    select.append( '<option value="'+d+'">'+d+'</option>' )
//                                } );
//                                break;
                            case 1: //lib index
                            case 2: //title
                            case 3: //subtitle
                            case 4: //customer
                            case 5: //author
                            case 6: //enddate
                                var input = $('<input type="text" style="width:100%" placeholder="Search..." value=""></input>')
                                    .appendTo( $(column.footer()).empty() )
                                    .on( 'keyup change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );

                                        column
                                            .search(val)
                                            .draw();
                                    } );
                                break;
                        }
                    } );
                },
            } );

            //hide first column (DocID)
            table.column(0).visible(true);
            //hides the columns responsible for need's input
            table.column(9).visible(false);
            <?php if($session->isAdmin()){ ?> table.column(9).visible(true); <?php } ?>
            // show or hide subtitle
            table.column(3).visible(false);
            $('#checkbox_subtitle').change(function (e) {
                e.preventDefault();
                // Get the column API object
                var column = table.column(3);
                // Toggle the visibility
                column.visible( ! column.visible() );
            } );

            // select row on single click
            $('#dtable tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } );
            //resize height of the scroller
            $("#divscroller").height($(window).outerHeight() - $(footer).outerHeight() - $("#page_title").outerHeight() - 55);
        });
    </script>

</head>
<!-- HTML BODY -->
<body>
<div id="wrap">
    <div id="main">
        <div id="divleft">
        <?php include '../../Master/header.php';
        include '../../Master/sidemenu.php' ?>
        </div>
        <div id="divright">
        <h2 id="page_title">Title</h2>
        <table width="100%">
            <tr>
                <td style="float:right;font-size:13px" colspan="100%"><input name="checkbox_subtitle" type="checkbox" id="checkbox_subtitle" />Show/Hide Subtitle</td>
            </tr>
        </table>
        <div id="divscroller">
        <table id="dtable" class="display compact cell-border hover stripe" cellspacing="0" width="100%" data-page-length='20'>
            <thead>
                <tr>
                    <th></th>
                    <th width="100px">Library Index</th>
                    <th>Document Title</th>
                    <th width="280px">Document Subtitle</th>
                    <th width="200px">Customer</th>
                    <th width="200px">Author</th>
                    <th width="70px">End Date</th>
                    <th width="40px">Has Coast</th>
                    <th width="30px">Needs Review</th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
            <tr>
                <th></th>
                <th width="100px">Library Index</th>
                <th>Document Title</th>
                <th width="280px">Document Subtitle</th>
                <th width="200px">Customer</th>
                <th width="200px">Author</th>
                <th width="70px">End Date</th>
                <th width="40px">Has Coast</th>
                <th width="30px">Needs Review</th>
                <th></th>
            </tr>
            </tfoot>
        </table>
        </div>
        </div>
    </div>
</div>
<?php include '../../Master/footer.php'; ?>
</body>
</html>
