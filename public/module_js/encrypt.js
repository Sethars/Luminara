export function encode(str, key = "luminarakarburatorcyrenehsrpinkgirlhair") {
  let result = "";
  for (let i = 0; i < str.length; i++) {

    const charCode = str.charCodeAt(i) ^ key.charCodeAt(i % key.length);
    result += String.fromCharCode(charCode);
  }

  return btoa(result);
}

export function decode(encoded, key = "luminarakarburatorcyrenehsrpinkgirlhair") {
  const decoded = atob(encoded);
  let result = "";
  for (let i = 0; i < decoded.length; i++) {
    const charCode = decoded.charCodeAt(i) ^ key.charCodeAt(i % key.length);
    result += String.fromCharCode(charCode);
  }
  return result;
}