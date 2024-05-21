export function required(input?: string | any[] | number) {
  if (input && (typeof input === 'number' || input.length)) {
    return true;
  }

  return 'Required.';
}

export function validEmail(input?: string) {
  const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  return (input && regex.test(input)) || 'Must be a valid email address.';
}
