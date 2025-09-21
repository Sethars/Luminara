// Update localStorage data
export function updateLocalData (storageKey, field, value) {
  const data = JSON.parse(localStorage.getItem(storageKey));

  if (!data) {
    console.error(storageKey + " tidak ditemukan di localStorage");
    return;
  }

  try {
    data[field] = value;
    localStorage.setItem(storageKey, JSON.stringify(data));
    console.log(`${storageKey}.${field} berhasil diupdate jadi:`, value);
  } catch (err) {
    console.error("Gagal update data:", err);
  }
};