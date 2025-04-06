
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary"><?php echo $page; ?></h5>
    </div>
    <style>
        .btn-group, .btn-group-vertical {
            height: 30px !important;
            background-color: rgba(70, 72, 141, 0.95);

        }

        #myTable_filter {
            margin-top: 20px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin-left: 0px !important;
            padding: .12em .25em !important;
        }

        .btn {
            color: white !important;
        }

        .btn-success {
            background: #00ac74 !important;
        }

        #cus {
            height: 30px !important;
            font-size: 14px !important;
            margin: 5px 5px !important;
        }

        div.dataTables_processing {
            background: #05b5a5 !important;
            color: white !important;
        }

        .dropdown-item.active, .dropdown-item:active {
            color: #fff;
            text-decoration: none;
            background-color: #4a518a;
        }
    </style>
    <link href="/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>

    <div class="card-body">
        <div class="table-responsive">
            <table id="myTable" class="table table-bordered table-striped">
