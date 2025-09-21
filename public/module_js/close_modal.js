// Close modal (Bootstrap)
export function closeModal (modalId) {
  const modalElement = document.getElementById(modalId);
  if (modalElement) {
    const modal =
      bootstrap.Modal.getInstance(modalElement) ||
      new bootstrap.Modal(modalElement);
    modal.hide();
  } else {
    console.error(`Modal with ID ${modalId} not found.`);
  }
};