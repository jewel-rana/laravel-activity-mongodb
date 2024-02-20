<html>
<head>
    <title>Mongovity</title>
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style href="//cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"></style>
    <style>
        pre {
            display: block;
            font-size: 87.5%;
            color: #ffe38f;
            background: black;
            padding: 15px;
        }
        .toolbar {
            float: left;
        }
        #reportrange {
            margin-left: 15px;
        }
    </style>

    <script src="https://use.fontawesome.com/6592cfefc8.js"></script>
</head>
<body>
<div class="container-fluid">
    <h4>Activities</h4>

    <table id="dataTable" class="table table-condensed display">
        <thead>
        <tr>
            <td>Causes at</td>
            <td>Causer Type</td>
            <td>Causer ID</td>
            <td>Causer Name</td>
            <td>Causer Mobile</td>
            <td>Subject Type</td>
            <td>Message</td>
            <td>IP</td>
            <td>Action</td>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal Begin -->
<div class="modal" id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View changes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal ends -->

<script src="//code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    let url = "{{ route('mongovity') }}";
    let start = moment().startOf('month');
    let end = moment();

    function cb(start, end) {
        start = start;
        end = end;
        $('div#dataTable_filter #reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    let table = new DataTable('#dataTable', {
        ajax: {
            'url': url,
            pages: 5, // number of pages to cache
            'data': function (data) {
                // Read values
                data.date_from = start.format('YYYY-MM-DD');
                data.date_to = end.format('YYYY-MM-DD');
            }
        },
        processing: true,
        serverSide: true,
        "pageLength": 10,
        columns: [
            {data: "created_at", orderable: true},
            {data: "causer_type", orderable: false},
            {data: "causer_id", orderable: false},
            {data: "causer_name", orderable: false},
            {data: "causer_mobile", orderable: false},
            {data: "subject_type", orderable: false},
            {data: "message", orderable: false},
            {data: "ip", orderable: false},
            {
                "mRender": function (data, type, row) {
                    return "<button class='btn btn-xs btn-info activity' data-id='" + row['_id'] + "' data-attr='" + JSON.stringify(row['data']) + "'>View</button>"
                }
            }
        ],
        order: [[0, 'desc']]
    });
    $('div#dataTable_filter').append('<span id="reportrange"style="width: 220px;background: #fff; cursor: pointer; padding: 6px 10px; border: 1px solid #ccc; width: 100%"> <i class="fa fa-calendar"></i>&nbsp; <span></span> <i class="fa fa-caret-down"></i> </span>');

    cb(start, end);
        let myModal = $('#myModal');
    $('table').on('click', '.activity', function () {
        let modalBody = $(myModal).find('#modalBody');
        let attr = $(this).data('attr');
        $(modalBody).html("<pre>" + JSON.stringify(attr, null, 4) + "</pre>");
        $(myModal).modal("show");
    });
    $(myModal).on('hidden.bs.modal', function () {
        $('#myModal #modalBody').html('');
    });

    $('div#dataTable_filter #reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        timePicker: false,
        maxDate: moment(),
        showDropdowns: true,
        minYear: 2019,
        maxYear: parseInt(moment().format('YYYY'), 10),
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, dateRange);

    function dateRange(startDate, endDate) {
        start = startDate;
        end = endDate;
        cb(start, end);
        table.draw();
    }
</script>
</body>
</html>
