document.addEventListener("DOMContentLoaded", function () {
  new Sortable(document.getElementById("used-badges"), {
    group: "badges",
    animation: 150,
    ghostClass: "sortable-ghost",
  });

  new Sortable(document.getElementById("unused-badges"), {
    group: "badges",
    animation: 150,
    ghostClass: "sortable-ghost",
  });

  // Simpan hasil perubahan ke JSON
  document
    .getElementById("saveBadgesBtn")
    .addEventListener("click", function () {
      const used = [];
      const unused = [];

      document.querySelectorAll("#used-badges .badge-item").forEach((el) => {
        used.push(el.dataset.badge);
      });
      document.querySelectorAll("#unused-badges .badge-item").forEach((el) => {
        unused.push(el.dataset.badge);
      });

      const badgeConfig = { used, unused };
      console.log("Badge JSON:", badgeConfig);

      fetch("save_badges.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(badgeConfig),
      })
        .then((res) => res.json())
        .then((data) => {
          alert("Badge berhasil disimpan!");
        })
        .catch((err) => console.error(err));
    });
});
