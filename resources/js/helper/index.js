import store from '@/store';
const variables = {
    'menuText':'#bfcbd9',
    'menuActiveText':'#409EFF',
    'subMenuActiveText':'#f4f4f5',
    'menuBg':'#304156',
    'menuHover':'#263445',
    'subMenuBg':'#1f2d3d',
    'subMenuHover':'#001528',
    'sideBarWidth': '210px',
    'theme':'#1890ff'
}
const variablesMarket = {
    'bgColor' :"#fff",
    'textColor'   :"#303133",
    'activeColor' : "#303133",
}
const typeMarket = {
    'course':'course',
    'equipment':'equipment'
}
const colorMenuMarket = {
    'info':'#409eff',
    'quest':'#ed8b33',
    'equipment':'#ffba00',
    'trip':'#42b983',
    'market':'#673ab7',
}
// update vàng, điểm kinh nghiệm, trang bị/khoá học của user
const updateInfoUser = (data) => {
    //if()
    store.commit('user/SET_TEST');
}
const statusQuest = {
    'pending'  : '01',
    'received' : '02',
};
const percentExp = (data) => {
    let percent = Math.round(data['experience']/data['exp_level'] * 100);
    return percent;
}
const languages = () => {
    let languages = [
        {
            'key': 'vi',
            'value': 'VI',
        },
        {
            'key': 'en',
            'value': 'EN',
        },
    ]
    
    return languages
}
export default {
    variables,
    typeMarket,
    variablesMarket,
    colorMenuMarket,
    updateInfoUser,
    statusQuest,
    percentExp,
    languages
};