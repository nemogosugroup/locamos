<template>
    <div class="privacy-info">
        <p class="c-orange"><strong>{{ $t('footer.title') }}</strong></p>
        <p>{{ $t('footer.address') }}</p>
        <p>{{ $t('footer.phone') }}</p>
    </div>
</template>

<script>
export default {
    name: 'FooterPrivacy',
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