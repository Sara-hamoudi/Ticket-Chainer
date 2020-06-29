import App from '../../modules/app'

$(async () => {
    ['game_list'].includes(App.route) ? (await import('./list')).init() : void 0;
    ['game_create','game_edit'].includes(App.route) ? (await import('./form')).init() : void 0;
});
