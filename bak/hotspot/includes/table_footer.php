</table>
</div>
</div>
</div>
<script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        var table = $('#myTable').DataTable({
            lengthChange: true,
            <?php
            if (isset($tsort)) {
                echo "order: [[$tsort, 'desc']],";
            }
            ?>

            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            buttons: ['copy', 'excel', 'csv', 'pdf', 'print']
        });
        table.buttons().container()
            .appendTo('#myTable_wrapper .col-md-6:eq(0)');
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
