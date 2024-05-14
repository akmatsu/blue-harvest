import { onMounted, onBeforeUnmount } from 'vue';
import { InertiaForm } from '@inertiajs/vue3';

export function useClosePrompt(
  form: InertiaForm<any>,
  message = 'Are you sure you want to leave this page? Leaving now could result in losing unsaved changes.',
) {
  function handleClosePrompt(event: Event) {
    if (form.isDirty) {
      event.preventDefault();
      event.returnValue = message; // Properly setting the returnValue
      return message; // Ensuring the message is returned
    }
  }

  onMounted(() => {
    window.addEventListener('beforeunload', handleClosePrompt);
  });

  onBeforeUnmount(() => {
    window.removeEventListener('beforeunload', handleClosePrompt);
  });
}
