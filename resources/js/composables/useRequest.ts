import { useToasts } from '@/store/toasts';

/**
 * This function abstracts out the complexity of handing async requests. Handles
 * loading as well as options for onSuccess and onError callback functions.
 */
export function useRequest<T = any, TArgs extends Array<any> = Array<any>>(
  requestHandler: (...args: TArgs) => Promise<T>,
  options?: {
    onSuccess?: (result: Awaited<T>) => void;
    onError?: (error: any) => void;
    silent?: boolean;
  },
) {
  const loading = ref(false);
  const error = ref<any>();
  const result = ref<T>();
  const toast = useToasts();

  async function exec(...args: TArgs) {
    try {
      _startLoading();
      _clearError();

      const res = await requestHandler(...args);
      res ? _setResult(res) : _clearResult();
      if (options?.onSuccess) options.onSuccess(res);
    } catch (err) {
      _clearResult();
      _setError(err);
      if (!options?.silent)
        toast.show({
          text: 'Oops! Something went wrong. Please try again.',
          color: 'error',
        });

      if (options?.onError) options.onError(err);
    } finally {
      _stopLoading();
    }
  }

  function _startLoading() {
    loading.value = true;
  }

  function _stopLoading() {
    loading.value = false;
  }

  function _setError(err: any) {
    error.value = err;
  }

  function _clearError() {
    error.value = undefined;
  }

  function _setResult(val: T) {
    result.value = val;
  }

  function _clearResult() {
    result.value = undefined;
  }

  return {
    exec,
    result,
    error,
    loading,
  };
}
