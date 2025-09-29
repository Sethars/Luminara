import { refreshAccessToken } from "./refresh_token.js";
import { decode } from "./encrypt.js";


let token = decode(localStorage.getItem('token'));

export async function fetchWithAuth(url, options = {}, retry = true) {
  options.headers = options.headers || {};

  if (token) options.headers["Authorization"] = "Bearer " + token;

  let res = await fetch(url, options);

  if (res.status === 401 && retry) {
    const newToken = await refreshAccessToken();
    if (newToken) {
      token = newToken; // update token
      options.headers["Authorization"] = "Bearer " + token;
      return fetch(url, options);
    } else {
      localStorage.clear()
      window.location.href = "/login"
    }
  }

  return res;
}
