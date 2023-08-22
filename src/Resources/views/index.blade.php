<html>
<head>
    <title>Mongovity</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style href="//cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"></style>
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
            <td>Action</td>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<script src="//code.jquery.com/jquery-latest.min.js"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    let table = new DataTable('#dataTable', {
        ajax: "{{ route('mongovity') }}",
        processing: true,
        serverSide: true,
        "pageLength": 25,
        columns: [
            {data: "created_at", orderable: true},
            {data: "causer_type", orderable: false},
            {data: "causer_id", orderable: false},
            {data: "causer_name", orderable: false},
            {data: "causer_mobile", orderable: false},
            {data: "message", orderable: false},
            {data: "message", orderable: false}
        ],
        order: [[0, 'desc']]
    });
</script>
</body>
</html>
