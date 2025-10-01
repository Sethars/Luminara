import { refreshAccessToken } from "./refresh_token.js";
import { decode } from "./encrypt.js";

export async function fetchWithAuth(url, options = {}, retry = true) {
  let token = decode(localStorage.getItem('token'));
  options.headers = options.headers || {};

  if (token) options.headers["Authorization"] = "Bearer " + token;

  let res = await fetch(url, options);

  if (res.status === 401 && retry) {
    const newToken = await refreshAccessToken();
    if (newToken) {
      token = newToken; // update token
      options.headers["Authorization"] = "Bearer " + token;
      return fetchWithAuth(url, options, false);
    } else {
      localStorage.clear()
      window.location.href = "/login"
    }
  }

  return res;
}
