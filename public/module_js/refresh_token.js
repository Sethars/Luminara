import { encode } from "./encrypt.js";

export async function refreshAccessToken() {
  const res = await fetch("/api/refreshToken", {
    method: "POST",
    credentials: "include"
  });
  const data = await res.json();
  if (res.ok) {
    localStorage.setItem('token', encode(data.token))
    return data.token;
  }
  return null;
}