export type ColorOptions =
  | 'default'
  | 'success'
  | 'warning'
  | 'error'
  | 'info'
  | 'primary';
export type Toast = {
  message: string;
  color: ColorOptions;
  id: number;
  show: boolean;
};

export const useToasts = defineStore('toasts', () => {
  const toasts = ref<Toast[]>([]);

  function show(message: string, color: ColorOptions = 'default') {
    const id = toasts.value.length ? toasts.value[0].id + 1 : 1;

    toasts.value.unshift({
      message,
      color,
      id,
      show: true,
    });
  }

  function hide(index: number) {
    toasts.value.splice(index, 1);
  }

  return {
    toasts,
    show,
    hide,
  };
});
