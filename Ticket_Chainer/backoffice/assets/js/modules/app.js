import Routing from "./routing";

const vars = window.__vars__ || {};
export default {
    vars,
    generateUrl: (route, params = null, absolute = false) => {
        return Routing.generate(route, params)
    },
    route: vars.route_name
}