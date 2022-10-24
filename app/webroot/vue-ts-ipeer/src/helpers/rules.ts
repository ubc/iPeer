


export function validateText(value: string) {
  // if the field is empty
  if (!value) {
    //this.isEager = true;
    return 'This field is required';
  }
  return true
}

export function validateRadio(value: string) {
  // if the field is empty
  if (!value) {
    //this.isEager = true;
    return 'This field is required';
  }
  return true
}

export function validateCheck(value: string) {
  // if the field is empty
  if (!value) {
    //this.isEager = true;
    return 'This field is required';
  }
  return true
}

export function validateEmail(value: string) {
  // if the field is empty
  if (!value) {
    //this.isEager = true;
    return 'This field is required';
  }

  // if the field is not a valid email
  if (!/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i.test(value)) {
    //this.isEager = true;
    return 'This field must be a valid email';
  }
}