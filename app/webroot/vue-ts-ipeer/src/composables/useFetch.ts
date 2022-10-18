export default function useFetch(url: string, options: {method?: string, timeout?: number, body?: HTMLFormElement}) {
  let body = {}
  let headers = {}
  switch (options.method) {
    case 'GET':
      headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
      break
    case 'POST':
    case 'PUT':
      headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' // 'Content-Type': 'application/json'
      }
      break
  }

  return new Promise(async (resolve, reject) => {
    try {
      const fetchResponse = await fetch(url, {
        method: options.method,
        headers: headers,
        // @ts-ignore
        body: options.body
      });
      setTimeout(async () => {
        return resolve(await fetchResponse.json())
      }, options.timeout)
    }
    catch (err) {
      // reject("Failed to load data")
      reject(err)
    }
  })

}
