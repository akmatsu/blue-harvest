<script lang="ts" setup>
import { useToasts } from '@/store/toasts';

const toast = useToasts();
</script>

<template>
  <div class="toast-stack">
    <TransitionGroup name="toast-stack">
      <v-snackbar
        v-for="(t, i) in toast.toasts"
        :key="t.id"
        v-model="t.show"
        :color="t.color"
        :attach="true"
        absolute
      >
        {{ t.message }}
      </v-snackbar>
    </TransitionGroup>
  </div>
</template>

<style lang="scss" scoped>
.toast-stack {
  position: fixed;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: end;
  gap: 1rem;
  bottom: 0;
  width: 100%;
  height: 100vh !important;
  pointer-events: none;
  z-index: 1000;
  overflow: hidden;
  padding: 1rem;
}

.toast-stack > * {
  // pointer-events: initial;
}

.toast-stack-move,
.toast-stack-enter-active,
.toast-stack-leave-active {
  transition: all 0.15s ease-in-out;
}

.toast-stack-enter-from,
.toast-stack-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

.toast-stack-leave-active {
  position: absolute;
}
</style>
