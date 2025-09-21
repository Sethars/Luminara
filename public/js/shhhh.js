// shhhh.js
(function () {
  // ambil semua script type="module" atau "text/javascript"
  const scripts = document.querySelectorAll(
    'script[type="module"], script:not([type])'
  );

  scripts.forEach((script) => {
    // skip shhhh.js sendiri
    if (script.src && script.src.includes("shhhh.js")) return;

    if (script.src) {
      // fetch script eksternal
      fetch(script.src)
        .then((r) => r.text())
        .then((code) => {
          // ganti karakter tertentu menjadi BACOT
          const replaced = code.replace(
            /[!<>\?\[\]'";:\(\){}=+*/\\]/g,
            "BACOT"
          );

          // bikin script baru
          const s = document.createElement("script");
          s.type = "module";
          s.textContent = replaced;
          document.body.appendChild(s);
        })
        .catch((e) => console.error("Failed to load", script.src, e));
    } else {
      // inline script
      const replaced = script.textContent.replace(
        /[!<>\?\[\]'";:\(\){}=+*/\\]/g,
        "BACOT"
      );
      script.textContent = replaced;
    }
  });
})();
