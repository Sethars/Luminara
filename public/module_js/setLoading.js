// Loading button handler
export function setLoading (isLoading, btnId) {
  const btn = document.getElementById(btnId);

  if (!btn) {
    console.error(`Button with ID ${btnId} not found.`);
    return;
  }

  if (isLoading) {
    btn.disabled = true;
    btn.dataset.originalText = btn.innerHTML;
    btn.innerHTML = `
      <span class="spinner-border spinner-border-sm me-2" role="status"></span>`;
  } else {
    btn.disabled = false;
    btn.innerHTML = btn.dataset.originalText || "Submit";
  }
};