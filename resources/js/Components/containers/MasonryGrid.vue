<template>
  <div class="masonry">
    <slot />
  </div>
</template>

<style lang="scss" scoped>
.masonry {
  column-count: 1;
  column-gap: 1rem;

  @media screen and (min-width: 600px) {
    column-count: 2;
  }

  @media screen and (min-width: 960px) {
    column-count: 3;
  }
}
</style>
