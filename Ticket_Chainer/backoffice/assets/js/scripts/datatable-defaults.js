$.extend(true, $.fn.dataTable.defaults, {
    language: {
        url: '/data/dt_i18n_fr.json'
    },
    search: {
        "addClass": 'form-control input-lg col-xs-12'
    },
});


$.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-default';
