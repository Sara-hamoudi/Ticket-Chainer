import notifications from '../../modules/notifications';
import SwitcheryCustom from '../../modules/switchery-custom';
import {startLoading, stopLoading} from '../../modules/loading-button'
import {fetch} from 'whatwg-fetch'

$('body').on('submit', 'form', async function (e) {
    e.preventDefault();
    const $button = $(e.currentTarget).find('button[type=submit]');
    startLoading($button);
    const url = $(this).attr('action');
    const method = $(this).attr('method');
    const response = await fetch(url, {
        method: method,
        body: new FormData(this),
    });
    if (response.ok) {
        notifications.success('Les modifications ont été enregistrées avec succès!');
        if (response.headers.has('Redirect-To-Url')) {
            window.location.replace(response.headers.get('Redirect-To-Url'));
        }
    } else {
        notifications.success('Une erreur est survenue');
    }
    stopLoading($button);
});

export const init = () => {
    $('select[name="game[club]"]').select2();
    $('select[name="game[opponent]"]').select2({
        placeholder: "Choisir l'équipe adverse",
        allowClear: true
    });

    SwitcheryCustom.apply('.switchery');
    $('#game_date').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
        //minDate: moment(),
        locale: {
            format: 'DD/MM/YYYY HH:mm',
            applyLabel: 'Confirmer',
            cancelLabel: 'Annuler'
        }
    }, function (start, end, label) {

    });
};