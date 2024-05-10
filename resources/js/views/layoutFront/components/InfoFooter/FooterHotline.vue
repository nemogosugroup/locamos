<template>
    <div class="contact-hotline d-flex a-center">
        <a target="_blank" href="https://www.facebook.com/gosucorp.vn" class="link-ic-ft"><i class="ri-tiktok-fill"></i></a>
        <a target="_blank" href="https://www.facebook.com/gosucorp.vn" class="link-ic-ft"><i class="ri-facebook-fill"></i></a>
        <div class="hotline-ft p-relative">
            <p>{{ $t('footer.hotline') }}</p>
            <p class="c-orange"><a :href="`tel:${'19007189'}`">{{ $t('footer.phonehotline') }}</a></p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FooterHotline',
    data() {
        return {}
    }
}
</script>

<style lang="scss" scoped>
    .c-orange {
        color: #ed9534 !important;
    }
</style>