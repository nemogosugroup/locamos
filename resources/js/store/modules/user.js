import RepositoryFactory from "@/utils/RepositoryFactory";
import {getAccessToken, setAccessToken, removeToken, setAuth, getAuth, removeAuth} from '@/utils/auth'
import router, { resetRouter } from "@/router";
const UserRepository = RepositoryFactory.get('user');
const state = {
    token: getAccessToken(),
    name: "",
    avatar: "",
    introduction: "",
    gold: "",
    experience: "",
    data_achievement:false,
    level: 1,
    user: {},
    roles: [],
    courses: [],
    equipments: [],
    training: [],
    training_detail: [],
    data_medal: []
};

const mutations = {
    SET_TOKEN: (state, token) => {
        state.token = token;
    },
    SET_INTRODUCTION: (state, introduction) => {
        state.introduction = introduction;
    },
    SET_NAME: (state, name) => {
        state.name = name;
    },
    SET_AVATAR: (state, avatar) => {
        state.avatar = avatar;
    },
    SET_ROLES: (state, roles) => {
        state.roles = roles;
    },
    SET_INFO: (state, user) => {
        state.user = user;
    },
    SET_TRAINING: (state, training) => {
        state.training = training;
    },
    SET_TRAINING_DETAIL: (state, data) => {
        state.training_detail = data;
    },
    SET_COURSES: (state, courses) => {
        state.courses = courses;
    },
    SET_EQUIPMENTS: (state, equipments) => {
        state.equipments = equipments;
    },
    SET_GOLD: (state, gold) => {
        state.gold = gold;
    },
    SET_EXPERIENCE: (state, experience) => {
        state.experience = experience;
    },
    SET_LEVEL: (state, level) => {
        state.level = level;
    },
    SET_DATA_MEDAL: (state, data) => {
        state.data_medal = data;
    },
    SET_DATA_ACHIEVEMENT: (state, data) => {
        state.data_achievement = data;
    },
};

