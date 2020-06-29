import Mustache from "mustache";


export const actionBtnViewTpl = $('#action_btn_view_tpl').html();
export const actionBtnEditTpl = $('#action_btn_edit_tpl').html();
export const actionBtnDeleteTpl = $('#action_btn_delete_tpl').html();

Mustache.parse(actionBtnViewTpl);
Mustache.parse(actionBtnEditTpl);
Mustache.parse(actionBtnDeleteTpl);