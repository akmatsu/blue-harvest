export function required(input?: string | any[] | number) {
  if (input && (typeof input === 'number' || input.length)) {
    return true;
  }

  return 'Required.';
}
