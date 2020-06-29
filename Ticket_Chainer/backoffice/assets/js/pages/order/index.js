import App from '../../modules/app'

$(async () => {
    ['order_list'].includes(App.route) ? (await import('./list')).init() : void 0;
});
