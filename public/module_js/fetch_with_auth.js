import { refreshAccessToken } from "./refresh_token.js";
import { Encoder } from "./encrypt.js";

export async function fetchWithAuth(url, options = {}, retry = true) {
  const encoder = new Encoder();
  let token = encoder.decode(localStorage.getItem('token'));
  options.headers = options.headers || {};

  if (token) options.headers["Authorization"] = "Bearer " + token;

  try{
    const res = await fetch(url, options);

    if (res.status === 401 && retry) {
      const newToken = await refreshAccessToken();
      if (newToken) {
        token = newToken;
        options.headers["Authorization"] = "Bearer " + token;
        return fetchWithAuth(url, options, false);
      } else {
        localStorage.clear()
        window.location.href = "/login"
        return new Response(null, { status: 401 });
      }
    }

    return res;
  } catch (err){
    return new Response(null, { status: 500 });
  }
}
