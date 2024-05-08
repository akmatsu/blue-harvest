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
  try {
    toast.success(successMsg);
    await navigator.clipboard.writeText(text);
  } catch (err) {
    toast.error(`Failed to copy text: ${err}`);
  }
}
