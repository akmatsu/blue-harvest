export type ColorOptions =
  | 'default'
  | 'success'
  | 'warning'
  | 'error'
  | 'info'
  | 'primary';

export type Toast = {
  text: string;
  color: ColorOptions;
  timeout?: number;
};

export const useToasts = defineStore('toasts', () => {
  const toasts = ref<Toast[]>([]);

  function show({ text = 'Text', color = 'default', timeout }: Partial<Toast>) {
    toasts.value.unshift({
      text,
      color,
      timeout,
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
