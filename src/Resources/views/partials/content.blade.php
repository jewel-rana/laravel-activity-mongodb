<div class="container-fluid" id="mongovity-app">
    <h4>{{ $title ?? 'Activities' }}</h4>

    <table id="mongovity-dataTable" class="table table-condensed display">
        <thead>
        <tr>
            <th>Caused at</th>
            <th>Causer Type</th>
            <th>Causer ID</th>
            <th>Causer Name</th>
            <th>Causer Mobile</th>
            <th>Subject Type</th>
            <th>Message</th>
            <th>IP</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div class="modal" id="mongovity-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View changes</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mongovity-modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
