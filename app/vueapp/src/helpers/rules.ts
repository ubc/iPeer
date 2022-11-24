// import { object, string, boolean } from 'yup'
import * as Yup from 'yup'
import {isEmpty} from 'lodash'

// Evaluation Validation
export const validateLikert = Yup.string().required().nullable()
export const validateRequired = Yup.string().required().nullable()
export const validateSentence = Yup.string().required().trim().nullable()
export const validateParagraph = Yup.string().required().trim().nullable()

// UserProfile Account Validation
export const validateProfileTextInput = Yup.string().required().trim().nullable()
export function validateFirstNameField(value: string) {
  if (!value) {
    return 'Please enter your first name.';
  } return true;
}
export function validateLastNameField(value: string) {
  if (!value) {
    return 'Please enter your last name.';
  } return true;
}
export function validateEmailAddressField(value: string) {
  if (!/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i.test(value)) {
    return 'Please enter a valid email address.';
  } return true;
}
// UserProfile Password Validation
export const validateProfileOldPasswordInput = Yup.string().trim().nullable()
export const validateProfileNewPasswordInput = Yup.string().trim().nullable()
export const validateProfileConfirmPasswordInput = Yup.string().trim()
  .test('passwords-match', 'Please make sure your new password matches in both fields.', (value) => {
    // @ts-ignore
    const parentValue = parent['data[User][temp_password]']['value'].toString()
    if(isEmpty(parentValue)) return true
    return parentValue === value;
}).nullable()

// misc.
export const requiredTextRules = (value: string) => {
  return Yup.string().required().min(2)
}
export const validateText = (value: string) => {
  if (!value) {
    return 'This field is required';
  } return true
}
export const validateRadio = (value: string) => {
  if (!value) {
    return 'This field is required';
  } return true
}
export const validateCheck = (value: string) => {
  if (!value) {
    return 'This field is required';
  } return true
}
export const validateEmail = (value: string) => {
  if (!value) {
    return 'This field is required';
  }
  if (!/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i.test(value)) {
    return 'This field must be a valid email';
  }
}