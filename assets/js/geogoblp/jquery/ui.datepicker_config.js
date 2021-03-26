$.datepicker.setDefaults({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    monthNamesShort: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']
});
$('input.datepicker').live('focus', function() {
    $(this).attr('readonly','readonly')
    $(this).datepicker().datepicker('show');
    true;
});