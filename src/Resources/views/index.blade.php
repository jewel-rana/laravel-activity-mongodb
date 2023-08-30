<html>
<head>
    <title>Mongovity</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style href="//cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"></style>
    <style>
        pre {
            display: block;
            font-size: 87.5%;
            color: #ffe38f;
            background: black;
            padding: 15px;
        }
    </style>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    let url = "{{ route('mongovity') }}";
    let table = new DataTable('#dataTable', {
        ajax: {
            'url': url,
            pages: 5, // number of pages to cache
            'data': function (data) {
                // Read values
                data.date_from = "2023-08-29";
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
    let modal = $('#myModal');
    $('table').on('click', '.activity', function () {
        let modalBody = $(modal).find('#modalBody');
        let attr = $(this).data('attr');
        $(modalBody).html("<pre>" + JSON.stringify(attr, null, 4) + "</pre>");
        $(modal).modal("show");
    });
    $(modal).on('hidden.bs.modal', function () {
        $('#myModal #modalBody').html('');
    });
</script>
</body>
</html>
