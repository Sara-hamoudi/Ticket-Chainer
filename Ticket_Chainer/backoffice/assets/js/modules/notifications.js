import toastr from 'toastr';

const success = (message) => {
    toastr.success(message, null, {
        showMethod: 'slideDown',
        hideMethod: 'slideUp',
        positionClass: 'toast-bottom-full-width',
        timeOut: 3000
    });
};

const error = (message) => {
    toastr.error(message, null, {
        showMethod: 'slideDown',
        hideMethod: 'slideUp',
        positionClass: 'toast-bottom-full-width',
        timeOut: 3000
    });
};

export default {
    success,
    error
}
