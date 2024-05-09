<template>
    <ul class="list-social social-icons">
        <li><a href="#" class="social"><i class="ri-facebook-circle-fill"></i></a></li>
        <li><a href="#" class="social"><i class="ri-home-2-fill"></i></a></li>
        <li><a href="#" class="social"><i class="ri-youtube-fill"></i></a></li>
        <li><a href="#" class="social"><i class="ri-google-fill"></i></a></li>
        <li><a v-show="isCheckShare" href="#" class="social"><i class="ri-share-fill"></i></a></li>
    </ul>
</template>
<script>
// import RepositoryFactory from '@/utils/RepositoryFactory';
// const HandBookRepository = RepositoryFactory.get('hand_book');  
// import dataFake from './fake'; 
// import imagePerson from '@/assets/images/nhanvat.png';
export default {
    name: 'Socials',
    props: {
        isCheckShare: {
            required: true,
            type: Boolean
        }
    },
    data() {
        return {
        }
    },

    filters: {

    },

    created() {
    },

    mounted() {
    },

    methods: {
    }
}

</script>
<style lang="scss" scoped>
@import '@style/variables.scss';
// .list-social {
//     display: flex;
//     flex-direction: row;
//     flex-wrap: nowrap;
//     justify-content: flex-end;
//     align-items: baseline;
//     align-content: stretch;

//     .social {
//         width: 30px;
//         height: 30px;
//         border-radius: 100%;
//         text-align: center;
//         margin-left: 5px;
//         border: aliceblue;
//         font-size: 18px;
//         padding: 5px;
//         color: #fff;
//         background: #409eff;
//         ;
//     }
// }

.social-icons {
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: flex-end;
    align-items: baseline;
    align-content: stretch;
    li {
        display: inline-block;
        margin: 0.15em;
        position: relative;
        font-size: 1em;
        i {
            color: #fff;
        position: absolute;
        top: calc(50% - 12px);
        left: calc(50% - 10px);
        transition: all 265ms ease-out;
        font-size: 20px;
        line-height: 20px;
        }
        a {
            display: inline-block;
            &:before {
                transform: scale(1);
                -ms-transform: scale(1);
                -webkit-transform: scale(1);
                content: " ";
                width: 35px;
                height: 35px;
                border-radius: 100%;
                display: block;
                background: linear-gradient(45deg, $menuActiveText, $light-blue);
                transition: all 265ms ease-out;
            }
            &:hover{
                &::before{
                    transform: scale(0);
                    transition: all 265ms ease-in;
                }
                i {
                    transform: scale(1.8);
                    -ms-transform: scale(1.8);
                    -webkit-transform: scale(1.8);
                    color: $menuActiveText;
                    background: -webkit-linear-gradient(45deg, $menuActiveText, $light-blue);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    transition: all 265ms ease-in;
                }
            }
        }
    }
}
</style>