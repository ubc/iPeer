import { differenceInHours, differenceInSeconds, format } from 'date-fns'
import { map, filter, isEmpty, forEach } from 'lodash'
import { enCA } from 'date-fns/locale'

interface Entry {
  event: Event
  course: object
  group: object
  penalties: object[]
}
interface Event {
  is_ended: boolean
  is_released: boolean
  is_result_released: boolean
  is_submitted: string
}
interface Sort {
  key: string
  column: string
  direction: string
}
interface Paginate {
  page: number
  offset: number
  limit: number
}
interface Filter {
  timeframe: string
  limit: string[]
  search: string
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

export const unique = (entries: object[], object_key: string, column_key: string):string[] => {
  // @ts-ignore
  return [...new Set(map(entries, entry => entry[object_key]).map(obj => obj[column_key]))]
}

export const compareEntries = (key: string, column: string, direction = 'asc') => {
  return function innerSort(a: any, b: any) {
    let varA;
    let varB;
    let comparison = 0;
    if(column === 'due_date') {
      // check if property exist on objects
      if (!a[key].hasOwnProperty(column) || !b[key].hasOwnProperty(column)) return 0;
      varA = (typeof a[key][column] === 'string') ? Date.parse(a[key][column]) : a[key][column];
      varB = (typeof b[key][column] === 'string') ? Date.parse(b[key][column]) : b[key][column];
    } else {
      // check if property exist on objects
      if (!a[key].hasOwnProperty(column) || !b[key].hasOwnProperty(column)) return 0;
      varA = (typeof a[key][column] === 'string') ? a[key][column].toUpperCase() : a[key][column];
      varB = (typeof b[key][column] === 'string') ? b[key][column].toUpperCase() : b[key][column];
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

export const filterEntries = (entries: Entry[], _filter: Filter): object[] | boolean[] => {

  let newEntries: object[] | boolean[] = entries

  if(_filter?.timeframe !== 'all') {
    let _entries: object[] | boolean[] = []
    filter(newEntries, (entry) => {
      if (!entry.hasOwnProperty('course')) return 0;
      // @ts-ignore
      if (entry?.course?.term?.includes(_filter?.timeframe)) {
        // @ts-ignore
        _entries.push(entry);
      }
    })
    newEntries = _entries
  }

  if(!isEmpty(_filter.limit)) {
    let filtered_entries: object[] | boolean[] = []
    // @ts-ignore
    filter(newEntries, (entry: Entry) => {
      if (!entry.hasOwnProperty('event')) return 0;
      if (entry?.event?.is_submitted === '1') {
        forEach(_filter.limit, (item) => {
          if(item === 'can_edit' && !entry?.event?.is_result_released) {
            // @ts-ignore
            filtered_entries.push(entry)
          }
          if(item === 'can_view' && entry?.event?.is_result_released) {
            // @ts-ignore
            filtered_entries.push(entry)
          }
        })
      }
    })
    return filtered_entries
  }
  return newEntries
}

export const paginateEntries = (entries: Event[], offset: number, end: number) => {
  if(!isEmpty(entries)) {
    // @ts-ignore
    return entries.slice(Number(offset), Number(end))
  }
  return
}

// @ts-ignore
// export const sortEntries = (arr: object[], sort: Sort): object[] => {
//   console.log({arr, sort})
//   if(arr && sort) {
//     const { key, column, direction } = sort
//     if(direction === 'asc') {
//       // @ts-ignore
//       return arr.sort((a, b) => a[key][column].localeCompare(b[key][column]))
//     }
//     if(direction === 'desc') {
//       // @ts-ignore
//       return arr.sort((a, b) => b[key][column].localeCompare(a[key][column]))
//     }
//   }
// }

export function jsonToFormData (json){
  const FORM_DATA = new FormData();
  if(!json) return FORM_DATA;
  try{
    json = JSON.parse(json);
  }catch{}
  if(typeof json != 'object'||Array.isArray(json)) return FORM_DATA;
  for(let key in json) {
    let value = json[key];
    if(Array.isArray(value)){
      value.forEach((v)=>{
        FORM_DATA.append(key+"[]", v);
      });
    }
    else
      FORM_DATA.append(key, typeof json[key] == 'object' ? JSON.stringify(json[key]) : json[key]);
  }

  return FORM_DATA;
}