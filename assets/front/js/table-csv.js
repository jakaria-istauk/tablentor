let TableCSV = function( $scope, $ ){
    var section_id = $scope.data('id'),
        table_id = '#tablentor-table-csv-'+section_id;
    
        new DataTable( table_id + ' .tablentor-table-csv', {
            responsive: true
        });
}

jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/tablentor-table-csv.default", TableCSV);
});
  