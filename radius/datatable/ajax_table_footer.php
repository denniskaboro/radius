</table>
</div>
</div>
</div>
<!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>-->

<script src="/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>


<?php
if (isset($_GET['page'])) {
    $page_name = $_GET['page'];
} else {
    $page_name = "All User";
}
if (isset($_GET['userType'])) {
    $user_type = $_GET['userType'];
} else {
    $user_type = "all";
}
if (isset($_GET['resType'])) {
    $res_type = $_GET['resType'];
} else {
    $res_type = "";
}
if (isset($_GET['zone'])) {
    $zone = $_GET['zone'];
} else {
    $zone = "";
}
if (isset($_GET['area'])) {
    $area = $_GET['area'];
} else {
    $area = "";
}
?>

<script>
    /* Formatting function for row details - modify as you need */
    function format(d, resu, data, colName) {
        var returnData = '<ul class="list-group">' +
            '<li class="list-group-item bg-light-info">' + colName[0] + ': ' + d[data[0]] + '</li> ' +
            '<li class="list-group-item list-group-item-dark">' + colName[1] + ': ' + d[data[1]] + '</li> ' +
            '<li class="list-group-item list-group-item-primary">' + colName[2] + ': ' + d[data[2]] + '</li> ' +
            '<li class="list-group-item list-group-item-info">' + colName[3] + ': ' + d[data[3]] + '</li> ' +
            '<li class="list-group-item list-group-item-success">' + colName[4] + ': ' + d[data[4]] + '</li> ' +
            '<li class="list-group-item list-group-item-warning">' + colName[5] + ': ' + d[data[5]] + '</li> ' +
            '<li class="list-group-item list-group-item-danger">' + colName[6] + ': ' + d[data[6]] + '</li> ' +
            '<li class="list-group-item list-group-item-primary">' + colName[7] + ': ' + d[data[7]] + '</li> ' +
            '<li class="list-group-item list-group-item-info">' + colName[8] + ': ' + d[data[8]] + '</li> ' +
            '<li class="list-group-item list-group-item-success">' + colName[9] + ': ' + d[data[9]] + '</li> ' +
            '<li class="list-group-item list-group-item-danger">' + colName[10] + ': ' + d[data[10]] + '</li> ' +
            '</ul>';
        return (
            returnData
        );

    }

    function userTypeDataSort(userType) {
        let zone = "<?php echo $zone; ?>";
        let area = "<?php echo $area; ?>";
        let resType = "<?php echo $res_type; ?>";
        window.location.assign("/admin=user_list/page=All&userType=" + userType + "&resType=" + resType + "&zone=" + zone + "&area=" + area);

    }

    function zoneDataSort(zone) {
        let userType = "<?php echo $user_type; ?>";
        let area = "<?php echo $area; ?>";
        let resType = "<?php echo $res_type; ?>";
        window.location.assign("/admin=user_list/page=All&userType=" + userType + "&resType=" + resType + "&zone=" + zone + "&area=" + area);

    }

    function areaDataSort(area) {
        let userType = "<?php echo $user_type; ?>";
        let zone = "<?php echo $zone; ?>";
        let resType = "<?php echo $res_type; ?>";
        window.location.assign("/admin=user_list/page=All&userType=" + userType + "&resType=" + resType + "&zone=" + zone + "&area=" + area);

    }

    function resTypeDataSort(resType) {
        let area = "<?php echo $area; ?>";
        let userType = "<?php echo $user_type; ?>";
        let zone = "<?php echo $zone; ?>";
        window.location.assign("/admin=user_list/page=All&userType=" + userType + "&resType=" + resType + "&zone=" + zone + "&area=" + area);

    }

    $(document).ready(function () {
        const table_col = {
            'last': '5',
            'tableName': '<?php echo $page_name; ?>',
            'userType': '<?php echo $user_type; ?>',
            'resType': '<?php echo $res_type; ?>',
            'zone': '<?php echo $zone; ?>',
            'area': '<?php echo $area; ?>',
        };

        var table = $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '/datatable/user_list_process.php',
                'data': table_col
            },
            'columns': [
                {data: 'username'},
                {data: 'full_name'},
                {data: 'package'},
                {data: 'net_bill'},
                {data: 'billing_date'},
                <?php if ($API->CheckPermission("RENEW", "3")) { ?>
                {data: 'renew'},
                <?php } ?>
                {data: 'status'},
                {data: 'online'},
                {data: 'bn_name'},
                {data: 'mobile'},
                {data: 'zone'},
                {data: 'area'},
                {data: 'address'},
                {data: 'description'},
                <?php if ($API->CheckPermission("USER", "3")) { ?>
                {data: 'mac'},
                {data: 'onoff'},
                <?php }
                if ($API->CheckPermission("USER", "4")) { ?>
                {data: 'delete'}
                <?php } ?>
            ],
            'lengthChange':
                false,
            <?php
            if (isset($tsort)) {
                echo "order: [[$tsort, 'desc']],";
            }
            ?>
            'lengthMenu':
                [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all']
                ],
            'dom':
                'Bfrtip',
            'stateSave':
                true,
            'buttons':
                [
                    'pageLength',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',
                    {
                        extend: 'collection',
                        text: 'View',
                        buttons: [
                            'colvis',
                            'columnsToggle'
                        ]
                    },
                ]
        });

        if (screen.width < 1368) {
            // var row1 = document.getElementsByTagName("tr")[0];
            // var y = row1.insertCell(0);
            // y.innerHTML = '<i class="bx bx-star font-24"></i>';
            // var col = document.getElementsByTagName("tr").length;
            // for (var i = 0; i <= col - 2; i++) {
            //     let insertRow = document.getElementsByTagName("tr")[i];
            //     const ins = insertRow.insertCell(0);
            //     ins.innerHTML = '<td>' +
            //         '<button class="btn  btn-success">' +
            //         '<i class="bx bx-star font-24"></i>' +
            //         '</button></td>';
            //     ins.setAttribute('class', 'dt-control');
            // }

            //Pagination
            $('#myTable').on('draw.dt', function () {
                var col = document.getElementsByTagName("tr").length;
                for (var i = 0; i <= col - 2; i++) {
                    if (document.getElementById("myTable").rows[i].cells[0].className != "dt-control") {
                        let insertRow = document.getElementsByTagName("tr")[i];
                        const ins = insertRow.insertCell(0);
                        ins.innerHTML = '<td>' +
                            '<button class="btn btn-success">' +
                            '<i class="bx bx-star font-24"></i>' +
                            '</button></td>';
                        ins.setAttribute('class', 'dt-control');
                    }
                }
            });
        }

        if (screen.width > 1368) {
            table.columns([]).visible(false);
            var resulation = '1920';
        } else if ((screen.width >= 967) && (screen.width <= 1368)) {
            table.columns([9, 10, 11, 12, 13, 14, 15, 16]).visible(false);
            var resulation = '1366';
            var showData = [
                "net_bill",
                "online",
                "bn_name",
                "mobile",
                "zone",
                "area",
                "address",
                "description",
                <?php if ($API->CheckPermission("USER", "3")) { ?>
                "mac",
                "onoff",
                <?php }
                if ($API->CheckPermission("USER", "4")) { ?>
                "delete"
                <?php } ?>
            ];
            var colName = [
                "Bill",
                "Online",
                "Bn Name",
                "Mobile",
                "Zone",
                "Area",
                "Address",
                "Description",
                <?php if ($API->CheckPermission("USER", "3")) { ?>
                "MAC",
                "Off/On",
                <?php }
                if ($API->CheckPermission("USER", "4")) { ?>
                "Delete"
                <?php } ?>
            ];
        } else {
            table.columns([3, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]).visible(false);
            var resulation = '480';
            var showData = [
                "net_bill",
                "online",
                "bn_name",
                "mobile",
                "zone",
                "area",
                "address",
                "description",
                <?php if ($API->CheckPermission("USER", "3")) { ?>
                "mac",
                "onoff",
                <?php }
                if ($API->CheckPermission("USER", "4")) { ?>
                "delete"
                <?php } ?>
            ];
            var colName = [
                "Bill",
                "Online",
                "Bn Name",
                "Mobile",
                "Zone",
                "Area",
                "Address",
                "Description",
                <?php if ($API->CheckPermission("USER", "3")) { ?>
                "MAC",
                "Off/On",
                <?php }
                if ($API->CheckPermission("USER", "4")) { ?>
                "Delete"
                <?php } ?>
            ];
        }

        $('#myTable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data(), resulation, showData, colName)).show();
                tr.addClass('shown');
            }
        });
    });

</script>

<div class="modal fade" role="dialog" id="payModal" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="paymentmodel">

            </div>
        </div>
    </div>
</div>
