import Routing from '../../modules/routing'
import Mustache from 'mustache'
import {fetch} from 'whatwg-fetch'
import Swal from 'sweetalert2'
import moment from 'moment'
import {actionBtnViewTpl, actionBtnEditTpl, actionBtnDeleteTpl } from '../../modules/datatable-common-buttons'
const listDataUrl = Routing.generate('game_list_data');
export let gameDatatable;

const $dt = $('#gameDataTable');
const datatableProgressBarTpl = $('#datatable_progressbar_tpl').html();

const datatableStatusRowTpl = $('#datatable_status_row_tpl').html();


$('body').on('click', 'button[data-action^="dt.row."]', (e) => {
    const $this = $(e.currentTarget);
    const data = $this.data();
    if (data.action === 'dt.row.delete') {
        deleteGame(data)
    } else {
        log.error('action not defined');
    }
});

const deleteGame = async (game) => {
    try {
        const result = await Swal.fire({
            title: 'Êtes-vous sûr de vouloir supprimer ce match?',
            text: game.title,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler'
        });
        if (result.value) {
            await fetch(Routing.generate('game_delete', {'id': game.id}), {
                method: 'DELETE'
            });
            gameDatatable.ajax.reload();
            await Swal.fire(
                'Supprimé !',
                'Le match a été supprimé',
                'success'
            )
        }
    } catch (e) {
        console.error(e)
    }
};

const renderColDateOfBirth = (data, type, row, meta) => {
    if (type === 'sort') {
        return data;
    } else {
        const dateStr = moment(data).format('LLLL');
        const badge = `<div class="badge bg-grey font-size-small pull-right">${moment(row.date).fromNow()}</div>`;
        return `${dateStr} ${badge}`
    }
};

const renderColStatus = (data, type, row, meta) => {

    const statusClass = {
        draft: 'bg-grey bg-darken-1',
        published: 'bg-green bg-darken-1',
        unpublished: 'bg-orange bg-darken-3',
    };

    return Mustache.render(datatableStatusRowTpl, {
        class: statusClass[data],
        status: Translator.trans(`game.status.${data}`)
    });

};

const renderColStats = (data, type, row, meta) => {
    if (type === 'sort') {
        return data ? ((data.stockInitial - data.stock) / data.stockInitial) : null;
    } else {
        if (!data) {
            return 'ERROR';
        }
        return Mustache.render(datatableProgressBarTpl, {
            min: 0,
            max: new Intl.NumberFormat('fr-FR').format(data.stockInitial),
            value: new Intl.NumberFormat('fr-FR').format(data.stockInitial - data.stock),
            rate: Math.round(((data.stockInitial - data.stock) / data.stockInitial) * 100)
        });
    }
};

const renderColAction = (data, type, row, meta) => {
    const buttons = [];

    buttons.push(Mustache.render(actionBtnViewTpl, {
        url: Routing.generate('game_show', {id: row.id})
    }));
    buttons.push(Mustache.render(actionBtnEditTpl, {
        url: Routing.generate('game_edit', {id: row.id})
    }));

    buttons.push(Mustache.render(actionBtnDeleteTpl, {
        title: row.title + ' [ ' + moment(row.date).format('LLLL') + ' ]',
        id: row.id
    }));
    return buttons.join('')
};

const initDataTable = () => {
    gameDatatable = $dt.DataTable({
        ajax: {
            url: listDataUrl
        },
        order: [[1, 'desc']],
        columns: [
            {
                data: 'opponent.shortName',
                orderable: false
            },
            {
                data: 'date',
                class: 'text-center',
                width: '320px',
                render: renderColDateOfBirth
            },
            {
                data: 'status',
                width: '100px',
                class: 'text-center',
                orderable: true,
                render: renderColStatus
            },
            {
                data: 'ticketStock',
                width: '100px',
                class: 'text-center',
                orderable: true,
                render: renderColStats
            },
            {
                width: '130px',
                class: 'text-center',
                orderable: false,
                render: renderColAction
            }
        ]
    });
};

export const init = () => {
    initDataTable();
};