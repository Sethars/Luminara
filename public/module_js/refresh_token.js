import { Encoder } from "./encrypt.js";

export async function refreshAccessToken() {
  const encoder = new Encoder();
  const res = await fetch("/api/refreshToken", {
    method: "POST",
    credentials: "include"
  });
  const data = await res.json();
  if (res.ok) {
    localStorage.setItem('token', encoder.encode(data.token))
    return data.token;
  }
  return null;
}