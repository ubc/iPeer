import { string } from 'yup'
import {isEmpty} from 'lodash'

// Evaluation Validation
export const validateLikert = string().required().trim().nullable()
export const validateSentence = string().required().min(10).trim().nullable()
export const validateParagraph = string().required().min(20).trim().nullable()

// UserProfile Validation
export const validateProfileTextInput = string().required().min(2).trim().nullable()
export const validateProfileEmailInput = string().required().email().trim().nullable()
// UserProfile Password Validation
export const validateProfileOldPasswordInput = string().trim()
export const validateProfileNewPasswordInput = string().trim()
export const validateProfileConfirmPasswordInput = string().trim()
  .test('passwords-match', 'New Passwords do not match', (value) => {
    // @ts-ignore
    const parentValue = parent['data[User][temp_password]']['value'].toString()
    if(isEmpty(parentValue)) return true
    return parentValue === value;
})

// misc.
export const requiredTextRules = (value: string) => {
  return string().required().min(2)
}
export const validateText = (value: string) => {
  // if the field is empty
  if (!value) {
    //this.isEager = true;
    return 'This field is required';
  }
  return true
}
export const validateRadio = (value: string) => {
  // if the field is empty
  if (!value) {
    //this.isEager = true;
    return 'This field is required';
  }
  return true
}
export const validateCheck = (value: string) => {
  // if the field is empty
  if (!value) {
    //this.isEager = true;
    return 'This field is required';
  }
  return true
}
export const validateEmail = (value: string) => {
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