// Show modal (Bootstrap)
export function showModal (modalId) {
  const modalElement = document.getElementById(modalId);
  if (modalElement) {
    const modal =
      bootstrap.Modal.getInstance(modalElement) ||
      new bootstrap.Modal(modalElement);
    modal.show();
  } else {
    console.error(`Modal with ID ${modalId} not found.`);
  }
};