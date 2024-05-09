<template>
    <div class="app-container">
        <section class="open-book">
            <header></header>
            <footer></footer>
        </section>
    </div>
</template>

<script>
export default {
  name: "FlipHandBook",
  data() {
    return {};
  },
  methods: {
    onFlipLeftStart(page) {
      console.log("flip-left-start", page);
    },
    onFlipLeftEnd(page) {
      console.log("flip-left-end", page);
      window.location.hash = "#" + page;
    },
    onFlipRightStart(page) {
      console.log("flip-right-start", page);
    },
    onFlipRightEnd(page) {
      console.log("flip-right-end", page);
      window.location.hash = "#" + page;
    },
    setPageFromHash() {
      const n = parseInt(window.location.hash.slice(1), 10);
      if (isFinite(n)) this.pageNum = n;
    },
  },
  mounted() {},
};
</script>

<style>

</style>
