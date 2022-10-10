import { differenceInHours, differenceInSeconds, format } from 'date-fns'
import { map } from 'lodash'
import { enCA } from 'date-fns/locale'
import {array} from "yup";

interface Sort {
  key: string
  column: string
  by: string
}

export const sleep = async (time: number) => await new Promise(resolve => setTimeout(() => resolve(true), time))

export const Statuses = {Loading: 'LOADING', Ready: 'READY', Empty: 'EMPTY', Error: 'ERROR'}

export const longDateFormat = (date: string | Date): string => {
  return format(new Date(date), 'EE, MMM d, yyyy @ h:mm aaaa', { locale: enCA })
}

export const shortDateFormat = (date: string | Date): string => {
  return format(new Date(date), 'MMM d, yyyy', { locale: enCA })
}

export const isOverDue = (date: string | Date): boolean => {
  const due_date = new Date(date)
  return differenceInSeconds(due_date, new Date()) > 24*60*60
}

export const isDueTomorrow = (date: string | Date): boolean => {
  const due_date = new Date(date)
  return differenceInSeconds(due_date, new Date()) <= 24*60*60
}

export const unique = (array: object[], object_key: string, column_key: string):string[] => {
  // @ts-ignore
  return [...new Set(map(array, entry => entry[object_key]).map(obj => obj[column_key]))]
}

export const compare = (category: string, key: string, direction = 'asc') => {
  return function innerSort(a: any, b: any) {
    let varA;
    let varB;
    let comparison = 0;
    if(key === 'due_date') {
      // check if property exist on objects
      if (!a[category].hasOwnProperty(key) || !b[category].hasOwnProperty(key)) return 0;
      varA = (typeof a[category][key] === 'string') ? Date.parse(a[category][key]) : a[category][key];
      varB = (typeof b[category][key] === 'string') ? Date.parse(b[category][key]) : b[category][key];
    } else {
      // check if property exist on objects
      if (!a[category].hasOwnProperty(key) || !b[category].hasOwnProperty(key)) return 0;
      varA = (typeof a[category][key] === 'string') ? a[category][key].toUpperCase() : a[category][key];
      varB = (typeof b[category][key] === 'string') ? b[category][key].toUpperCase() : b[category][key];
    }
    if (varA > varB) {
      comparison = 1;
    } else if (varA < varB) {
      comparison = -1;
    }
    return (
      (direction === 'desc') ? (comparison * -1) : comparison
    );
  };
}


// @ts-ignore
export const compareBy = (arr: object[], sort: Sort): object[] => {
  if(arr && sort) {
    const { key, column, by } = sort
    if(by === 'asc') {
      // @ts-ignore
      return arr.sort((a, b) => a[key][column].localeCompare(b[key][column]))
    }
    if(by === 'desc') {
      // @ts-ignore
      return arr.sort((a, b) => b[key][column].localeCompare(a[key][column]))
    }
  }
}