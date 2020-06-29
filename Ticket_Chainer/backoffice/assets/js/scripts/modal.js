import Modal from "../modules/modal";
import voca from 'voca';

$('body').on('click', 'button[data-modal-url]', (e) => {
    const $this = $(e.currentTarget);
    const data = $this.data();
    const options = [];
    for (const [attr, val] of Object.entries(data)) {
        let option = voca.splice(voca.slugify(attr), 0, 'modal-'.length);
        options[option] = val
    }
    (new Modal(options)).show();
});