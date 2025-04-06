</table>
</div>
</div>
</div>
<script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    function format(d, data, totalRow) {
        var returnData = '<ul class="list-group">';
        for (let i = 1; i <= totalRow; i++) {
            data.forEach((colName, index) => {
                if (i == index) {
                    var colData = d[index];
                    returnData += '<li class="list-group-item bg-light-success">' + colName + ': ' + colData + '</li> ';
                }
            });
        }

        returnData += '</ul>';

        return (
            returnData
        );
    }

    $(document).ready(function () {
        var totalRow = document.getElementById("myTable").rows.length;
        var totalCell = document.getElementById("myTable").rows[0].cells.length;
        var showCol = [];
        var colHide = [];

        if ((screen.width >= 967) && (screen.width <= 1368)) {
            for (let i = 8; i < totalCell; i++) {
                colHide.push(i);
                showCol[i] = document.getElementById("myTable").rows[0].cells[i].innerText;
            }
        } else {
            for (let i = 4; i < totalCell; i++) {
                colHide.push(i);
                showCol[i] = document.getElementById("myTable").rows[0].cells[i].innerText;
            }
        }
        var table = $('#myTable').DataTable({
            lengthChange: true,
            <?php
            if (isset($tsort)) {
                echo "order: [[$tsort, 'desc']],";
            }
            ?>
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            'dom': 'Bfrtip',
            'stateSave': false,
            'buttons': [
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

        //Column Hide
        for (var k = 0; k < colHide.length; k++) {
            for (var j = 0; j < arrAdd.length; j++) {
                if (colHide[k] == arrAdd[j]) {
                    colHide.splice(k, 1);
                    k--;
                }
            }
        }
        colHide = arrRem.concat(colHide);

        //Column Add
        if (screen.width > 1368) {
            table.columns([]).visible(false);
        } else if ((screen.width >= 967) && (screen.width <= 1368)) {
            table.columns(colHide).visible(false);
        } else {
            table.columns(colHide).visible(false);
        }


        //Content Hide
        if (screen.width < 1368 && totalCell > 4) {
            var row1 = document.getElementsByTagName("tr")[0];
            var y = row1.insertCell(0);
            y.innerHTML = '<i class="bx bx-star font-24"></i>';
            var col = document.getElementsByTagName("tr").length;
            for (var i = 1; i <= col - 1; i++) {
                let insertRow = document.getElementsByTagName("tr")[i];
                const ins = insertRow.insertCell(0);
                ins.innerHTML = '<td>' +
                    '<button class="btn  btn-success">' +
                    '<i class="bx bx-star font-24"></i>' +
                    '</button></td>';
                ins.setAttribute('class', 'dt-control');
            }

            //Pagination
            $('#myTable').on('draw.dt', function () {
                var col = document.getElementsByTagName("tr").length;
                for (var i = 1; i <= col - 1; i++) {
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


        $('#myTable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(format(row.data(), showCol, totalCell)).show();
                tr.addClass('shown');
            }
        });

        table.buttons().container().appendTo('#myTable_wrapper .col-md-6:eq(0)');
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