const actions = {
    // user login
    login({commit}, userInfo) {
        const { email, password } = userInfo;
        return new Promise((resolve, reject) => {
            UserRepository.login({email: email, password: password}).then(response => {
                const { data } = response;
                if (data.success) {
                    let token = data.data.access_token;
                    let roles = data.data.roles;
                    let training = data.data.training;
                    let users = data.data;
                    commit('SET_INFO', users);
                    commit('SET_TOKEN', token);
                    commit('SET_ROLES', roles);
                    commit('SET_NAME', data.data.fullname);
                    commit('SET_TRAINING', training);
                    commit('SET_GOLD', data.data.gold);
                    commit('SET_EXPERIENCE', data.data.experience);
                    commit('SET_LEVEL', data.data.level);
                    commit('SET_DATA_MEDAL', data.data_medal);
                    if(data.data.equipments){
                        let equipments = data.data.equipments.map(item => {
                            return item.id
                        });
                        commit('SET_EQUIPMENTS', equipments);
                    }
                    if(data.data.courses){
                        let courses = data.data.courses.map(item => {
                            return item.id
                        });
                        commit('SET_COURSES', courses);
                    }
                    if(users.achievement_user){
                        let achievements = {
                            'medal':users.achievement_user?.medal.title,
                            'achievement':users.achievement_user?.category.title,
                        };
                        commit('SET_DATA_ACHIEVEMENT', achievements);
                    }                   
                    setAccessToken(token)
                    setAuth(JSON.stringify(data.data))
                }
                resolve(response)
            }).catch(e => {
                console.log(`error`, e);
                reject(e)
            })

        }).catch(error => {
            reject(error)
        })
    },

    // get user info
    getInfo({ commit, state }) {
        return new Promise((resolve, reject) => {
            UserRepository.getInfo(state.token)
                .then((response) => {
                    const { data } = response;
                    if (data.success) {
                        let token = data.data.access_token;
                        let roles = data.data.roles;
                        let training = data.data.training;
                        let users = data.data;
                        commit('SET_INFO', users);
                        commit('SET_TOKEN', token);
                        commit('SET_ROLES', roles);
                        commit('SET_NAME', data.data.fullname);
                        commit('SET_TRAINING', training);
                        commit('SET_GOLD', data.data.gold);
                        commit('SET_EXPERIENCE', data.data.experience);
                        commit('SET_LEVEL', data.data.level);
                        commit('SET_DATA_MEDAL', data.data_medal);
                        if(data.data.equipments){
                            let equipments = data.data.equipments.map(item => {
                                return item.id
                            });
                            commit('SET_EQUIPMENTS', equipments);
                        }
                        if(data.data.courses){
                            let courses = data.data.courses.map(item => {
                                return item.id
                            });
                            commit('SET_COURSES', courses);
                        }
                        if(users.achievement_user){
                            let achievements = {
                                'medal':users.achievement_user?.medal.title,
                                'achievement':users.achievement_user?.category.title,
                            };
                            commit('SET_DATA_ACHIEVEMENT', achievements);
                        }     
                        setAccessToken(token)
                        setAuth(JSON.stringify(data.data))
                    }
                    resolve(response)
                })
                .catch((error) => {
                    reject(error);
                });
        });
    },
     // get user info
    getRoles({ commit, state }) {
        return new Promise((resolve, reject) => {
            UserRepository.getRoles(state.token)
                .then((response) => {
                    const { data } = response;                    
                    if (!data) {
                        commit("SET_TOKEN", "");
                        commit("SET_ROLES", []);
                        removeToken();
                        reject("Verification failed, please Login again.");
                    }
                    let results = data['data'];

                    // roles must be a non-empty array
                    if (!results['roles'] || results['roles'].length <= 0) {
                        reject("Verification failed, please Login again.");
                    }

                    commit("SET_ROLES", results['roles']);
                    // commit("SET_NAME", name);
                    // commit("SET_AVATAR", avatar);
                    // commit("SET_INTRODUCTION", introduction);
                    resolve(results['roles']);
                })
                .catch((error) => {
                    reject(error);
                });
        });
    },
    // user logout
    logout({ commit, state, dispatch }) {
        return new Promise((resolve, reject) => {
            UserRepository.logout(state.token)
                .then(() => {
                    commit("SET_TOKEN", "");
                    commit("SET_ROLES", []);
                    removeToken();
                    resetRouter();
                    dispatch("tagsView/delAllViews", null, { root: true });
                    resolve();
                })
                .catch((error) => {
                    reject(error);
                });
        });
    },

    // remove token
    resetAccessToken({ commit }) {
        return new Promise((resolve) => {
            commit("SET_TOKEN", "");
            commit("SET_ROLES", []);
            removeToken();
            resolve();
        });
    },

    // dynamically modify permissions
    async changeRoles({ commit, dispatch }, role) {
        // const token = role + "-token";

        // commit("SET_TOKEN", token);
        // setAccessToken(token);

        // const { roles } = await dispatch("getInfo");

        // resetRouter();

        // // generate accessible routes map based on roles
        // const accessRoutes = await dispatch(
        //     "permission/generateRoutes",
        //     roles,
        //     { root: true }
        // );
        // // dynamically add accessible routes
        // router.addRoutes(accessRoutes);

        // reset visited views and cached views
        dispatch("tagsView/delAllViews", null, { root: true });
    },

    update({ commit, state }, data){
        if (data) {
            let roles = data.roles;
            let users = data;
            commit('SET_INFO', users);
            commit('SET_ROLES', roles);
            commit('SET_NAME', data.fullname);
            if (data.gold) {
                commit('SET_GOLD', data.gold);
            }
            if (data.experience) {
                commit('SET_EXPERIENCE', data.experience);
            }
            if (data.level) {
                commit('SET_LEVEL', data.level);
            }
            setAuth(JSON.stringify(data))
        }
    },

    // get user training info
    getTrainingInfo({ commit, state }) {
        return new Promise((resolve, reject) => {
            UserRepository.getTrainingInfo(state.token)
                .then((response) => {
                    const { data } = response;
                    if (data.success) {
                        let training = data.data.training;
                        commit('SET_TRAINING', training);
                    }
                    resolve(response)
                })
                .catch((error) => {
                    reject(error);
                });
        });
    },
    // get user training detail info
    getTrainingDetailInfo({ commit, state }) {
        return new Promise((resolve, reject) => {
            UserRepository.getTrainingDetailInfo(state.token)
                .then((response) => {
                    const { data } = response;
                    if (data.success) {
                        let detail = data.data;
                        commit('SET_TRAINING_DETAIL', detail);
                    }
                    resolve(response)
                })
                .catch((error) => {
                    reject(error);
                });
        });
    },
    // update gold/exp/level of user
    // updateDataUser({ commit, state }, data){
    //     console.log('dataStore', data)
    //     if (data) {
    //         if (data.gold) {
    //             commit('SET_GOLD', data.gold);
    //         }
    //         if (data.experience) {
    //             commit('SET_EXPERIENCE', data.experience);
    //         }
    //         if (data.level) {
    //             commit('SET_LEVEL', data.level);
    //         }
    //     }
    // }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
