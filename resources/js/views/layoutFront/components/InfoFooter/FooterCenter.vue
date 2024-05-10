<template>
    <div class="privacy-info d-flex a-content-center c-flex">
        <p>{{ $t('footer.namecontent') }}</p>
        <p>{{ $t('footer.registerno') }} Copyright © 2024 GOSU. All Rights Reserved.</p>
        <p><a :href="`mailto:info@gosu.vn`">Email: info@gosu.vn</a></p>
    </div>
</template>

<script>
export default {
    name: 'FooterCenter',
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