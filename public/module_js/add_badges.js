import { fetchWithAuth } from "./fetch_with_auth.js";

export async function addBadge (newBadge) {
  const profile = JSON.parse(localStorage.getItem('profile'));
  if (!profile) {
    console.error("Profile not found in localStorage");
    return;
  }

  let badges = profile.badges;
  if (typeof badges === "string") {
    try {
      badges = JSON.parse(badges);
    } catch (e) {
      console.error("Gagal parse badges:", e);
      badges = { used: [], unused: [] };
    }
  }
  if (!badges.used) badges.used = [];
  if (!badges.unused) badges.unused = [];

  if (!badges.unused.includes(newBadge) && !badges.used.includes(newBadge)) {
    badges.unused.push(newBadge);
  } else {
    console.log("Badge sudah ada");
    return;
  }
  profile.badges = badges;

  try {
    const res = await fetchWithAuth("api/addBadges", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        badgeName: newBadge
      }),
    });

    const data = await res.json();
    if(data.success){
      localStorage.setItem("profile", JSON.stringify(profile));
    }
  } catch (err) {
    console.error("Error update badge:", err);
  }
}
