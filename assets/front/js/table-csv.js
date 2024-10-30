let TableCSV = function( $scope, $ ){
    var section_id = $scope.data('id'),
        table_id = '#tablentor-table-csv-'+section_id,
        is_data_table = $(table_id).data('table');
    
        if ( is_data_table && 'yes' === is_data_table ) {
            let table_wrapper =  $(table_id);
                is_paginate = table_wrapper.data('pagination'),
                is_srting = table_wrapper.data('sorting'),
                is_search = table_wrapper.data('search');

            new DataTable( table_id + ' .tablentor-table-csv', {
                responsive: true,
                paging: 'yes' === is_paginate,
                ordering: 'yes' === is_srting,
                searching: 'yes' === is_search,
            });
        }
}

jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/tablentor-table-csv.default", TableCSV);
});
  