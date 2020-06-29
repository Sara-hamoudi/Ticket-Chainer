import Mustache from 'mustache';
import uuid from 'uuid'

const modalTpl = $('#modal_tpl').html();
Mustache.parse(modalTpl);


export default class Modal {

    constructor(options) {
        this.options = options;
        const name = options.name || uuid.v4();
        this.id = ['modal', name].join('__');
        this.url = options.url;
        this.$modal = this.createModal();
        this.setEventListeners();
    }

    setEventListeners() {
        if (this.$modal.length) {
            this.$modal.on('hidden.bs.modal', () => {
                this.destroy();
            });
        }
    }

    createModal() {
        const modalHtml = Mustache.render(modalTpl, {
            id: this.id,
            size: this.options.size || 'md'
        });
        return $(modalHtml);
    }

    loadContent() {
        this.$modal.find('.modal-content').load(this.url, () => {
            $('.modal').trigger('modal.contentLoaded', $(this));
        });
    }

    getId() {
        return this.id
    }

    show() {
        if (this.$modal.length) {
            this.$modal.modal('show');
            this.loadContent()
        }
    }

    destroy() {
        if (this.$modal.length) {
            this.$modal.remove();
        }
    }
}