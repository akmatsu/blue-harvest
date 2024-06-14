import { useToasts } from '@/store/toasts';
import dayjs from 'dayjs';

export function formatDate(date?: string) {
  return dayjs(date).format('MMMM DD, YYYY');
}

export function formatBytes(bytes: number, decimals = 2) {
  if (bytes === 0) return '0 Bytes';

  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));

  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

export async function copyToClipboard(
  text: string,
  successMsg = 'Successfully copied to clipboard!',
) {
  const toast = useToasts();
  if (navigator.clipboard) {
    try {
      await navigator.clipboard.writeText(text);
      toast.success(successMsg);
    } catch (err) {
      toast.error(`Failed to copy text: ${err}`);
    }
  } else {
    try {
      const textArea = document.createElement('textarea');
      textArea.value = text;
      document.body.appendChild(textArea);
      textArea.select();
      // This function is deprecated but we're using it here in case we're not in a secure context (HTTP instead of HTTPS) or an older browser.
      document.execCommand('copy');
      toast.success(successMsg);
    } catch (err) {
      toast.error(`Failed to copy text: ${err}`);
    }
  }
}
