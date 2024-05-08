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

  function _show(
    text = 'Text',
    color: ColorOptions = 'default',
    timeout = 3500,
  ) {
    toasts.value.unshift({
      text,
      color,
      timeout,
    });
  }

  function hide(index: number) {
    toasts.value.splice(index, 1);
  }

  function show(text: string, timeout?: number) {
    _show(text, 'default', timeout);
  }
  function success(text: string, timeout?: number) {
    _show(text, 'success', timeout);
  }
  function error(text: string, timeout?: number) {
    _show(text, 'error', timeout);
  }
  function info(text: string, timeout?: number) {
    _show(text, 'info', timeout);
  }
  function warn(text: string, timeout?: number) {
    _show(text, 'warning', timeout);
  }

  return {
    toasts,
    show,
    success,
    error,
    info,
    warn,
    hide,
  };
});
