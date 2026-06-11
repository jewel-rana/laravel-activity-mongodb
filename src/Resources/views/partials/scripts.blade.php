@unless(config('mongovity.skip_daterangepicker_assets'))
    @once('mongovity-daterangepicker-assets')
        <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    @endonce
@endunless

@once('mongovity-scripts')
<script>
(function ($) {
    'use strict';

    $(function () {
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('Mongovity requires DataTables. Load jQuery DataTables in your application layout.');
            return;
        }

        if (typeof moment === 'undefined') {
            console.error('Mongovity requires Moment.js for the date range filter.');
            return;
        }

        const url = @json(route('mongovity'));
        let start = moment().startOf('month');
        let end = moment();

        function updateRangeLabel(rangeStart, rangeEnd) {
            $('#mongovity-dataTable_filter #mongovity-reportrange span').html(
                rangeStart.format('MMMM D, YYYY') + ' - ' + rangeEnd.format('MMMM D, YYYY')
            );
        }

        const table = $('#mongovity-dataTable').DataTable({
            ajax: {
                url: url,
                data: function (data) {
                    data.date_from = start.format('YYYY-MM-DD');
                    data.date_to = end.format('YYYY-MM-DD');
                }
            },
            processing: true,
            serverSide: true,
            pageLength: 10,
            columns: [
                {data: 'created_at', orderable: true},
                {data: 'causer_type', orderable: false},
                {data: 'causer_id', orderable: false},
                {data: 'causer_name', orderable: false},
                {data: 'causer_mobile', orderable: false},
                {data: 'subject_type', orderable: false},
                {data: 'message', orderable: false},
                {data: 'ip', orderable: false},
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        const payload = $('<div/>').text(JSON.stringify(row.data ?? {})).html();

                        return '<button type="button" class="btn btn-xs btn-info mongovity-activity"'
                            + ' data-id="' + (row._id ?? '') + '"'
                            + ' data-attr="' + payload + '">View</button>';
                    }
                }
            ],
            order: [[0, 'desc']]
        });

        $('#mongovity-dataTable_filter').append(
            '<span id="mongovity-reportrange">'
            + '<i class="fa fa-calendar"></i>&nbsp; <span></span> <i class="fa fa-caret-down"></i>'
            + '</span>'
        );

        updateRangeLabel(start, end);

        const $modal = $('#mongovity-modal');

        $('#mongovity-app').on('click', '.mongovity-activity', function () {
            let attr = $(this).data('attr');

            if (typeof attr === 'string') {
                try {
                    attr = JSON.parse(attr);
                } catch (e) {
                    // Keep the original string when it is not valid JSON.
                }
            }

            $('#mongovity-modal-body').html('<pre>' + JSON.stringify(attr, null, 4) + '</pre>');

            if (typeof $modal.modal === 'function') {
                $modal.modal('show');
            } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance($modal[0]).show();
            }
        });

        $modal.on('hidden.bs.modal', function () {
            $('#mongovity-modal-body').html('');
        });

        $('#mongovity-dataTable_filter #mongovity-reportrange').daterangepicker({
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
        }, function (startDate, endDate) {
            start = startDate;
            end = endDate;
            updateRangeLabel(start, end);
            table.draw();
        });
    });
})(jQuery);
</script>
@endonce
