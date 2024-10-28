jQuery(document).ready(function ($) {
    let csvFrame; // Keep a single instance of the media frame

    $('.csv-upload').on('click', function (e) {
        e.preventDefault();

        // If the frame already exists, reopen it.
        if (csvFrame) {
            csvFrame.open();
            return;
        }

        // Create a new media frame
        csvFrame = wp.media({
            title: 'Select CSV File',
            library: {
                // Attempt to restrict selection to CSV files by MIME type
                type: ['text/csv', 'application/vnd.ms-excel', 'application/csv'],
            },
            button: {
                text: 'Use this file'
            },
            multiple: false // Only allow single file selection
        });

        // When a file is selected, set the CSV URL in the input field
        csvFrame.on('select', function () {
            const attachment = csvFrame.state().get('selection').first().toJSON();
            $('.csv-url').val(attachment.url).trigger('input'); // Set URL in the input field
        });

        // Open the media frame
        csvFrame.open();
    });
});
