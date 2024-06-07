import { MaybeRef, ref, toValue } from 'vue';

export function useAspectRatio(w: MaybeRef<number>, h: MaybeRef<number>) {
  const width = ref(toValue(w));
  const height = ref(toValue(h));
  const aspectRatio = ref<number>(toValue(w) / toValue(h));
  const isAspectRatioLocked = ref(true);

  /** Call this after width has changed to update the height or the aspect ratio */
  function updateHeight() {
    checkAspectRatio(
      () => (height.value = Math.round(width.value / aspectRatio.value)),
    );
  }

  /** Call this after height has changed to update the width or the aspect ratio */
  function updateWidth() {
    checkAspectRatio(
      () => (width.value = Math.round(height.value / aspectRatio.value)),
    );
  }

  /** This is a high order function that either runs the callback or it updates the aspect ratio */
  function checkAspectRatio(cb: () => void) {
    if (isAspectRatioLocked.value) {
      cb();
    } else {
      aspectRatio.value = width.value / height.value;
    }
  }

  return {
    aspectRatio,
    width,
    height,
    isAspectRatioLocked,
    updateHeight,
    updateWidth,
  };
}
