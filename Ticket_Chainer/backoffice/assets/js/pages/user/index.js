import App from '../../modules/app'

$(async () => {
    ['user_list'].includes(App.route) ? (await import('./list')).init() : void 0;
});
