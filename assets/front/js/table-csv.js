let TableCSV = function( $scope, $ ){
    var section_id = $scope.data('id'),
        table_id = '#tablentor-table-csv-'+section_id,
        is_data_table = $(table_id).data('table');
    
        if ( is_data_table && 'yes' === is_data_table ) {
            new DataTable( table_id + ' .tablentor-table-csv', {
            });
        }
}

jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/tablentor-table-csv.default", TableCSV);
});
  