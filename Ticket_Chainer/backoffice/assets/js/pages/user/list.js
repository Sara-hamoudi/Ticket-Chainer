import Routing from '../../modules/routing'
import Mustache from 'mustache'
import moment from 'moment'
import voca from 'voca';

export let dt;

const dtActionViewBtn = $('#action_btn_view_tpl').html();
Mustache.parse(dtActionViewBtn);

const dtColumns = [
    {
        width: '150px',
        render: function (data, type, row, meta) {
            let title;
            if (row.title === 'mr') {
                title = 'Mr.'
            } else if (row.title === 'mrs') {
                title = 'Mme.'
            } else {
                title = '?'
            }
            return [title, voca.capitalize(row.name), voca.upperCase(row.surname)].join(' ');
        }
    },

    {
        data: 'dateOfBirth',
        class: 'text-center',
        width: '1%',
        render: function (data, type, row, meta) {
            const dateStr = moment(data).format('L');
            if (type === 'sort') {
                return moment(data).format('YYYYMMDDHHmmss');
            } else if (type === 'export') {
                return dateStr;
            }
            const badge = `<div class="badge  bg-grey font-size-small pull-right">${moment(data).fromNow(true)}</div>`;
            return `${dateStr} ${badge}`;
        }
    },
    {
        data: 'createdAt',
        class: 'text-center',
        width: '1%',
        render: function (data, type, row, meta) {
            if (type === 'sort') {
                return moment(data).format('YYYYMMDDHHmmss');
            } else {
                return moment(data).format('L');
            }
        }
    },

    {
        data: 'address',
        width: '150px',
        class: 'text-center',
        orderable: true,
        render: function (data, type, row, meta) {
            return `${voca.capitalize(data.city)} (${data.postalCode}), ${voca.upperCase(data.country)}`
        }
    },

    {
        data: 'status',
        width: '1%',
        class: 'text-center',
        orderable: true,
        render: function (data, type, row, meta) {

            if (type === 'sort') {
                return data;
            } else {
                if (data === 'active') {
                    return '<i class="fa fa-check-circle green" title="Actif" style="cursor: help; font-size: 24px"></i>'
                } else if (data === 'pending_activation') {
                    return '<i class="fa fa-hourglass-half orange" title="En attente d\'activation" style="cursor: help; font-size: 20px"></i>'
                } else {
                    return data;
                }
            }
        }
    },

    {
        width: '130px',
        class: 'text-center',
        orderable: false,
        render: function (data, type, row, meta) {
            const btnShow = Mustache.render(dtActionViewBtn, {
                modalUrl: Routing.generate('user_show', {id: row.id}),
            });
            return [btnShow].join('');
        }
    }];

const exportCommonOptions = {
    exportOptions: {
        orthogonal: 'export',
        columns: [
            'id:name',
            'title:name',
            'name:name',
            'surname:name',
            'dateOfBirth:name',
            'email:name',
            'phone:name',
            'address_lines:name',
            'address_city:name',
            'address_postalCode:name',
            'address_country:name',
            'createdAt:name',
            'status:name',
        ],

    }
};

const exportColumns = [
    {
        data: 'id',
        name: 'id',
        title: 'ID',
        visible: false
    },
    {
        data: 'title',
        name: 'title',
        title: 'Titre',
        visible: false
    },
    {
        data: 'name',
        name: 'name',
        title: 'Prénom',
        visible: false
    },
    {
        data: 'surname',
        name: 'surname',
        title: 'Nom',
        visible: false
    },
    {
        data: 'dateOfBirth',
        name: 'dateOfBirth',
        title: 'Date de naissance',
        visible: false,
        render: function (data, type, row, meta) {
            return moment(data).format('L')
        }
    },
    {
        data: 'email',
        name: 'email',
        title: 'E-mail',
        visible: false
    },
    {
        data: 'phone',
        name: 'phone',
        title: 'Téléphone',
        visible: false
    },
    {
        data: 'status',
        name: 'status',
        title: 'Statut',
        visible: false,
        render: function (data) {
            return voca.upperCase(data)
        }
    },
    {
        data: 'createdAt',
        name: 'createdAt',
        title: 'Date inscription',
        visible: false,
        render: function (data, type, row, meta) {
            return moment(data).format('YYYY-MM-DD HH:mm')
        }
    },
    {
        data: 'address',
        name: 'address_lines',
        title: 'Adresse',
        visible: false,
        render: function (data) {
            return [data.line1, data.line2, data.line3].join(' ')
        }
    },
    {
        data: 'address.city',
        name: 'address_city',
        title: 'Ville',
        visible: false
    },
    {
        data: 'address.postalCode',
        name: 'address_postalCode',
        title: 'CP',
        visible: false
    },
    {
        data: 'address.country',
        name: 'address_country',
        title: 'Pays',
        visible: false
    },
];

const ajaxOptions = {
    url: Routing.generate('user_list_data')
};

export const init = () => {
    dt = $('#userDataTable').DataTable({
        ajax: ajaxOptions,
        order: [[2, 'desc']],
        columns: [
            ...dtColumns,
            ...exportColumns
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                ...exportCommonOptions,
            },
            {
                extend: 'csvHtml5',
                ...exportCommonOptions,
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                ...exportCommonOptions,
            }
        ],
        paging: true,
        fixedHeader: {
            header: true,
            headerOffset: $('.header-navbar').outerHeight()
        },
        rowCallback: function (row, data, index) {

        },
        initComplete: function () {

        }
    });
};