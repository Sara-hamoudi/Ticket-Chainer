import Routing from '../../modules/routing'
import Mustache from 'mustache'
import moment from 'moment'
import {actionBtnViewTpl, actionBtnEditTpl, actionBtnDeleteTpl } from '../../modules/datatable-common-buttons'

export let dt;

const dtActionViewBtn = $('#action_btn_view_tpl').html();
Mustache.parse(dtActionViewBtn);

const dtColumns = [
    {
        width: '100px',
        data: 'id',
    },
    {
        data: 'createdAt',
        class: 'text-center',
        width: '130px',
        render: function (data, type, row, meta) {
            if (type==='display') {
                return moment(data).format('YYYY-MM-DD HH:mm');
            }
            return moment(data).format('YYYYMMDDHHmmss')
        }
    },
    {
        data: 'status',
        width: '1%',
        class: 'text-center',
        orderable: true,
        render: function (data, type, row, meta) {
            if (type === 'sort') {
                return data.value;
            }


            return data.display
        }
    },
    {
        width: '50px',
        data: 'totalAmount',
    },
    {
        width: '150px',
        data: 'user.displayName',
    },
    {
        width: '130px',
        class: 'text-center',
        orderable: false,
        render: function (data, type, row, meta) {

            const buttons = [];
            buttons.push (Mustache.render(dtActionViewBtn, {
                modalUrl: Routing.generate('order_show', {id: row.id}),
                modalSize: 'lg',
            }));

            buttons.push(Mustache.render(actionBtnDeleteTpl, {
                title: 'Commande #'+row.id,
                id: row.id
            }));
            return buttons.join('');
        }
    }];

const ajaxOptions = {
    url: Routing.generate('order_list_data')
};

export const init = () => {
    dt = $('#orderDataTable').DataTable({
        ajax: ajaxOptions,
        order: [[1, 'desc']],
        columns: [
            ...dtColumns
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