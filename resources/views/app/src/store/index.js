import Vue from "vue";
import Vuex from "vuex";
import * as getters from "./getters";
import * as actions from "./actions";
import * as mutations from "./mutations";

Vue.use(Vuex);

export default () => {
    let state = {
        currentViewData: {},
        authUser: null,
        head: {head:'',subhead:''}
    };

    let store = new Vuex.Store({
        state,
        getters,
        actions,
        mutations,
    });
    if (module.hot) {
        module.hot.accept(["./getters", "./actions", "./mutations"], () => {
            store.hotUpdate({
                getters: require("./getters"),
                actions: require("./actions"),
                mutations: require("./mutations")
            });
        });
    }
    return store;
};
